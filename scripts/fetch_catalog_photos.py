"""
Fetches freely-licensed photographs for fragrance houses and perfumes.

This is the same Wikipedia-then-Commons approach used for notes, with two
differences that matter for commercial subjects:

  * The subject check looks for company/product wording ("fashion house",
    "perfume", "fragrance company") instead of botanical wording.
  * A file is used ONLY when it exists on Wikimedia Commons with a licence.
    Most brand logos on Wikipedia are non-free "fair use" uploads hosted
    locally rather than on Commons; those are skipped rather than rehosted.

Expect a low hit rate for perfumes: individual fragrances rarely have their
own encyclopedia article. Anything unconfirmed keeps its generated placeholder.

Usage:
    python scripts/fetch_catalog_photos.py --kind brand
    python scripts/fetch_catalog_photos.py --kind perfume --limit 20
"""

import argparse
import io
import json
import os
import re
import time
import unicodedata
import urllib.parse
import urllib.request

AGENT = {"User-Agent": "RalphDeVincaPerfumary/1.0 (fragrance encyclopedia; local development)"}
WIKI_SUMMARY = "https://en.wikipedia.org/api/rest_v1/page/summary/"
WIKI_API = "https://en.wikipedia.org/w/api.php"
COMMONS_API = "https://commons.wikimedia.org/w/api.php"

WORD_RE = re.compile("[a-z]+")

# Wording that confirms the article really is about the house or the fragrance.
SUBJECT_WORDS = (
    "perfume", "perfumer", "fragrance", "cologne", "scent", "parfum",
    "fashion", "luxury", "couture", "cosmetic", "brand", "company",
    "house", "maison", "manufacturer", "retailer", "boutique", "label",
)

KINDS = {
    "brand": {
        "dir": os.path.join("public", "images", "brands"),
        "input": "storage/app/brands-needing-photos.tsv",
        "manifest": "storage/app/brand-photo-manifest.json",
        "terms": (" (brand)", " (perfume house)", " (company)", " perfume house", ""),
        # "Dior" should still match the article "Christian Dior SE".
        "strict": False,
        "commons_fallback": True,
    },
    "perfume": {
        "dir": os.path.join("public", "images", "perfumes"),
        "input": "storage/app/perfumes-needing-photos.tsv",
        "manifest": "storage/app/perfume-photo-manifest.json",
        "terms": (" (perfume)", " (fragrance)", ""),
        # A fragrance name is an ordinary phrase, so the article title has to
        # contain it outright and the open Commons search is not used at all:
        # it matched "Jazz Club" to a jazz club logo and "More Than Words" to
        # a photograph of a cat in a bookshop.
        "strict": True,
        "commons_fallback": False,
    },
}


def get_json(url, attempts=3):
    for attempt in range(attempts):
        try:
            with urllib.request.urlopen(urllib.request.Request(url, headers=AGENT), timeout=30) as r:
                return json.load(r)
        except Exception:
            if attempt + 1 < attempts:
                time.sleep(1.5 * (attempt + 1))

    return None


def summary(title):
    return get_json(WIKI_SUMMARY + urllib.parse.quote(title.replace(" ", "_")))


def is_subject(page):
    """Whether the article is about a fragrance house or a fragrance."""
    blob = (page.get("description") or "") + " " + (page.get("extract") or "")[:400]

    return any(word.startswith(SUBJECT_WORDS) for word in WORD_RE.findall(blob.lower()))


def ascii_key(text):
    """
    Lowercase alphanumerics with accents folded to plain letters.

    Without the folding "Hermes" and "Hermes" with an accent produce different
    keys, so a correct Commons photograph was rejected as a mismatch.
    """
    folded = unicodedata.normalize("NFKD", text or "")
    folded = folded.encode("ascii", "ignore").decode("ascii")

    return re.sub("[^a-z0-9]", "", folded.lower())


def title_matches(page, name, strict=False):
    """
    Whether the article is actually about this subject.

    The subject check alone is not enough: searching "Maison Francis Kurkdjian"
    returned Diptyque, which is also a perfume house and so passed every other
    test. One name has to contain the other once punctuation is stripped.
    """
    wanted = ascii_key(name)
    title = ascii_key(page.get("title") or "")

    if not wanted or not title:
        return False

    if strict:
        return wanted in title

    return wanted in title or title in wanted


def usable(page):
    return bool(page) and page.get("type") == "standard" and "thumbnail" in page and is_subject(page)


def search_titles(term, limit=3):
    data = get_json(WIKI_API + "?" + urllib.parse.urlencode({
        "action": "query", "list": "search", "srsearch": term,
        "srlimit": str(limit), "format": "json",
    }))

    if not data:
        return []

    return [hit["title"] for hit in data.get("query", {}).get("search", [])]


# Concentration wording is part of the catalogue name but never part of the
# encyclopedia title: the article is "Coco Mademoiselle", not "Coco
# Mademoiselle Eau de Parfum", so strict matching failed on every fragrance.
CONCENTRATIONS = (
    "extrait de parfum", "eau de parfum", "eau de toilette", "eau de cologne",
    "eau fraiche", "le parfum", "parfum intense", "cologne intense",
    "parfum", "cologne", "elixir intense", "edp", "edt",
)


def strip_concentration(name):
    trimmed = name.strip()

    for phrase in CONCENTRATIONS:
        if ascii_key(trimmed).endswith(ascii_key(phrase)):
            cut = trimmed.lower().rfind(phrase.split()[0])

            if cut > 0:
                candidate = trimmed[:cut].strip(" -")

                # Keep the full name when trimming would leave too little to
                # identify anything, as with "Y Eau de Parfum".
                if len(ascii_key(candidate)) >= 4:
                    return candidate

            break

    return trimmed


def find_article(name, terms, match_name=None, strict=False):
    match_name = match_name or name
    exact = summary(name)

    if usable(exact) and title_matches(exact, match_name, strict):
        return exact

    for suffix in terms:
        for title in search_titles(name + suffix):
            page = summary(title)

            if usable(page) and title_matches(page, match_name, strict):
                return page

        time.sleep(0.3)

    return None


def commons_file(filename):
    """Author, licence and a full-width URL, or empty when not on Commons."""
    data = get_json(COMMONS_API + "?" + urllib.parse.urlencode({
        "action": "query", "titles": "File:" + filename,
        "prop": "imageinfo", "iiprop": "url|extmetadata",
        "iiurlwidth": "1024", "format": "json",
    }))

    if not data:
        return "", "", "", ""

    for page in data.get("query", {}).get("pages", {}).values():
        if "missing" in page:
            continue

        info = (page.get("imageinfo") or [{}])[0]
        meta = info.get("extmetadata", {})
        author = re.sub(r"<[^>]+>", "", meta.get("Artist", {}).get("value", "")).strip()

        return (
            re.sub(r"\s+", " ", author),
            meta.get("LicenseShortName", {}).get("value", ""),
            "https://commons.wikimedia.org/wiki/File:" + urllib.parse.quote(filename),
            info.get("thumburl") or info.get("url") or "",
        )

    return "", "", "", ""


# Words that mark a Commons file as a photo of a place or a product rather
# than, say, a person who happens to share the brand name.
COMMONS_HINTS = (
    "boutique", "store", "shop", "flagship", "storefront", "facade",
    "building", "counter", "perfume", "parfum", "fragrance", "bottle",
    "flacon", "logo", "packaging", "advertisement", "poster", "factory",
)


def commons_fallback(name):
    """
    Looks for a freely-licensed Commons photograph directly.

    Used when the Wikipedia lead image turns out to be a non-free logo, which
    is the common case for fashion houses. Commons hosts only free content, so
    whatever is found here can be rehosted with credit; the file title still
    has to name the subject and read like a photo of a place or a product.
    """
    data = get_json(COMMONS_API + "?" + urllib.parse.urlencode({
        "action": "query", "generator": "search",
        "gsrsearch": "filetype:bitmap " + name,
        "gsrlimit": "12", "gsrnamespace": "6",
        "prop": "imageinfo", "iiprop": "url|extmetadata",
        "iiurlwidth": "1024", "format": "json",
    }))

    if not data:
        return None

    for page in data.get("query", {}).get("pages", {}).values():
        title = (page.get("title") or "").replace("File:", "")

        # Strict containment only: the file title must name the subject.
        # The reverse direction that title_matches also allows would let a
        # file called "Parfum.jpg" satisfy "Y Eau de Parfum".
        wanted = ascii_key(name)

        if not wanted or wanted not in ascii_key(title):
            continue

        if not any(hint in title.lower() for hint in COMMONS_HINTS):
            continue

        info = (page.get("imageinfo") or [{}])[0]
        meta = info.get("extmetadata", {})
        licence = meta.get("LicenseShortName", {}).get("value", "")
        url = info.get("thumburl") or info.get("url") or ""

        if not licence or not url:
            continue

        author = re.sub("<[^>]+>", "", meta.get("Artist", {}).get("value", "")).strip()

        return {
            "article": title,
            "description": "Wikimedia Commons",
            "credit": re.sub(r"\s+", " ", author),
            "license": licence,
            "source": "https://commons.wikimedia.org/wiki/"
                      + urllib.parse.quote(page.get("title", "").replace(" ", "_")),
            "url": url,
        }

    return None


def download(url, destination):
    try:
        with urllib.request.urlopen(urllib.request.Request(url, headers=AGENT), timeout=60) as r:
            payload = r.read()
    except Exception:
        return False

    if len(payload) < 5000:
        return False

    with io.open(destination, "wb") as handle:
        handle.write(payload)

    return True


def main():
    parser = argparse.ArgumentParser()
    parser.add_argument("--kind", choices=sorted(KINDS), required=True)
    parser.add_argument("--limit", type=int, default=0)
    parser.add_argument("--dry-run", action="store_true")
    args = parser.parse_args()

    config = KINDS[args.kind]

    with io.open(config["input"], encoding="utf-8") as handle:
        rows = [line.rstrip("\n").split("\t") for line in handle if line.strip()]

    if args.limit:
        rows = rows[:args.limit]

    os.makedirs(config["dir"], exist_ok=True)
    manifest = {}
    found = 0

    for index, row in enumerate(rows, start=1):
        slug, name = row[0], row[1]

        # Perfume rows carry the fragrance name on its own in a third column.
        # Matching on the combined "Brand Fragrance" string would let the
        # brand's own article through — "Dior" satisfies "Dior Homme Intense".
        match_name = strip_concentration(row[2] if len(row) > 2 else name)
        label = "{:3d}/{:3d}  {:28s}".format(index, len(rows), name[:28])

        found_via = "wikipedia"
        picked = None

        page = find_article(name, config["terms"], match_name, config.get("strict", False))

        if page:
            filename = page["thumbnail"]["source"].split("/")[-1].split("?")[0]
            filename = re.sub(r"^\d+px-", "", urllib.parse.unquote(filename))
            credit, licence, source, url = commons_file(filename)

            if url and licence:
                picked = {
                    "article": page.get("title", ""),
                    "description": page.get("description", ""),
                    "credit": credit,
                    "license": licence,
                    "source": source,
                    "url": url,
                }

        # The lead image was missing or non-free (usually a logo), so look for
        # a freely-licensed photograph on Commons instead.
        if picked is None and config.get("commons_fallback"):
            picked = commons_fallback(match_name)
            found_via = "commons"

        if picked is None:
            print("  " + label + " -- tidak ada foto berlisensi bebas", flush=True)
            continue

        target = os.path.join(config["dir"], slug + ".jpg")

        if args.dry_run or download(picked["url"], target):
            found += 1
            manifest[slug] = {
                "image": "images/{}s/{}.jpg".format(args.kind, slug),
                "article": picked["article"],
                "description": picked["description"],
                "credit": picked["credit"][:180],
                "license": picked["license"][:60],
                "source": picked["source"],
                "via": found_via,
            }
            print("  {} OK  {:28s} | {}".format(
                label, picked["article"][:28], picked["license"][:22]), flush=True)
        else:
            print("  " + label + " -- gagal diunduh", flush=True)

        time.sleep(0.25)

    if not args.dry_run:
        with io.open(config["manifest"], "w", encoding="utf-8") as handle:
            handle.write(json.dumps(manifest, indent=2, ensure_ascii=False))

    print("\n{}: dapat foto {} dari {}".format(args.kind, found, len(rows)), flush=True)


if __name__ == "__main__":
    main()

"""
Fetches freely-licensed photographs for fragrance notes.

Images come from Wikipedia's lead image (which is curated per concept and
hosted on Wikimedia Commons), not from a raw Commons file search. A plain
Commons search was tried first and was badly unreliable — "Apple" returned a
photo of Apple Inc.'s headquarters, "Amber" a person at Eurovision, and
"Amberwood" a forest in England — because file titles collide with homonyms.

Two checks keep the wrong subject out:

  1. An article whose title matches the note name exactly is preferred, so
     "Apple" resolves to the fruit rather than to a related aroma chemical.
  2. The article's own short description must mention a plant, material or
     compound. Anything unconfirmed is skipped and keeps its placeholder.

Every accepted image is recorded with its author and licence for attribution.

Usage:
    python scripts/fetch_note_photos.py
    python scripts/fetch_note_photos.py --limit 12
    python scripts/fetch_note_photos.py --dry-run
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

OUT_DIR = os.path.join("public", "images", "notes")
MANIFEST = os.path.join("storage", "app", "note-photo-manifest.json")

# The subject has to read like a material, not a company, place or person.
SUBJECT_WORDS = (
    "plant", "tree", "flower", "fruit", "resin", "spice", "wood", "shrub",
    "herb", "species", "genus", "oil", "chemical", "compound", "aroma",
    "perfume", "grass", "seed", "citrus", "fragrance", "organic", "molecule",
    "extract", "gum", "bark", "root", "berry", "leaf", "blossom", "nut",
    "essential", "balsam", "secretion", "mineral", "crystal", "liquid",
)

# Tried in order when the note name has no article of its own.
FALLBACK_TERMS = (" plant", " (plant)", " essential oil", " (perfumery)", "")


def get_json(url, attempts=3):
    """
    Fetches JSON, retrying on transient failures.

    Without the retry a single dropped request made the exact-title lookup
    look like a miss, and the guided search then settled on a worse article --
    "Ambergris" came back once as "Aphrodisiac" purely because of a timeout.
    """
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


WORD_RE = re.compile('[a-z]+')


def is_material(page):
    """
    Whether the article describes a physical material rather than a homonym.

    Each word in the summary is tested against the subject list from its
    start, so 'woody' counts but 'Lacewood' does not. A plain substring
    test let Amberwood through as 'Lacewood Productions', a Canadian
    animation studio, because 'wood' sits inside the company name.
    """
    blob = (page.get('description') or '') + ' ' + (page.get('extract') or '')[:400]
    words = WORD_RE.findall(blob.lower())

    return any(word.startswith(SUBJECT_WORDS) for word in words)


def ascii_key(text):
    """
    Lowercase alphanumerics with accents folded to plain letters.

    Without the folding "Hermes" and "Hermes" with an accent produce different
    keys, so a correct Commons photograph was rejected as a mismatch.
    """
    folded = unicodedata.normalize("NFKD", text or "")
    folded = folded.encode("ascii", "ignore").decode("ascii")

    return re.sub("[^a-z0-9]", "", folded.lower())


def title_matches(page, name):
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

    return wanted in title or title in wanted


def describes(page, name):
    """
    Whether a search result really covers this note.

    Botanical articles are usually filed under a scientific name -- ambrette
    lives at "Abelmoschus moschatus" -- so requiring the title to match would
    throw away correct results. Naming the note in the opening lines is the
    other acceptable proof; an article that mentions it only in passing, the
    way "Aphrodisiac" mentions ambergris further down, does not qualify.
    """
    if title_matches(page, name):
        return True

    return ascii_key(name) in ascii_key((page.get("extract") or "")[:220])


def usable(page):
    return bool(page) and page.get("type") == "standard" and "thumbnail" in page and is_material(page)


def search_titles(term, limit=3):
    url = WIKI_API + "?" + urllib.parse.urlencode({
        "action": "query", "list": "search", "srsearch": term,
        "srlimit": str(limit), "format": "json",
    })
    data = get_json(url)

    if not data:
        return []

    return [hit["title"] for hit in data.get("query", {}).get("search", [])]


def find_article(name):
    """An exact-title article wins; otherwise fall back to a guided search."""
    exact = summary(name)

    if usable(exact) and title_matches(exact, name):
        return exact

    for suffix in FALLBACK_TERMS:
        for title in search_titles(name + suffix):
            page = summary(title)

            if usable(page) and describes(page, name):
                return page

        time.sleep(0.3)

    return None


def commons_file(filename):
    """
    Author, licence and a full-width URL for the Commons file behind a
    Wikipedia thumbnail.

    The download URL has to come from the API: Wikimedia only serves a fixed
    set of thumbnail widths, so rewriting the width in a thumbnail URL by hand
    is rejected with HTTP 400.
    """
    url = COMMONS_API + "?" + urllib.parse.urlencode({
        "action": "query", "titles": "File:" + filename,
        "prop": "imageinfo", "iiprop": "url|extmetadata",
        "iiurlwidth": "1024", "format": "json",
    })
    data = get_json(url)

    if not data:
        return "", "", "", ""

    for page in data.get("query", {}).get("pages", {}).values():
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
    parser.add_argument("--limit", type=int, default=0)
    parser.add_argument("--dry-run", action="store_true")
    parser.add_argument("--notes", default="storage/app/notes-needing-photos.tsv")
    args = parser.parse_args()

    with io.open(args.notes, encoding="utf-8") as handle:
        rows = [line.rstrip("\n").split("\t") for line in handle if line.strip()]

    if args.limit:
        rows = rows[:args.limit]

    os.makedirs(OUT_DIR, exist_ok=True)
    manifest = {}
    found = 0

    for index, (slug, name) in enumerate(rows, start=1):
        page = find_article(name)

        if not page:
            print("  {:3d}/{:3d}  {:24s} -- tidak ada yang cocok".format(index, len(rows), name[:24]), flush=True)
            continue

        # The REST API appends tracking parameters to thumbnail URLs, so the
        # query string has to go before the Commons filename can be looked up.
        filename = page["thumbnail"]["source"].split("/")[-1].split("?")[0]
        filename = re.sub(r"^\d+px-", "", urllib.parse.unquote(filename))
        credit, licence, source, download_url = commons_file(filename)

        # Only Commons files are used: images hosted locally on Wikipedia are
        # non-free "fair use" uploads (logos and the like) that cannot be
        # rehosted here, and without a licence there is nothing to credit.
        if not download_url or not licence:
            print("  {:3d}/{:3d}  {:24s} -- bukan lisensi bebas, dilewati".format(
                index, len(rows), name[:24]), flush=True)
            continue

        target = os.path.join(OUT_DIR, slug + ".jpg")
        ok = args.dry_run or download(download_url, target)

        if ok:
            found += 1
            manifest[slug] = {
                "image": "images/notes/{}.jpg".format(slug),
                "article": page.get("title", ""),
                "description": page.get("description", ""),
                "credit": credit[:180],
                "license": licence[:60],
                "source": source,
            }
            print("  {:3d}/{:3d}  {:24s} OK  {:30s} | {}".format(
                index, len(rows), name[:24], page.get("title", "")[:30],
                (page.get("description") or "")[:36]), flush=True)
        else:
            print("  {:3d}/{:3d}  {:24s} -- gagal diunduh".format(index, len(rows), name[:24]), flush=True)

        time.sleep(0.25)

    if not args.dry_run:
        os.makedirs(os.path.dirname(MANIFEST), exist_ok=True)
        with io.open(MANIFEST, "w", encoding="utf-8") as handle:
            handle.write(json.dumps(manifest, indent=2, ensure_ascii=False))

    print("\nDapat foto: {} dari {} note".format(found, len(rows)), flush=True)


if __name__ == "__main__":
    main()

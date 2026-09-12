"""
Second pass over Wikimedia Commons for photographs the first run could not
place, plus a distinct secondary image for every note.

Three modes:

  note-about   a second, different photograph for each note, used by the About
               section so it no longer repeats the hero image
  note-fill    a looser attempt at the notes that came back empty
  brand-fill   a looser attempt at the houses whose Wikipedia lead image was a
               non-free logo

Commons is searched directly here rather than going through Wikipedia, because
what is wanted is any suitable free photograph, not the one article-worthy
image. Everything it hosts is freely licensed, and candidates are ranked so
public domain and CC0 win over CC BY, which in turn wins over CC BY-SA.

Every accepted file still has to name its subject in the title, and everything
lands on the review contact sheet for a human to check.

Usage:
    python scripts/fetch_extra_photos.py --mode note-about
    python scripts/fetch_extra_photos.py --mode note-fill
    python scripts/fetch_extra_photos.py --mode brand-fill
"""

import argparse
import io
import sys
import json
import os
import re
import time
import unicodedata
import urllib.parse
import urllib.request

# Windows consoles default to cp1252, which cannot print accented Commons
# titles; the first run died on "Hermes Jardin sur le Nil".
if hasattr(sys.stdout, "reconfigure"):
    sys.stdout.reconfigure(encoding="utf-8", errors="replace")

AGENT = {"User-Agent": "RalphDeVincaPerfumary/1.0 (fragrance encyclopedia; local development)"}
COMMONS_API = "https://commons.wikimedia.org/w/api.php"

MODES = {
    "note-about": {
        "input": "storage/app/notes-for-about.tsv",
        "manifest": "storage/app/note-about-manifest.json",
        "dir": os.path.join("public", "images", "notes"),
        "suffix": "-about",
    },
    "note-fill": {
        "input": "storage/app/notes-still-empty.tsv",
        "manifest": "storage/app/note-fill-manifest.json",
        "dir": os.path.join("public", "images", "notes"),
        "suffix": "",
    },
    "brand-fill": {
        "input": "storage/app/brands-still-empty.tsv",
        "manifest": "storage/app/brand-fill-manifest.json",
        "dir": os.path.join("public", "images", "brands"),
        "suffix": "",
    },
}

# Titles containing these are almost never a usable photograph of the subject.
REJECT = (
    "coat of arms", "flag of", "map of", "location map", "signature",
    "logo", "wordmark", "icon", "diagram", "chart", "graph", "seal of",
    "stamp", "banknote", "postcard", "album", "single cover", "book cover",
    "president", "minister", "portrait of", "mp ", "senator", "governor",
    "wedding", "funeral", "protest", "election", "parliament",
)

# Raw materials share their names with streets and suburbs, so "Amberwood"
# found an office building. Places are excluded when looking for an ingredient
# but kept for houses, where a shopfront on a named street is exactly right.
REJECT_PLACES = (
    "office building", "street", "road", "avenue", "railway", "station",
    "bridge", "school", "hospital", "church", "airport", "hotel", "suburb",
    "apartment", "housing", "shopping centre", "shopping center", "car park",
)


def ascii_key(text):
    folded = unicodedata.normalize("NFKD", text or "").encode("ascii", "ignore").decode("ascii")

    return re.sub("[^a-z0-9]", "", folded.lower())


def get_json(url, attempts=3):
    for attempt in range(attempts):
        try:
            with urllib.request.urlopen(urllib.request.Request(url, headers=AGENT), timeout=30) as r:
                return json.load(r)
        except Exception:
            if attempt + 1 < attempts:
                time.sleep(1.5 * (attempt + 1))

    return None


def licence_rank(licence):
    """Public domain first, then permissive, then share-alike."""
    value = (licence or "").lower()

    if "public domain" in value or value.startswith("cc0") or "pd-" in value:
        return 0

    if value.startswith("cc by") and "sa" not in value:
        return 1

    return 2


def candidates(term, limit=20, extra_reject=()):
    """Freely-licensed Commons files matching a search term, best licence first."""
    data = get_json(COMMONS_API + "?" + urllib.parse.urlencode({
        "action": "query", "generator": "search",
        "gsrsearch": "filetype:bitmap " + term,
        "gsrlimit": str(limit), "gsrnamespace": "6",
        "prop": "imageinfo", "iiprop": "url|extmetadata",
        "iiurlwidth": "1024", "format": "json",
    }))

    if not data:
        return []

    found = []

    for page in data.get("query", {}).get("pages", {}).values():
        title = (page.get("title") or "").replace("File:", "")
        info = (page.get("imageinfo") or [{}])[0]
        meta = info.get("extmetadata", {})
        licence = meta.get("LicenseShortName", {}).get("value", "")
        url = info.get("thumburl") or info.get("url") or ""

        if not licence or not url:
            continue

        if any(word in title.lower() for word in REJECT + tuple(extra_reject)):
            continue

        author = re.sub("<[^>]+>", "", meta.get("Artist", {}).get("value", "")).strip()

        found.append({
            "file": title,
            "url": url,
            "license": licence,
            "credit": re.sub(r"\s+", " ", author),
            "source": "https://commons.wikimedia.org/wiki/"
                      + urllib.parse.quote(page.get("title", "").replace(" ", "_")),
        })

    found.sort(key=lambda item: licence_rank(item["license"]))

    return found


def pick(name, exclude_files, terms, extra_reject=()):
    """First candidate that names the subject and is not already in use."""
    wanted = ascii_key(name)
    excluded = {ascii_key(f) for f in exclude_files if f}

    for term in terms:
        for item in candidates(term, extra_reject=extra_reject):
            if wanted and wanted not in ascii_key(item["file"]):
                continue

            if ascii_key(item["file"]) in excluded:
                continue

            return item

        time.sleep(0.3)

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
    parser.add_argument("--mode", choices=sorted(MODES), required=True)
    parser.add_argument("--limit", type=int, default=0)
    args = parser.parse_args()

    config = MODES[args.mode]

    with io.open(config["input"], encoding="utf-8") as handle:
        rows = [line.rstrip("\n").split("\t") for line in handle if line.strip()]

    if args.limit:
        rows = rows[:args.limit]

    os.makedirs(config["dir"], exist_ok=True)
    manifest = {}
    found = 0

    for index, row in enumerate(rows, start=1):
        slug = row[0]
        name = row[1]
        already = row[2] if len(row) > 2 else ""

        label = "{:3d}/{:3d}  {:24s}".format(index, len(rows), name[:24])

        terms = [name, name + " plant", name + " flower", name + " perfume"]

        if args.mode == "brand-fill":
            terms = [name + " perfume", name + " store", name + " boutique", name]

        # Only ingredient searches need the place-name filter.
        item = pick(name, [already], terms,
                    () if args.mode == "brand-fill" else REJECT_PLACES)

        if not item:
            print("  " + label + " -- tidak ada yang cocok", flush=True)
            continue

        target = os.path.join(config["dir"], slug + config["suffix"] + ".jpg")

        if download(item["url"], target):
            found += 1
            manifest[slug] = {
                "image": "images/{}/{}{}.jpg".format(
                    os.path.basename(config["dir"]), slug, config["suffix"]),
                "article": item["file"],
                "description": "Wikimedia Commons",
                "credit": item["credit"][:180],
                "license": item["license"][:60],
                "source": item["source"],
            }
            print("  {} OK  {:30s} | {}".format(
                label, item["file"][:30], item["license"][:20]), flush=True)
        else:
            print("  " + label + " -- gagal diunduh", flush=True)

        time.sleep(0.25)

    with io.open(config["manifest"], "w", encoding="utf-8") as handle:
        handle.write(json.dumps(manifest, indent=2, ensure_ascii=False))

    print("\n{}: dapat {} dari {}".format(args.mode, found, len(rows)), flush=True)


if __name__ == "__main__":
    main()

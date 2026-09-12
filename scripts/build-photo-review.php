<?php

/**
 * Builds public/note-photo-review.html — a contact sheet of every photo the
 * fetchers accepted, grouped by kind, next to the name and the Wikipedia
 * article it came from.
 *
 * The fetchers can confirm that an article is about the right subject, but
 * they cannot see the picture, so this page exists for a human to spot the
 * ones that are simply wrong before they go live.
 *
 * Usage:  php scripts/build-photo-review.php
 */

$root = dirname(__DIR__);

$groups = [
    'Note' => ['note-photo-manifest.json', 'note-fill-manifest.json'],
    'Note - About' => ['note-about-manifest.json'],
    'Brand' => ['brand-photo-manifest.json', 'brand-fill-manifest.json'],
    'Parfum' => ['perfume-photo-manifest.json'],
];

$sections = '';
$total = 0;

foreach ($groups as $label => $files) {
    $manifest = [];

    foreach ($files as $file) {
        $path = $root.'/storage/app/'.$file;

        if (file_exists($path)) {
            $manifest += json_decode(file_get_contents($path), true) ?: [];
        }
    }

    ksort($manifest);

    $cards = '';
    $shown = 0;

    foreach ($manifest as $slug => $entry) {
        if (! file_exists($root.'/public/'.($entry['image'] ?? ''))) {
            continue;
        }

        $shown++;
        $total++;

        $name = htmlspecialchars(str_replace('-', ' ', $slug), ENT_QUOTES);
        $image = htmlspecialchars($entry['image'], ENT_QUOTES);
        $article = htmlspecialchars($entry['article'] ?? '', ENT_QUOTES);
        $description = htmlspecialchars($entry['description'] ?? '', ENT_QUOTES);
        $licence = htmlspecialchars($entry['license'] ?? '', ENT_QUOTES);

        $cards .= <<<CARD
        <figure class="card">
            <img src="/{$image}" alt="{$name}" loading="lazy">
            <figcaption>
                <strong>{$name}</strong>
                <span class="article">{$article}</span>
                <span class="desc">{$description}</span>
                <span class="lic">{$licence}</span>
            </figcaption>
        </figure>

CARD;
    }

    if ($shown === 0) {
        continue;
    }

    $sections .= <<<SECTION
    <h2>{$label} &mdash; {$shown} foto</h2>
    <div class="grid">
{$cards}    </div>

SECTION;
}

if ($total === 0) {
    exit("Belum ada foto untuk ditinjau. Jalankan skrip fetch dulu.\n");
}

$html = <<<HTML
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Tinjau Foto</title>
<style>
    body { margin:0; padding:32px; background:#faf8f5; color:#292524;
           font:14px/1.6 system-ui, -apple-system, "Segoe UI", sans-serif; }
    h1 { font-size:24px; margin:0 0 6px; }
    h2 { font-size:16px; margin:36px 0 14px; padding-bottom:8px;
         border-bottom:1px solid #e7e5e4; color:#B08D57;
         text-transform:uppercase; letter-spacing:.18em; }
    p.lead { margin:0; color:#78716c; max-width:62ch; }
    .grid { display:grid; gap:18px;
            grid-template-columns:repeat(auto-fill, minmax(190px, 1fr)); }
    .card { margin:0; background:#fff; border:1px solid #e7e5e4;
            border-radius:14px; overflow:hidden; }
    .card img { width:100%; aspect-ratio:1/1; object-fit:cover; display:block;
                background:#f5f5f4; }
    figcaption { padding:10px 12px 12px; display:flex; flex-direction:column; gap:2px; }
    strong { text-transform:capitalize; font-size:14px; }
    .article { font-size:12px; color:#B08D57; }
    .desc { font-size:11px; color:#78716c; }
    .lic { font-size:10px; color:#a8a29e; margin-top:3px; }
</style>
</head>
<body>
<h1>Tinjau Foto &mdash; {$total} total</h1>
<p class="lead">
    Scroll dan cari yang gambarnya jelas tidak nyambung dengan namanya.
    Sebutkan nama yang meleset, nanti fotonya dihapus dan kembali ke
    placeholder. Judul artikel Wikipedia sumbernya ditulis di bawah tiap foto.
</p>
{$sections}
</body>
</html>
HTML;

file_put_contents($root.'/public/note-photo-review.html', $html);

echo "Halaman tinjauan dibuat: {$total} foto\n";
echo "Buka: http://ralphdevincaperfumary.test/note-photo-review.html\n";

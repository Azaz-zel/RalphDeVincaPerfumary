<?php

/**
 * Compresses images in public/images in place.
 *
 * Originals are backed up to storage/app/backups/images-original first
 * (done separately), so this can be re-run or reverted safely.
 *
 * Two passes per image:
 *   1. Downscale if wider than MAX_WIDTH — the site never renders an image
 *      wider than ~1400px, so anything larger is wasted bytes.
 *   2. Re-encode: JPEGs at QUALITY, PNGs at max PNG compression.
 *
 * File extensions are never changed, so no view references break.
 *
 * Usage:  php scripts/compress-images.php [--dry-run]
 */

const MAX_WIDTH = 1400;
const QUALITY = 82;

$dryRun = in_array('--dry-run', $argv, true);
$root = dirname(__DIR__).'/public/images';

if (! is_dir($root)) {
    exit("Folder not found: {$root}\n");
}

$files = [];
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root));

foreach ($iterator as $file) {
    if (! $file->isFile()) {
        continue;
    }

    if (preg_match('/\.(jpe?g|png)$/i', $file->getFilename())) {
        $files[] = $file->getPathname();
    }
}

sort($files);

$totalBefore = 0;
$totalAfter = 0;

foreach ($files as $path) {
    $sizeBefore = filesize($path);
    $totalBefore += $sizeBefore;

    $info = @getimagesize($path);

    if (! $info) {
        $totalAfter += $sizeBefore;
        echo "  SKIP (unreadable): ".basename($path)."\n";
        continue;
    }

    [$width, $height, $type] = $info;

    // Transparency is read from the PNG colour-type byte in the IHDR chunk
    // (4 = greyscale+alpha, 6 = RGB+alpha) so alpha survives re-encoding.
    $hasAlpha = $type === IMAGETYPE_PNG
        && in_array(ord(file_get_contents($path, false, null, 25, 1)), [4, 6], true);

    $source = match ($type) {
        IMAGETYPE_JPEG => @imagecreatefromjpeg($path),
        IMAGETYPE_PNG => @imagecreatefrompng($path),
        default => null,
    };

    if (! $source) {
        $totalAfter += $sizeBefore;
        echo "  SKIP (decode failed): ".basename($path)."\n";
        continue;
    }

    // Downscale when wider than needed.
    if ($width > MAX_WIDTH) {
        $newWidth = MAX_WIDTH;
        $newHeight = (int) round($height * (MAX_WIDTH / $width));

        $resized = imagecreatetruecolor($newWidth, $newHeight);

        if ($hasAlpha) {
            imagealphablending($resized, false);
            imagesavealpha($resized, true);
        }

        imagecopyresampled($resized, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        imagedestroy($source);
        $source = $resized;
    }

    $targetPath = $path;

    // Write to a temp file first: re-encoding an already well-compressed
    // image can come out *larger*, in which case the original is kept.
    $tempPath = $path.'.tmp';

    if ($type === IMAGETYPE_PNG) {
        // Keep PNGs as PNG so filenames referenced in views stay valid.
        if ($hasAlpha) {
            imagealphablending($source, false);
            imagesavealpha($source, true);
        }

        $ok = imagepng($source, $tempPath, 9);
    } else {
        // Flatten onto white in case the source carried an unused alpha channel.
        $flat = imagecreatetruecolor(imagesx($source), imagesy($source));
        imagefill($flat, 0, 0, imagecolorallocate($flat, 255, 255, 255));
        imagecopy($flat, $source, 0, 0, 0, 0, imagesx($source), imagesy($source));
        imagedestroy($source);
        $source = $flat;

        $ok = imagejpeg($source, $tempPath, QUALITY);
    }

    imagedestroy($source);

    if (! $ok) {
        @unlink($tempPath);
        $totalAfter += $sizeBefore;
        echo "  FAIL: ".basename($path)."\n";
        continue;
    }

    clearstatcache(true, $tempPath);
    $candidate = filesize($tempPath);

    if ($candidate >= $sizeBefore || $dryRun) {
        // No saving to be had — leave the original untouched.
        @unlink($tempPath);
        $totalAfter += $sizeBefore;
        continue;
    }

    rename($tempPath, $targetPath);

    clearstatcache(true, $targetPath);
    $sizeAfter = filesize($targetPath);
    $totalAfter += $sizeAfter;

    if ($sizeBefore > 200 * 1024) {
        printf(
            "  %-46s %7.0f KB -> %6.0f KB  (-%d%%)\n",
            substr(str_replace($root.DIRECTORY_SEPARATOR, '', $targetPath), 0, 46),
            $sizeBefore / 1024,
            $sizeAfter / 1024,
            100 - round($sizeAfter / $sizeBefore * 100)
        );
    }
}

printf(
    "\nFiles: %d\nTotal: %.1f MB -> %.1f MB  (-%d%%)\n",
    count($files),
    $totalBefore / 1048576,
    $totalAfter / 1048576,
    $totalBefore > 0 ? 100 - round($totalAfter / $totalBefore * 100) : 0
);

<?php
require_once('jSignature_Tools_Base30.php');

function base30_to_image_and_display($base30_string)
{
    $data = str_replace('image/jsignature;base30,', '', $base30_string);
    $converter = new jSignature_Tools_Base30();
    $raw = $converter->Base64ToNative($data);

    // Calculate dimensions
    $width = 0;
    $height = 0;
    foreach ($raw as $line) {
        if (max($line['x']) > $width) $width = max($line['x']);
        if (max($line['y']) > $height) $height = max($line['y']);
    }

    // Create an image
    $im = imagecreatetruecolor($width + 20, $height + 20);

    // Save transparency for PNG
    imagesavealpha($im, true);
    // Fill background with transparency
    $trans_colour = imagecolorallocatealpha($im, 255, 255, 255, 127);
    imagefill($im, 0, 0, $trans_colour);
    // Set pen thickness
    imagesetthickness($im, 2);
    // Set pen color to black
    $black = imagecolorallocate($im, 0, 0, 0);

    // Loop through array pairs from each signature word
    for ($i = 0; $i < count($raw); $i++) {
        // Loop through each pair in a word
        for ($j = 0; $j < count($raw[$i]['x']); $j++) {
            // Make sure we are not on the last coordinate in the array
            if (!isset($raw[$i]['x'][$j])) break;
            if (!isset($raw[$i]['x'][$j + 1]))
                // Draw the dot for the coordinate
                imagesetpixel($im, $raw[$i]['x'][$j], $raw[$i]['y'][$j], $black);
            else
                // Draw the line for the coordinate pair
                imageline($im, $raw[$i]['x'][$j], $raw[$i]['y'][$j], $raw[$i]['x'][$j + 1], $raw[$i]['y'][$j + 1], $black);
        }
    }

    // Output the image to browser
    header('Content-Type: image/png');
    imagepng($im);
    imagedestroy($im);
}

// Your base30 string here
$imgStr = 'image/jsignature;base30,g911000_2Ehgnqpl_jfdgjgb2Z5egfc70Y1af4_2EZ630Y7iomlpog3Z8rpli4_5BZ4k93Y16dihb1Z7bd8_2y3hga752379bjkh9_7uZ5043300000Y324c8aehe7_1Schr1w1vqeZm1y1C1Aob1Yal1u1znb_6Tr1yr_4AZqld';
base30_to_image_and_display($imgStr);

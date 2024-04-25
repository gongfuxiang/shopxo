<?php

require('vendor/autoload.php');

function getSaveFilename($value) {
    return preg_replace('/[^a-zA-Z0-9_ \-+]/s', '-', $value);
}

$generatorSVG = new Picqer\Barcode\BarcodeGeneratorSVG();
file_put_contents('tests/verified-files/081231723897-ean13.svg', $generatorSVG->getBarcode('081231723897', $generatorSVG::TYPE_EAN_13));
file_put_contents('tests/verified-files/081231723897-ean13-fractional-width.svg', $generatorSVG->getBarcode('081231723897', $generatorSVG::TYPE_EAN_13, 0.25, 25.75));

$generatorHTML = new Picqer\Barcode\BarcodeGeneratorHTML();
file_put_contents('tests/verified-files/081231723897-code128.html', $generatorHTML->getBarcode('081231723897', $generatorHTML::TYPE_CODE_128));

file_put_contents('tests/verified-files/12345678903-imb.html', $generatorHTML->getBarcode('12345678903', $generatorHTML::TYPE_IMB));

$generatorDynamicHTML = new Picqer\Barcode\BarcodeGeneratorDynamicHTML();
file_put_contents('tests/verified-files/081231723897-dynamic-code128.html', $generatorDynamicHTML->getBarcode('081231723897', $generatorDynamicHTML::TYPE_CODE_128));

file_put_contents('tests/verified-files/12345678903-dynamic-imb.html', $generatorDynamicHTML->getBarcode('12345678903', $generatorDynamicHTML::TYPE_IMB));

$generatorSVG = new Picqer\Barcode\BarcodeGeneratorSVG();
file_put_contents('tests/verified-files/0049000004632-ean13.svg', $generatorSVG->getBarcode('0049000004632', $generatorSVG::TYPE_EAN_13));


// New style of verified files
require(__DIR__ . '/tests/VerifiedBarcodeTest.php');
$verifiedFiles = VerifiedBarcodeTest::$supportedBarcodes;

$generatorSVG = new Picqer\Barcode\BarcodeGeneratorSVG();
foreach ($verifiedFiles as $verifiedFile) {
    foreach ($verifiedFile['barcodes'] as $barcode) {
        file_put_contents('tests/verified-files/' . getSaveFilename($verifiedFile['type'] . '-' . $barcode) . '.svg', $generatorSVG->getBarcode($barcode, $verifiedFile['type']));
    }
}

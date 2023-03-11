<?php

use PHPUnit\Framework\TestCase;
use Picqer\Barcode\BarcodeGenerator;

/*
 * Test all supported barcodes types, with as much different but supported input strings.
 * Verified files can be build with generate-verified-files.php file.
 * Only run that file if you added new types or new strings to test.
 *
 * We use SVG because that output is vector and should be the same on every host system.
 */

class VerifiedBarcodeTest extends TestCase
{
    public static $supportedBarcodes = [
        ['type' => BarcodeGenerator::TYPE_CODE_39, 'barcodes' => ['1234567890ABC']],
        ['type' => BarcodeGenerator::TYPE_CODE_39_CHECKSUM, 'barcodes' => ['1234567890ABC']],
        ['type' => BarcodeGenerator::TYPE_CODE_39E, 'barcodes' => ['1234567890abcABC']],
        ['type' => BarcodeGenerator::TYPE_CODE_39E_CHECKSUM, 'barcodes' => ['1234567890abcABC']],
        ['type' => BarcodeGenerator::TYPE_CODE_93, 'barcodes' => ['1234567890abcABC']],
        ['type' => BarcodeGenerator::TYPE_STANDARD_2_5, 'barcodes' => ['1234567890']],
        ['type' => BarcodeGenerator::TYPE_STANDARD_2_5_CHECKSUM, 'barcodes' => ['1234567890']],
        ['type' => BarcodeGenerator::TYPE_INTERLEAVED_2_5, 'barcodes' => ['1234567890']],
        ['type' => BarcodeGenerator::TYPE_INTERLEAVED_2_5_CHECKSUM, 'barcodes' => ['1234567890']],
        ['type' => BarcodeGenerator::TYPE_EAN_13, 'barcodes' => ['081231723897', '0049000004632', '004900000463']],
        ['type' => BarcodeGenerator::TYPE_CODE_128, 'barcodes' => ['081231723897', '1234567890abcABC-283*33']],
        ['type' => BarcodeGenerator::TYPE_CODE_128_A, 'barcodes' => ['1234567890']],
        ['type' => BarcodeGenerator::TYPE_CODE_128_B, 'barcodes' => ['081231723897', '1234567890abcABC-283*33']],
        ['type' => BarcodeGenerator::TYPE_EAN_2, 'barcodes' => ['22']],
        ['type' => BarcodeGenerator::TYPE_EAN_5, 'barcodes' => ['1234567890abcABC-283*33']],
        ['type' => BarcodeGenerator::TYPE_EAN_8, 'barcodes' => ['1234568']],
        ['type' => BarcodeGenerator::TYPE_UPC_A, 'barcodes' => ['123456789']],
        ['type' => BarcodeGenerator::TYPE_UPC_E, 'barcodes' => ['123456789']],
        ['type' => BarcodeGenerator::TYPE_MSI, 'barcodes' => ['123456789']],
        ['type' => BarcodeGenerator::TYPE_MSI_CHECKSUM, 'barcodes' => ['123456789']],
        ['type' => BarcodeGenerator::TYPE_POSTNET, 'barcodes' => ['123456789']],
        ['type' => BarcodeGenerator::TYPE_PLANET, 'barcodes' => ['123456789']],
        ['type' => BarcodeGenerator::TYPE_RMS4CC, 'barcodes' => ['123456789']],
        ['type' => BarcodeGenerator::TYPE_KIX, 'barcodes' => ['123456789']],
        ['type' => BarcodeGenerator::TYPE_IMB, 'barcodes' => ['123456789']],
        ['type' => BarcodeGenerator::TYPE_CODABAR, 'barcodes' => ['123456789']],
        ['type' => BarcodeGenerator::TYPE_CODE_11, 'barcodes' => ['123456789']],
        ['type' => BarcodeGenerator::TYPE_PHARMA_CODE, 'barcodes' => ['123456789']],
        ['type' => BarcodeGenerator::TYPE_PHARMA_CODE_TWO_TRACKS, 'barcodes' => ['123456789']],
    ];

    public function testAllSupportedBarcodeTypes()
    {
        $generator = new Picqer\Barcode\BarcodeGeneratorSVG();

        foreach ($this::$supportedBarcodes as $barcodeTestSet) {
            foreach ($barcodeTestSet['barcodes'] as $barcode) {
                $result = $generator->getBarcode($barcode, $barcodeTestSet['type']);

                $this->assertStringEqualsFile(
                    sprintf('tests/verified-files/%s.svg', $this->getSaveFilename($barcodeTestSet['type'] . '-' . $barcode)),
                    $result,
                    sprintf('%s x %s dynamic test failed', $barcodeTestSet['type'], $barcode)
                );
            }
        }
    }

    protected function getSaveFilename($value) {
        return preg_replace('/[^a-zA-Z0-9_ \-+]/s', '-', $value);
    }
}

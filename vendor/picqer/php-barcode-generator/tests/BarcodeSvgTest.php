<?php

use PHPUnit\Framework\TestCase;

class BarcodeSvgTest extends TestCase
{
    public function test_svg_barcode_generator_can_generate_ean_13_barcode()
    {
        $generator = new Picqer\Barcode\BarcodeGeneratorSVG();
        $generated = $generator->getBarcode('081231723897', $generator::TYPE_EAN_13);

        $this->assertStringEqualsFile('tests/verified-files/081231723897-ean13.svg', $generated);
    }

    public function test_svg_barcode_generator_can_generate_ean_13_barcode_with_fractional_width()
    {
        $generator = new Picqer\Barcode\BarcodeGeneratorSVG();
        $generated = $generator->getBarcode('081231723897', $generator::TYPE_EAN_13, 0.25, 25.75);

        $this->assertStringEqualsFile('tests/verified-files/081231723897-ean13-fractional-width.svg', $generated);
    }
}

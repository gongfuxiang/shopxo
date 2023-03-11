<?php

use PHPUnit\Framework\TestCase;

class BarcodeDynamicHtmlTest extends TestCase
{
    public function test_dynamic_html_barcode_generator_can_generate_code_128_barcode()
    {
        $generator = new Picqer\Barcode\BarcodeGeneratorDynamicHTML();
        $generated = $generator->getBarcode('081231723897', $generator::TYPE_CODE_128);

        $this->assertStringEqualsFile('tests/verified-files/081231723897-dynamic-code128.html', $generated);
    }

    public function test_dynamic_html_barcode_generator_can_generate_imb_barcode_to_test_heights()
    {
        $generator = new Picqer\Barcode\BarcodeGeneratorDynamicHTML();
        $generated = $generator->getBarcode('12345678903', $generator::TYPE_IMB);

        $this->assertStringEqualsFile('tests/verified-files/12345678903-dynamic-imb.html', $generated);
    }
}

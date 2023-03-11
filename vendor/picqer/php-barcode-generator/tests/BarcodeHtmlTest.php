<?php

use PHPUnit\Framework\TestCase;

class BarcodeHtmlTest extends TestCase
{
    public function test_html_barcode_generator_can_generate_code_128_barcode()
    {
        $generator = new Picqer\Barcode\BarcodeGeneratorHTML();
        $generated = $generator->getBarcode('081231723897', $generator::TYPE_CODE_128);

        $this->assertStringEqualsFile('tests/verified-files/081231723897-code128.html', $generated);
    }

    public function test_html_barcode_generator_can_generate_imb_barcode_to_test_heights()
    {
        $generator = new Picqer\Barcode\BarcodeGeneratorHTML();
        $generated = $generator->getBarcode('12345678903', $generator::TYPE_IMB);

        $this->assertStringEqualsFile('tests/verified-files/12345678903-imb.html', $generated);
    }
}

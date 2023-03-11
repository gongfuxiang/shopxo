<?php

use PHPUnit\Framework\TestCase;

class GeneratorTest extends TestCase
{
    public function test_throws_exception_if_empty_barcode_is_used_in_ean13()
    {
        $this->expectException(Picqer\Barcode\Exceptions\InvalidLengthException::class);

        $generator = new Picqer\Barcode\BarcodeGeneratorSVG();
        $generator->getBarcode('', $generator::TYPE_EAN_13);
    }

    public function test_throws_exception_if_empty_barcode_is_used_in_code128()
    {
        $this->expectException(Picqer\Barcode\Exceptions\InvalidLengthException::class);

        $generator = new Picqer\Barcode\BarcodeGeneratorSVG();
        $generator->getBarcode('', $generator::TYPE_CODE_128);
    }

    public function test_ean13_generator_throws_exception_if_invalid_chars_are_used()
    {
        $this->expectException(Picqer\Barcode\Exceptions\InvalidCharacterException::class);

        $generator = new Picqer\Barcode\BarcodeGeneratorSVG();
        $generator->getBarcode('A123', $generator::TYPE_EAN_13);
    }

    public function test_ean13_generator_accepting_13_chars()
    {
        $generator = new Picqer\Barcode\BarcodeGeneratorSVG();
        $generated = $generator->getBarcode('0049000004632', $generator::TYPE_EAN_13);

        $this->assertStringEqualsFile('tests/verified-files/0049000004632-ean13.svg', $generated);
    }

    public function test_ean13_generator_accepting_12_chars_and_generates_13th_check_digit()
    {
        $generator = new Picqer\Barcode\BarcodeGeneratorSVG();
        $generated = $generator->getBarcode('004900000463', $generator::TYPE_EAN_13);

        $this->assertStringEqualsFile('tests/verified-files/0049000004632-ean13.svg', $generated);
    }

    public function test_ean13_generator_accepting_11_chars_and_generates_13th_check_digit_and_adds_leading_zero()
    {
        $generator = new Picqer\Barcode\BarcodeGeneratorSVG();
        $generated = $generator->getBarcode('04900000463', $generator::TYPE_EAN_13);

        $this->assertStringEqualsFile('tests/verified-files/0049000004632-ean13.svg', $generated);
    }

    public function test_ean13_generator_throws_exception_when_wrong_check_digit_is_given()
    {
        $this->expectException(Picqer\Barcode\Exceptions\InvalidCheckDigitException::class);

        $generator = new Picqer\Barcode\BarcodeGeneratorSVG();
        $generator->getBarcode('0049000004633', $generator::TYPE_EAN_13);
    }

    public function test_generator_throws_unknown_type_exceptions()
    {
        $this->expectException(Picqer\Barcode\Exceptions\UnknownTypeException::class);

        $generator = new Picqer\Barcode\BarcodeGeneratorSVG();
        $generator->getBarcode('0049000004633', 'vladimir');
    }
}
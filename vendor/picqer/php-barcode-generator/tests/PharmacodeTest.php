<?php

use PHPUnit\Framework\TestCase;

class PharmacodeTest extends TestCase
{
    public function test_validation_triggerd_when_generating_zero_code()
    {
        $pharmacode = new Picqer\Barcode\Types\TypePharmacodeTwoCode();

        $this->expectException(Picqer\Barcode\Exceptions\InvalidLengthException::class);

        $pharmacode->getBarcodeData('0');
    }
}

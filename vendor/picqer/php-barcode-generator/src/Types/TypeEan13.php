<?php

namespace Picqer\Barcode\Types;

/*
 * EAN13 and UPC-A barcodes.
 * EAN13: European Article Numbering international retail product code
 * UPC-A: Universal product code seen on almost all retail products in the USA and Canada
 * UPC-E: Short version of UPC symbol
 *
 * @param $code (string) code to represent.
 * @param $len (string) barcode type: 6 = UPC-E, 8 = EAN8, 13 = EAN13, 12 = UPC-A
 */

class TypeEan13 extends TypeEanUpcBase
{
    protected $length = 13;
    protected $upca = false;
    protected $upce = false;
}

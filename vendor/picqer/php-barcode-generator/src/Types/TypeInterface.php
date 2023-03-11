<?php

namespace Picqer\Barcode\Types;

use Picqer\Barcode\Barcode;

interface TypeInterface
{
    public function getBarcodeData(string $code): Barcode;
}

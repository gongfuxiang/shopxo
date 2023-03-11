<?php

namespace Picqer\Barcode\Types;

/*
 * Interleaved 2 of 5 barcodes.
 * Compact numeric code, widely used in industry, air cargo
 * Contains digits (0 to 9) and encodes the data in the width of both bars and spaces.
 */

class TypeInterleaved25 extends TypeInterleaved25Checksum
{
    protected function getChecksum(string $code): string
    {
        return '';
    }
}

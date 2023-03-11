<?php

namespace Picqer\Barcode\Types;

/*
 * Standard 2 of 5 barcodes.
 * Used in airline ticket marking, photofinishing
 * Contains digits (0 to 9) and encodes the data only in the width of bars.
 */

class TypeStandard2of5Checksum extends TypeStandard2of5
{
    protected $checksum = true;
}

<?php

namespace Picqer\Barcode\Types;

/*
 * MSI.
 * Variation of Plessey code, with similar applications
 * Contains digits (0 to 9) and encodes the data only in the width of bars.
 *
 * @param $code (string) code to represent.
 * @param $checksum (boolean) if true add a checksum to the code (modulo 11)
 */

class TypeMsi extends TypeMsiChecksum
{
    protected $checksum = false;
}

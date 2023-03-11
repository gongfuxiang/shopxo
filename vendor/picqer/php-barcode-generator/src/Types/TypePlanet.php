<?php

namespace Picqer\Barcode\Types;

/*
 * PLANET barcodes.
 * Used by U.S. Postal Service for automated mail sorting
 *
 * @param $code (string) zip code to represent. Must be a string containing a zip code of the form DDDDD or
 *     DDDDD-DDDD.
 * @param $planet (boolean) if true print the PLANET barcode, otherwise print POSTNET
 */

class TypePlanet extends TypePostnet
{
    protected $barlen = [
        0 => [1, 1, 2, 2, 2],
        1 => [2, 2, 2, 1, 1],
        2 => [2, 2, 1, 2, 1],
        3 => [2, 2, 1, 1, 2],
        4 => [2, 1, 2, 2, 1],
        5 => [2, 1, 2, 1, 2],
        6 => [2, 1, 1, 2, 2],
        7 => [1, 2, 2, 2, 1],
        8 => [1, 2, 2, 1, 2],
        9 => [1, 2, 1, 2, 2]
    ];
}

<?php

namespace Picqer\Barcode\Types;

/*
 * C128 barcodes.
 * Very capable code, excellent density, high reliability; in very wide use world-wide
 *
 * @param $code (string) code to represent.
 * @param $type (string) barcode type: A, B, C or empty for automatic switch (AUTO mode)
 */

class TypeCode128C extends TypeCode128
{
    protected $type = 'C';
}

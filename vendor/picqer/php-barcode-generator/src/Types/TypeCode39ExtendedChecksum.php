<?php

namespace Picqer\Barcode\Types;

/*
 * CODE 39 - ANSI MH10.8M-1983 - USD-3 - 3 of 9.
 * General-purpose code in very wide use world-wide
 */

class TypeCode39ExtendedChecksum extends TypeCode39
{
    protected $extended = true;
    protected $checksum = true;
}

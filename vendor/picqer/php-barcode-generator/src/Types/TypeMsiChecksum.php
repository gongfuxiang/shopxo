<?php

namespace Picqer\Barcode\Types;

use Picqer\Barcode\Barcode;
use Picqer\Barcode\Exceptions\InvalidCharacterException;
use Picqer\Barcode\Helpers\BinarySequenceConverter;

/*
 * MSI.
 * Variation of Plessey code, with similar applications
 * Contains digits (0 to 9) and encodes the data only in the width of bars.
 *
 * @param $code (string) code to represent.
 * @param $checksum (boolean) if true add a checksum to the code (modulo 11)
 */

class TypeMsiChecksum implements TypeInterface
{
    protected $checksum = true;

    public function getBarcodeData(string $code): Barcode
    {
        $chr['0'] = '100100100100';
        $chr['1'] = '100100100110';
        $chr['2'] = '100100110100';
        $chr['3'] = '100100110110';
        $chr['4'] = '100110100100';
        $chr['5'] = '100110100110';
        $chr['6'] = '100110110100';
        $chr['7'] = '100110110110';
        $chr['8'] = '110100100100';
        $chr['9'] = '110100100110';
        $chr['A'] = '110100110100';
        $chr['B'] = '110100110110';
        $chr['C'] = '110110100100';
        $chr['D'] = '110110100110';
        $chr['E'] = '110110110100';
        $chr['F'] = '110110110110';
        if ($this->checksum) {
            // add checksum
            $clen = strlen($code);
            $p = 2;
            $check = 0;
            for ($i = ($clen - 1); $i >= 0; --$i) {
                $check += (hexdec($code[$i]) * $p);
                ++$p;
                if ($p > 7) {
                    $p = 2;
                }
            }
            $check %= 11;
            if ($check > 0) {
                $check = 11 - $check;
            }
            $code .= $check;
        }
        $seq = '110'; // left guard
        $clen = strlen($code);
        for ($i = 0; $i < $clen; ++$i) {
            $digit = $code[$i];
            if (! isset($chr[$digit])) {
                throw new InvalidCharacterException('Char ' . $digit . ' is unsupported');
            }
            $seq .= $chr[$digit];
        }
        $seq .= '1001'; // right guard

        return BinarySequenceConverter::convert($code, $seq);
    }
}

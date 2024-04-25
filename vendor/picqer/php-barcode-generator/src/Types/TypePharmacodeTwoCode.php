<?php

namespace Picqer\Barcode\Types;

/*
 * Pharmacode two-track
 * Contains digits (0 to 9)
 */

use Picqer\Barcode\Barcode;
use Picqer\Barcode\BarcodeBar;
use Picqer\Barcode\Exceptions\BarcodeException;
use Picqer\Barcode\Exceptions\InvalidCharacterException;
use Picqer\Barcode\Exceptions\InvalidLengthException;

class TypePharmacodeTwoCode implements TypeInterface
{
    public function getBarcodeData(string $code): Barcode
    {
        $code = intval($code);

        if ($code < 1) {
            throw new InvalidLengthException('Pharmacode 2 needs a number of 1 or larger');
        }

        $seq = '';

        do {
            switch ($code % 3) {
                case 0:
                    $seq .= '3';
                    $code = ($code - 3) / 3;
                    break;

                case 1:
                    $seq .= '1';
                    $code = ($code - 1) / 3;
                    break;

                case 2:
                    $seq .= '2';
                    $code = ($code - 2) / 3;
                    break;
            }
        } while ($code != 0);

        $seq = strrev($seq);

        $barcode = new Barcode($code);

        for ($i = 0; $i < strlen($seq); ++$i) {
            switch ($seq[$i]) {
                case '1':
                    $p = 1;
                    $h = 1;
                    break;

                case '2':
                    $p = 0;
                    $h = 1;
                    break;

                case '3':
                    $p = 0;
                    $h = 2;
                    break;

                default:
                    throw new InvalidCharacterException('Could not find bar for char.');
            }

            $barcode->addBar(new BarcodeBar(1, $h, 1, $p));
            if ($i < (strlen($seq) - 1)) {
                $barcode->addBar(new BarcodeBar(1, 2, 0, 0));
            }
        }

        return $barcode;
    }
}

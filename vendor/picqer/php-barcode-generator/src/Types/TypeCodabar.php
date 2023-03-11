<?php

namespace Picqer\Barcode\Types;

use Picqer\Barcode\Barcode;
use Picqer\Barcode\BarcodeBar;
use Picqer\Barcode\Exceptions\InvalidCharacterException;

/*
 * CODABAR barcodes.
 * Older code often used in library systems, sometimes in blood banks
 */

class TypeCodabar implements TypeInterface
{
    protected $conversionTable = [
        '0' => '11111221',
        '1' => '11112211',
        '2' => '11121121',
        '3' => '22111111',
        '4' => '11211211',
        '5' => '21111211',
        '6' => '12111121',
        '7' => '12112111',
        '8' => '12211111',
        '9' => '21121111',
        '-' => '11122111',
        '$' => '11221111',
        ':' => '21112121',
        '/' => '21211121',
        '.' => '21212111',
        '+' => '11222221',
        'A' => '11221211',
        'B' => '12121121',
        'C' => '11121221',
        'D' => '11122211'
    ];

    public function getBarcodeData(string $code): Barcode
    {
        $barcode = new Barcode($code);

        $code = 'A' . strtoupper($code) . 'A';

        for ($i = 0; $i < strlen($code); ++$i) {
            if (! isset($this->conversionTable[(string)$code[$i]])) {
                throw new InvalidCharacterException('Char ' . $code[$i] . ' is unsupported');
            }

            $seq = $this->conversionTable[(string)$code[$i]];
            for ($j = 0; $j < 8; ++$j) {
                if (($j % 2) == 0) {
                    $drawBar = true;
                } else {
                    $drawBar = false;
                }
                $barWidth = $seq[$j];
                $barcode->addBar(new BarcodeBar($barWidth, 1, $drawBar));
            }
        }

        return $barcode;
    }
}

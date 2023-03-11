<?php

namespace Picqer\Barcode\Types;

use Picqer\Barcode\Barcode;
use Picqer\Barcode\Exceptions\InvalidCheckDigitException;
use Picqer\Barcode\Helpers\BinarySequenceConverter;

/*
 * UPC-Based Extensions
 * 2-Digit Ext.: Used to indicate magazines and newspaper issue numbers
 * 5-Digit Ext.: Used to mark suggested retail price of books
 */

class TypeUpcExtension2 implements TypeInterface
{
    protected $length = 2;

    public function getBarcodeData(string $code): Barcode
    {
        $len = $this->length;

        //Padding
        $code = str_pad($code, $len, '0', STR_PAD_LEFT);

        // calculate check digit
        if ($len == 2) {
            $r = $code % 4;
        } elseif ($len == 5) {
            $r = (3 * ($code[0] + $code[2] + $code[4])) + (9 * ($code[1] + $code[3]));
            $r %= 10;
        } else {
            throw new InvalidCheckDigitException();
        }

        //Convert digits to bars
        $codes = [
            'A' => [ // left odd parity
                '0' => '0001101',
                '1' => '0011001',
                '2' => '0010011',
                '3' => '0111101',
                '4' => '0100011',
                '5' => '0110001',
                '6' => '0101111',
                '7' => '0111011',
                '8' => '0110111',
                '9' => '0001011'
            ],
            'B' => [ // left even parity
                '0' => '0100111',
                '1' => '0110011',
                '2' => '0011011',
                '3' => '0100001',
                '4' => '0011101',
                '5' => '0111001',
                '6' => '0000101',
                '7' => '0010001',
                '8' => '0001001',
                '9' => '0010111'
            ]
        ];

        $parities = [
            2 =>[
                '0' => ['A', 'A'],
                '1' => ['A', 'B'],
                '2' => ['B', 'A'],
                '3' => ['B', 'B']
            ],
            5 => [
                '0' => ['B', 'B', 'A', 'A', 'A'],
                '1' => ['B', 'A', 'B', 'A', 'A'],
                '2' => ['B', 'A', 'A', 'B', 'A'],
                '3' => ['B', 'A', 'A', 'A', 'B'],
                '4' => ['A', 'B', 'B', 'A', 'A'],
                '5' => ['A', 'A', 'B', 'B', 'A'],
                '6' => ['A', 'A', 'A', 'B', 'B'],
                '7' => ['A', 'B', 'A', 'B', 'A'],
                '8' => ['A', 'B', 'A', 'A', 'B'],
                '9' => ['A', 'A', 'B', 'A', 'B']
            ]
        ];

        $p = $parities[$len][$r];
        $seq = '1011'; // left guard bar
        $seq .= $codes[$p[0]][$code[0]];
        for ($i = 1; $i < $len; ++$i) {
            $seq .= '01'; // separator
            $seq .= $codes[$p[$i]][$code[$i]];
        }

        return BinarySequenceConverter::convert($code, $seq);
    }
}

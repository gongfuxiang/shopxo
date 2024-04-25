<?php

namespace Picqer\Barcode\Types;

use Picqer\Barcode\Barcode;
use Picqer\Barcode\Exceptions\InvalidCharacterException;
use Picqer\Barcode\Exceptions\InvalidCheckDigitException;
use Picqer\Barcode\Exceptions\InvalidLengthException;

/*
 * CODE 32 - italian pharmaceutical
 * General-purpose code in very wide use world-wide
 */
class TypeCode32 extends TypeCode39
{
    protected $conversionTable32 = [
        '0' => '0',
        '1' => '1',
        '2' => '2',
        '3' => '3',
        '4' => '4',
        '5' => '5',
        '6' => '6',
        '7' => '7',
        '8' => '8',
        '9' => '9',
        '10' => 'B',
        '11' => 'C',
        '12' => 'D',
        '13' => 'F',
        '14' => 'G',
        '15' => 'H',
        '16' => 'J',
        '17' => 'K',
        '18' => 'L',
        '19' => 'M',
        '20' => 'N',
        '21' => 'P',
        '22' => 'Q',
        '23' => 'R',
        '24' => 'S',
        '25' => 'T',
        '26' => 'U',
        '27' => 'V',
        '28' => 'W',
        '29' => 'X',
        '30' => 'Y',
        '31' => 'Z'
    ];

    public function getBarcodeData(string $code): Barcode
    {
        // Validate code 32.
        $stringLength = strlen($code);

        for ($i = 0;  $i < $stringLength; ++$i) {
            if (!is_numeric($code[$i])) {
                throw new InvalidCharacterException('Character "' . $code[$i] . '" is not supported.');
            }
        }

        // Prepare code 32.
        $code          = str_pad($code, 8, '0', STR_PAD_LEFT);
        $checksumDigit = $this->checksum_code32(substr($code, 0, 8));
        $stringLength  = max($stringLength, 8);

        if ($stringLength === 8) {
            $code .= $checksumDigit;
            ++$stringLength;
        }
        if ($stringLength !== 9) {
            throw new InvalidLengthException('Only a code consisting of no more than 9 numbers is supported.');
        }
        if ($code[8] !== $checksumDigit) {
            throw new InvalidCheckDigitException('Provided checksum digit is wrong for provided code.');
        }

        // Convert code 32 into code 39.
        $code39   = '';
        $codeElab = $code;

        for ($e = 5; $e >= 0; --$e) {
            $code39  .= $this->conversionTable32[intval($codeElab / pow(32, $e))];
            $codeElab = $codeElab % pow(32, $e);
        }

        // Return barcode data for code 39.
        return parent::getBarcodeData($code39);
    }


    /**
     * Calculate CODE 32 checksum (modulo 10).
     *
     * @param string $code code to represent.
     * @return string char checksum.
     * @protected
     */
    protected function checksum_code32(string $code): string
    {
        $s = 0;

        foreach (str_split($code) as $i => $c) {
            if (0 === $i % 2) {
                $s += (int)$c;
            } else {
                $c  = 2 * (int)$c;
                $s += (int)floor($c / 10) + ($c % 10);
            }
        }

        return (string)($s % 10);
    }
}

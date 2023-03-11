<?php

namespace Picqer\Barcode\Types;

use Picqer\Barcode\Barcode;

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
        $code39 = '';
        $codeElab = $code;
        
        for ($e = 5; $e >= 0; $e--) {
            $code39 .= $this->conversionTable32[intval($codeElab / pow(32,$e))];
            $codeElab = intval($codeElab % pow(32,$e));
	    }
        
        return parent::getBarcodeData($code39);
	}
}

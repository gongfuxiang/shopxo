<?php

namespace Picqer\Barcode\Helpers;

use Picqer\Barcode\Barcode;
use Picqer\Barcode\BarcodeBar;

/**
 * Convert binary barcode sequence string to Barcode representation.
 */
class BinarySequenceConverter
{
    public static function convert(string $code, string $sequence): Barcode
    {
        $barcode = new Barcode($code);

        $len = strlen($sequence);
        $barWidth = 0;
        for ($i = 0; $i < $len; ++$i) {
            $barWidth += 1;
            if (($i == ($len - 1)) || (($i < ($len - 1)) && ($sequence[$i] != $sequence[($i + 1)]))) {
                if ($sequence[$i] == '1') {
                    $drawBar = true;
                } else {
                    $drawBar = false;
                }

                $barcode->addBar(new BarcodeBar($barWidth, 1, $drawBar));
                $barWidth = 0;
            }
        }

        return $barcode;
    }
}

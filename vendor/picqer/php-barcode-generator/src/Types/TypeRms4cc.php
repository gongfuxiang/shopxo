<?php

namespace Picqer\Barcode\Types;

/*
 * RMS4CC - CBC - KIX
 * RMS4CC (Royal Mail 4-state Customer Code) - CBC (Customer Bar Code) - KIX (Klant index - Customer index)
 * RM4SCC is the name of the barcode symbology used by the Royal Mail for its Cleanmail service.
 * @param $kix (boolean) if true prints the KIX variation (doesn't use the start and end symbols, and the checksum)
 *     - in this case the house number must be sufficed with an X and placed at the end of the code.
 */

use Picqer\Barcode\Barcode;
use Picqer\Barcode\BarcodeBar;

class TypeRms4cc implements TypeInterface
{
    protected $kix = false;

    public function getBarcodeData(string $code): Barcode
    {
        // bar mode
        // 1 = pos 1, length 2
        // 2 = pos 1, length 3
        // 3 = pos 2, length 1
        // 4 = pos 2, length 2
        $barmode = [
            '0' => [3, 3, 2, 2],
            '1' => [3, 4, 1, 2],
            '2' => [3, 4, 2, 1],
            '3' => [4, 3, 1, 2],
            '4' => [4, 3, 2, 1],
            '5' => [4, 4, 1, 1],
            '6' => [3, 1, 4, 2],
            '7' => [3, 2, 3, 2],
            '8' => [3, 2, 4, 1],
            '9' => [4, 1, 3, 2],
            'A' => [4, 1, 4, 1],
            'B' => [4, 2, 3, 1],
            'C' => [3, 1, 2, 4],
            'D' => [3, 2, 1, 4],
            'E' => [3, 2, 2, 3],
            'F' => [4, 1, 1, 4],
            'G' => [4, 1, 2, 3],
            'H' => [4, 2, 1, 3],
            'I' => [1, 3, 4, 2],
            'J' => [1, 4, 3, 2],
            'K' => [1, 4, 4, 1],
            'L' => [2, 3, 3, 2],
            'M' => [2, 3, 4, 1],
            'N' => [2, 4, 3, 1],
            'O' => [1, 3, 2, 4],
            'P' => [1, 4, 1, 4],
            'Q' => [1, 4, 2, 3],
            'R' => [2, 3, 1, 4],
            'S' => [2, 3, 2, 3],
            'T' => [2, 4, 1, 3],
            'U' => [1, 1, 4, 4],
            'V' => [1, 2, 3, 4],
            'W' => [1, 2, 4, 3],
            'X' => [2, 1, 3, 4],
            'Y' => [2, 1, 4, 3],
            'Z' => [2, 2, 3, 3]
        ];

        $code = strtoupper($code);
        $len = strlen($code);

        $barcode = new Barcode($code);

        if (! $this->kix) {
            // table for checksum calculation (row,col)
            $checktable = [
                '0' => [1, 1],
                '1' => [1, 2],
                '2' => [1, 3],
                '3' => [1, 4],
                '4' => [1, 5],
                '5' => [1, 0],
                '6' => [2, 1],
                '7' => [2, 2],
                '8' => [2, 3],
                '9' => [2, 4],
                'A' => [2, 5],
                'B' => [2, 0],
                'C' => [3, 1],
                'D' => [3, 2],
                'E' => [3, 3],
                'F' => [3, 4],
                'G' => [3, 5],
                'H' => [3, 0],
                'I' => [4, 1],
                'J' => [4, 2],
                'K' => [4, 3],
                'L' => [4, 4],
                'M' => [4, 5],
                'N' => [4, 0],
                'O' => [5, 1],
                'P' => [5, 2],
                'Q' => [5, 3],
                'R' => [5, 4],
                'S' => [5, 5],
                'T' => [5, 0],
                'U' => [0, 1],
                'V' => [0, 2],
                'W' => [0, 3],
                'X' => [0, 4],
                'Y' => [0, 5],
                'Z' => [0, 0]
            ];

            $row = 0;
            $col = 0;
            for ($i = 0; $i < $len; ++$i) {
                $row += $checktable[$code[$i]][0];
                $col += $checktable[$code[$i]][1];
            }
            $row %= 6;
            $col %= 6;
            $chk = array_keys($checktable, [$row, $col]);
            $code .= $chk[0];
            ++$len;

            // start bar
            $barcode->addBar(new BarcodeBar(1, 2, 1));
            $barcode->addBar(new BarcodeBar(1, 2, 0));
        }

        for ($i = 0; $i < $len; ++$i) {
            for ($j = 0; $j < 4; ++$j) {
                switch ($barmode[$code[$i]][$j]) {
                    case 1:
                        $p = 0;
                        $h = 2;
                        break;

                    case 2:
                        $p = 0;
                        $h = 3;
                        break;

                    case 3:
                        $p = 1;
                        $h = 1;
                        break;

                    case 4:
                        $p = 1;
                        $h = 2;
                        break;
                }

                $barcode->addBar(new BarcodeBar(1, $h, 1, $p));
                $barcode->addBar(new BarcodeBar(1, 2, 0));
            }
        }

        if (! $this->kix) {
            // stop bar
            $barcode->addBar(new BarcodeBar(1, 3, 1));
        }

        return $barcode;
    }
}

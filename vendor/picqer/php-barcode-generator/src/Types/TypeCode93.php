<?php

namespace Picqer\Barcode\Types;

use Picqer\Barcode\Barcode;
use Picqer\Barcode\BarcodeBar;
use Picqer\Barcode\Exceptions\InvalidCharacterException;

/*
 * CODE 93 - USS-93
 * Compact code similar to Code 39
 * 
 * reference: https://en.wikipedia.org/wiki/Code_93#Full_ASCII_Code_93
 */

class TypeCode93 implements TypeInterface
{
    protected $conversionTable = [
        48 => '131112', // 0
        49 => '111213', // 1
        50 => '111312', // 2
        51 => '111411', // 3
        52 => '121113', // 4
        53 => '121212', // 5
        54 => '121311', // 6
        55 => '111114', // 7
        56 => '131211', // 8
        57 => '141111', // 9
        65 => '211113', // A
        66 => '211212', // B
        67 => '211311', // C
        68 => '221112', // D
        69 => '221211', // E
        70 => '231111', // F
        71 => '112113', // G
        72 => '112212', // H
        73 => '112311', // I
        74 => '122112', // J
        75 => '132111', // K
        76 => '111123', // L
        77 => '111222', // M
        78 => '111321', // N
        79 => '121122', // O
        80 => '131121', // P
        81 => '212112', // Q
        82 => '212211', // R
        83 => '211122', // S
        84 => '211221', // T
        85 => '221121', // U
        86 => '222111', // V
        87 => '112122', // W
        88 => '112221', // X
        89 => '122121', // Y
        90 => '123111', // Z
        45 => '121131', // -
        46 => '311112', // .
        32 => '311211', //
        36 => '321111', // $
        47 => '112131', // /
        43 => '113121', // +
        37 => '211131', // %
        97 => '121221', // ($)
        98 => '312111', // (%)
        99 => '311121', // (/)
        100 => '122211', // (+)
        42 => '111141', // start-stop
    ];

    public function getBarcodeData(string $code): Barcode
    {
        $encode = [
            chr(0) => 'bU',
            chr(1) => 'aA',
            chr(2) => 'aB',
            chr(3) => 'aC',
            chr(4) => 'aD',
            chr(5) => 'aE',
            chr(6) => 'aF',
            chr(7) => 'aG',
            chr(8) => 'aH',
            chr(9) => 'aI',
            chr(10) => 'aJ',
            chr(11) => 'aK',
            chr(12) => 'aL',
            chr(13) => 'aM',
            chr(14) => 'aN',
            chr(15) => 'aO',
            chr(16) => 'aP',
            chr(17) => 'aQ',
            chr(18) => 'aR',
            chr(19) => 'aS',
            chr(20) => 'aT',
            chr(21) => 'aU',
            chr(22) => 'aV',
            chr(23) => 'aW',
            chr(24) => 'aX',
            chr(25) => 'aY',
            chr(26) => 'aZ',
            chr(27) => 'bA',
            chr(28) => 'bB',
            chr(29) => 'bC',
            chr(30) => 'bD',
            chr(31) => 'bE',
            chr(32) => ' ',
            chr(33) => 'cA',
            chr(34) => 'cB',
            chr(35) => 'cC',
            chr(36) => '$',
            chr(37) => '%',
            chr(38) => 'cF',
            chr(39) => 'cG',
            chr(40) => 'cH',
            chr(41) => 'cI',
            chr(42) => 'cJ',
            chr(43) => '+',
            chr(44) => 'cL',
            chr(45) => '-',
            chr(46) => '.',
            chr(47) => '/',
            chr(48) => '0',
            chr(49) => '1',
            chr(50) => '2',
            chr(51) => '3',
            chr(52) => '4',
            chr(53) => '5',
            chr(54) => '6',
            chr(55) => '7',
            chr(56) => '8',
            chr(57) => '9',
            chr(58) => 'cZ',
            chr(59) => 'bF',
            chr(60) => 'bG',
            chr(61) => 'bH',
            chr(62) => 'bI',
            chr(63) => 'bJ',
            chr(64) => 'bV',
            chr(65) => 'A',
            chr(66) => 'B',
            chr(67) => 'C',
            chr(68) => 'D',
            chr(69) => 'E',
            chr(70) => 'F',
            chr(71) => 'G',
            chr(72) => 'H',
            chr(73) => 'I',
            chr(74) => 'J',
            chr(75) => 'K',
            chr(76) => 'L',
            chr(77) => 'M',
            chr(78) => 'N',
            chr(79) => 'O',
            chr(80) => 'P',
            chr(81) => 'Q',
            chr(82) => 'R',
            chr(83) => 'S',
            chr(84) => 'T',
            chr(85) => 'U',
            chr(86) => 'V',
            chr(87) => 'W',
            chr(88) => 'X',
            chr(89) => 'Y',
            chr(90) => 'Z',
            chr(91) => 'bK',
            chr(92) => 'bL',
            chr(93) => 'bM',
            chr(94) => 'bN',
            chr(95) => 'bO',
            chr(96) => 'bW',
            chr(97) => 'dA',
            chr(98) => 'dB',
            chr(99) => 'dC',
            chr(100) => 'dD',
            chr(101) => 'dE',
            chr(102) => 'dF',
            chr(103) => 'dG',
            chr(104) => 'dH',
            chr(105) => 'dI',
            chr(106) => 'dJ',
            chr(107) => 'dK',
            chr(108) => 'dL',
            chr(109) => 'dM',
            chr(110) => 'dN',
            chr(111) => 'dO',
            chr(112) => 'dP',
            chr(113) => 'dQ',
            chr(114) => 'dR',
            chr(115) => 'dS',
            chr(116) => 'dT',
            chr(117) => 'dU',
            chr(118) => 'dV',
            chr(119) => 'dW',
            chr(120) => 'dX',
            chr(121) => 'dY',
            chr(122) => 'dZ',
            chr(123) => 'bP',
            chr(124) => 'bQ',
            chr(125) => 'bR',
            chr(126) => 'bS',
            chr(127) => 'bT',
        ];

        $code_ext = '';
        $clen = strlen($code);
        for ($i = 0; $i < $clen; ++$i) {
            if (ord($code[$i]) > 127) {
                throw new InvalidCharacterException('Only supports till char 127');
            }
            $code_ext .= $encode[$code[$i]];
        }

        // checksum
        $code_ext .= $this->checksum_code93($code_ext);

        // add start and stop codes
        $code = '*' . $code_ext . '*';

        $barcode = new Barcode($code);

        for ($i = 0; $i < strlen($code); ++$i) {
            $char = ord($code[$i]);
            if (! isset($this->conversionTable[$char])) {
                throw new InvalidCharacterException('Char ' . $char . ' is unsupported');
            }

            for ($j = 0; $j < 6; ++$j) {
                if (($j % 2) == 0) {
                    $drawBar = true;
                } else {
                    $drawBar = false;
                }
                $barWidth = $this->conversionTable[$char][$j];

                $barcode->addBar(new BarcodeBar($barWidth, 1, $drawBar));
            }
        }

        $barcode->addBar(new BarcodeBar(1, 1, true));

        return $barcode;
    }

    /**
     * Calculate CODE 93 checksum (modulo 47).
     *
     * @param $code (string) code to represent.
     * @return string checksum code.
     * @protected
     */
    protected function checksum_code93($code)
    {
        $chars = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '-', '.', ' ', '$', '/', '+', '%', 'a', 'b', 'c', 'd'];

        // calculate check digit C
        $len = strlen($code);
        $p = 1;
        $check = 0;
        for ($i = ($len - 1); $i >= 0; --$i) {
            $k = array_keys($chars, $code[$i]);
            $check += ($k[0] * $p);
            ++$p;
            if ($p > 20) {
                $p = 1;
            }
        }
        $check %= 47;
        $c = $chars[$check];
        $code .= $c;

        // calculate check digit K
        $p = 1;
        $check = 0;
        for ($i = $len; $i >= 0; --$i) {
            $k = array_keys($chars, $code[$i]);
            $check += ($k[0] * $p);
            ++$p;
            if ($p > 15) {
                $p = 1;
            }
        }
        $check %= 47;
        $k = $chars[$check];

        $checksum = $c . $k;

        return $checksum;
    }
}

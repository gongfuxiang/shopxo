<?php

namespace Picqer\Barcode\Types;

use Picqer\Barcode\Barcode;
use Picqer\Barcode\BarcodeBar;
use Picqer\Barcode\Exceptions\InvalidCharacterException;
use Picqer\Barcode\Exceptions\InvalidLengthException;

/*
 * CODE 39 - ANSI MH10.8M-1983 - USD-3 - 3 of 9.
 * General-purpose code in very wide use world-wide
 */

class TypeCode39 implements TypeInterface
{
    protected $extended = false;
    protected $checksum = false;

    protected $conversionTable = [
        '0' => '111331311',
        '1' => '311311113',
        '2' => '113311113',
        '3' => '313311111',
        '4' => '111331113',
        '5' => '311331111',
        '6' => '113331111',
        '7' => '111311313',
        '8' => '311311311',
        '9' => '113311311',
        'A' => '311113113',
        'B' => '113113113',
        'C' => '313113111',
        'D' => '111133113',
        'E' => '311133111',
        'F' => '113133111',
        'G' => '111113313',
        'H' => '311113311',
        'I' => '113113311',
        'J' => '111133311',
        'K' => '311111133',
        'L' => '113111133',
        'M' => '313111131',
        'N' => '111131133',
        'O' => '311131131',
        'P' => '113131131',
        'Q' => '111111333',
        'R' => '311111331',
        'S' => '113111331',
        'T' => '111131331',
        'U' => '331111113',
        'V' => '133111113',
        'W' => '333111111',
        'X' => '131131113',
        'Y' => '331131111',
        'Z' => '133131111',
        '-' => '131111313',
        '.' => '331111311',
        ' ' => '133111311',
        '$' => '131313111',
        '/' => '131311131',
        '+' => '131113131',
        '%' => '111313131',
        '*' => '131131311',
    ];

    public function getBarcodeData(string $code): Barcode
    {
        if (strlen(trim($code)) === 0) {
            throw new InvalidLengthException('You should provide a barcode string.');
        }

        if ($this->extended) {
            // extended mode
            $code = $this->encode_code39_ext($code);
        }

        if ($this->checksum) {
            // checksum
            $code .= $this->checksum_code39($code);
        }

        // add start and stop codes
        $code = '*' . $code . '*';

        $barcode = new Barcode($code);

        for ($i = 0; $i < strlen($code); ++$i) {
            $char = $code[$i];
            if (! isset($this->conversionTable[$char])) {
                throw new InvalidCharacterException('Char ' . $char . ' is unsupported');
            }

            for ($j = 0; $j < 9; ++$j) {
                if (($j % 2) == 0) {
                    $drawBar = true;
                } else {
                    $drawBar = false;
                }
                $barWidth = $this->conversionTable[$char][$j];
                $barcode->addBar(new BarcodeBar($barWidth, 1, $drawBar));
            }

            // inter character gap
            $barcode->addBar(new BarcodeBar(1, 1, false));
        }

        return $barcode;
    }


    /**
     * Encode a string to be used for CODE 39 Extended mode.
     *
     * @param string $code code to represent.
     * @return bool|string encoded string.
     * @protected
     */
    protected function encode_code39_ext($code)
    {
        $encode = [
            chr(0) => '%U',
            chr(1) => '$A',
            chr(2) => '$B',
            chr(3) => '$C',
            chr(4) => '$D',
            chr(5) => '$E',
            chr(6) => '$F',
            chr(7) => '$G',
            chr(8) => '$H',
            chr(9) => '$I',
            chr(10) => '$J',
            chr(11) => '$K',
            chr(12) => '$L',
            chr(13) => '$M',
            chr(14) => '$N',
            chr(15) => '$O',
            chr(16) => '$P',
            chr(17) => '$Q',
            chr(18) => '$R',
            chr(19) => '$S',
            chr(20) => '$T',
            chr(21) => '$U',
            chr(22) => '$V',
            chr(23) => '$W',
            chr(24) => '$X',
            chr(25) => '$Y',
            chr(26) => '$Z',
            chr(27) => '%A',
            chr(28) => '%B',
            chr(29) => '%C',
            chr(30) => '%D',
            chr(31) => '%E',
            chr(32) => ' ',
            chr(33) => '/A',
            chr(34) => '/B',
            chr(35) => '/C',
            chr(36) => '/D',
            chr(37) => '/E',
            chr(38) => '/F',
            chr(39) => '/G',
            chr(40) => '/H',
            chr(41) => '/I',
            chr(42) => '/J',
            chr(43) => '/K',
            chr(44) => '/L',
            chr(45) => '-',
            chr(46) => '.',
            chr(47) => '/O',
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
            chr(58) => '/Z',
            chr(59) => '%F',
            chr(60) => '%G',
            chr(61) => '%H',
            chr(62) => '%I',
            chr(63) => '%J',
            chr(64) => '%V',
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
            chr(91) => '%K',
            chr(92) => '%L',
            chr(93) => '%M',
            chr(94) => '%N',
            chr(95) => '%O',
            chr(96) => '%W',
            chr(97) => '+A',
            chr(98) => '+B',
            chr(99) => '+C',
            chr(100) => '+D',
            chr(101) => '+E',
            chr(102) => '+F',
            chr(103) => '+G',
            chr(104) => '+H',
            chr(105) => '+I',
            chr(106) => '+J',
            chr(107) => '+K',
            chr(108) => '+L',
            chr(109) => '+M',
            chr(110) => '+N',
            chr(111) => '+O',
            chr(112) => '+P',
            chr(113) => '+Q',
            chr(114) => '+R',
            chr(115) => '+S',
            chr(116) => '+T',
            chr(117) => '+U',
            chr(118) => '+V',
            chr(119) => '+W',
            chr(120) => '+X',
            chr(121) => '+Y',
            chr(122) => '+Z',
            chr(123) => '%P',
            chr(124) => '%Q',
            chr(125) => '%R',
            chr(126) => '%S',
            chr(127) => '%T'
        ];

        $code_ext = '';
        for ($i = 0; $i < strlen($code); ++$i) {
            if (ord($code[$i]) > 127) {
                throw new InvalidCharacterException('Only supports till char 127');
            }

            $code_ext .= $encode[$code[$i]];
        }

        return $code_ext;
    }


    /**
     * Calculate CODE 39 checksum (modulo 43).
     *
     * @param string $code code to represent.
     * @return string char checksum.
     * @protected
     */
    protected function checksum_code39($code)
    {
        $chars = [
            '0',
            '1',
            '2',
            '3',
            '4',
            '5',
            '6',
            '7',
            '8',
            '9',
            'A',
            'B',
            'C',
            'D',
            'E',
            'F',
            'G',
            'H',
            'I',
            'J',
            'K',
            'L',
            'M',
            'N',
            'O',
            'P',
            'Q',
            'R',
            'S',
            'T',
            'U',
            'V',
            'W',
            'X',
            'Y',
            'Z',
            '-',
            '.',
            ' ',
            '$',
            '/',
            '+',
            '%'
        ];

        $sum = 0;
        for ($i = 0; $i < strlen($code); ++$i) {
            $k = array_keys($chars, $code[$i]);
            $sum += $k[0];
        }
        $j = ($sum % 43);

        return $chars[$j];
    }
}

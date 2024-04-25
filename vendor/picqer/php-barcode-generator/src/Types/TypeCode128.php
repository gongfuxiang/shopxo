<?php

namespace Picqer\Barcode\Types;

use Picqer\Barcode\Barcode;
use Picqer\Barcode\BarcodeBar;
use Picqer\Barcode\Exceptions\BarcodeException;
use Picqer\Barcode\Exceptions\InvalidCharacterException;
use Picqer\Barcode\Exceptions\InvalidLengthException;

/*
 * C128 barcodes.
 * Very capable code, excellent density, high reliability; in very wide use world-wide
 *
 * @param $code (string) code to represent.
 * @param $type (string) barcode type: A, B, C or empty for automatic switch (AUTO mode)
 */

class TypeCode128 implements TypeInterface
{
    protected $type = null;

    protected $conversionTable = [
        '212222', /* 00 */
        '222122', /* 01 */
        '222221', /* 02 */
        '121223', /* 03 */
        '121322', /* 04 */
        '131222', /* 05 */
        '122213', /* 06 */
        '122312', /* 07 */
        '132212', /* 08 */
        '221213', /* 09 */
        '221312', /* 10 */
        '231212', /* 11 */
        '112232', /* 12 */
        '122132', /* 13 */
        '122231', /* 14 */
        '113222', /* 15 */
        '123122', /* 16 */
        '123221', /* 17 */
        '223211', /* 18 */
        '221132', /* 19 */
        '221231', /* 20 */
        '213212', /* 21 */
        '223112', /* 22 */
        '312131', /* 23 */
        '311222', /* 24 */
        '321122', /* 25 */
        '321221', /* 26 */
        '312212', /* 27 */
        '322112', /* 28 */
        '322211', /* 29 */
        '212123', /* 30 */
        '212321', /* 31 */
        '232121', /* 32 */
        '111323', /* 33 */
        '131123', /* 34 */
        '131321', /* 35 */
        '112313', /* 36 */
        '132113', /* 37 */
        '132311', /* 38 */
        '211313', /* 39 */
        '231113', /* 40 */
        '231311', /* 41 */
        '112133', /* 42 */
        '112331', /* 43 */
        '132131', /* 44 */
        '113123', /* 45 */
        '113321', /* 46 */
        '133121', /* 47 */
        '313121', /* 48 */
        '211331', /* 49 */
        '231131', /* 50 */
        '213113', /* 51 */
        '213311', /* 52 */
        '213131', /* 53 */
        '311123', /* 54 */
        '311321', /* 55 */
        '331121', /* 56 */
        '312113', /* 57 */
        '312311', /* 58 */
        '332111', /* 59 */
        '314111', /* 60 */
        '221411', /* 61 */
        '431111', /* 62 */
        '111224', /* 63 */
        '111422', /* 64 */
        '121124', /* 65 */
        '121421', /* 66 */
        '141122', /* 67 */
        '141221', /* 68 */
        '112214', /* 69 */
        '112412', /* 70 */
        '122114', /* 71 */
        '122411', /* 72 */
        '142112', /* 73 */
        '142211', /* 74 */
        '241211', /* 75 */
        '221114', /* 76 */
        '413111', /* 77 */
        '241112', /* 78 */
        '134111', /* 79 */
        '111242', /* 80 */
        '121142', /* 81 */
        '121241', /* 82 */
        '114212', /* 83 */
        '124112', /* 84 */
        '124211', /* 85 */
        '411212', /* 86 */
        '421112', /* 87 */
        '421211', /* 88 */
        '212141', /* 89 */
        '214121', /* 90 */
        '412121', /* 91 */
        '111143', /* 92 */
        '111341', /* 93 */
        '131141', /* 94 */
        '114113', /* 95 */
        '114311', /* 96 */
        '411113', /* 97 */
        '411311', /* 98 */
        '113141', /* 99 */
        '114131', /* 100 */
        '311141', /* 101 */
        '411131', /* 102 */
        '211412', /* 103 START A */
        '211214', /* 104 START B */
        '211232', /* 105 START C */
        '233111', /* STOP */
        '200000'  /* END */
    ];

    public function getBarcodeData(string $code): Barcode
    {
        if (strlen(trim($code)) === 0) {
            throw new InvalidLengthException('You should provide a barcode string.');
        }

        // ASCII characters for code A (ASCII 00 - 95)
        $keys_a = ' !"#$%&\'()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\\]^_';
        $keys_a .= chr(0) . chr(1) . chr(2) . chr(3) . chr(4) . chr(5) . chr(6) . chr(7) . chr(8) . chr(9);
        $keys_a .= chr(10) . chr(11) . chr(12) . chr(13) . chr(14) . chr(15) . chr(16) . chr(17) . chr(18) . chr(19);
        $keys_a .= chr(20) . chr(21) . chr(22) . chr(23) . chr(24) . chr(25) . chr(26) . chr(27) . chr(28) . chr(29);
        $keys_a .= chr(30) . chr(31);

        // ASCII characters for code B (ASCII 32 - 127)
        $keys_b = ' !"#$%&\'()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\\]^_`abcdefghijklmnopqrstuvwxyz{|}~' . chr(127);

        // special codes
        $fnc_a = [241 => 102, 242 => 97, 243 => 96, 244 => 101];
        $fnc_b = [241 => 102, 242 => 97, 243 => 96, 244 => 100];

        // array of symbols
        $code_data = [];

        // length of the code
        $len = strlen($code);

        switch (strtoupper($this->type ?? "")) {
            case 'A':
                $startid = 103;
                for ($i = 0; $i < $len; ++$i) {
                    $char = $code[$i];
                    $char_id = ord($char);
                    if (($char_id >= 241) AND ($char_id <= 244)) {
                        $code_data[] = $fnc_a[$char_id];
                    } elseif ($char_id <= 95) {
                        $code_data[] = strpos($keys_a, $char);
                    } else {
                        throw new InvalidCharacterException('Char ' . $char . ' is unsupported');
                    }
                }
                break;

            case 'B':
                $startid = 104;
                for ($i = 0; $i < $len; ++$i) {
                    $char = $code[$i];
                    $char_id = ord($char);
                    if (($char_id >= 241) AND ($char_id <= 244)) {
                        $code_data[] = $fnc_b[$char_id];
                    } elseif (($char_id >= 32) AND ($char_id <= 127)) {
                        $code_data[] = strpos($keys_b, $char);
                    } else {
                        throw new InvalidCharacterException('Char ' . $char . ' is unsupported');
                    }
                }
                break;

            case 'C':
                $startid = 105;
                if (ord($code[0]) == 241) {
                    $code_data[] = 102;
                    $code = substr($code, 1);
                    --$len;
                }
                if (($len % 2) != 0) {
                    throw new InvalidLengthException('Length must be even');
                }
                for ($i = 0; $i < $len; $i += 2) {
                    $chrnum = $code[$i] . $code[$i + 1];
                    if (preg_match('/([0-9]{2})/', $chrnum) > 0) {
                        $code_data[] = intval($chrnum);
                    } else {
                        throw new InvalidCharacterException();
                    }
                }
                break;

            default:
                // split code into sequences
                $sequence = [];
                // get numeric sequences (if any)
                $numseq = [];
                preg_match_all('/([0-9]{4,})/', $code, $numseq, PREG_OFFSET_CAPTURE);
                if (isset($numseq[1]) AND ! empty($numseq[1])) {
                    $end_offset = 0;
                    foreach ($numseq[1] as $val) {
                        $offset = $val[1];

                        // numeric sequence
                        $slen = strlen($val[0]);
                        if (($slen % 2) != 0) {
                            // the length must be even
                            ++$offset;
                            $val[0] = substr($val[0], 1);
                        }

                        if ($offset > $end_offset) {
                            // non numeric sequence
                            $sequence = array_merge($sequence,
                                $this->get128ABsequence(substr($code, $end_offset, ($offset - $end_offset))));
                        }
                        // numeric sequence fallback
                        $slen = strlen($val[0]);
                        if (($slen % 2) != 0) {
                            // the length must be even
                            --$slen;
                        }
                        $sequence[] = ['C', substr($code, $offset, $slen), $slen];
                        $end_offset = $offset + $slen;
                    }
                    if ($end_offset < $len) {
                        $sequence = array_merge($sequence, $this->get128ABsequence(substr($code, $end_offset)));
                    }
                } else {
                    // text code (non C mode)
                    $sequence = array_merge($sequence, $this->get128ABsequence($code));
                }

                // process the sequence
                foreach ($sequence as $key => $seq) {
                    switch ($seq[0]) {
                        case 'A':
                            if ($key == 0) {
                                $startid = 103;
                            } elseif ($sequence[($key - 1)][0] != 'A') {
                                if (($seq[2] == 1) AND ($key > 0) AND ($sequence[($key - 1)][0] == 'B') AND (! isset($sequence[($key - 1)][3]))) {
                                    // single character shift
                                    $code_data[] = 98;
                                    // mark shift
                                    $sequence[$key][3] = true;
                                } elseif (! isset($sequence[($key - 1)][3])) {
                                    $code_data[] = 101;
                                }
                            }
                            for ($i = 0; $i < $seq[2]; ++$i) {
                                $char = $seq[1][$i];
                                $char_id = ord($char);
                                if (($char_id >= 241) AND ($char_id <= 244)) {
                                    $code_data[] = $fnc_a[$char_id];
                                } else {
                                    $code_data[] = strpos($keys_a, $char);
                                }
                            }
                            break;

                        case 'B':
                            if ($key == 0) {
                                $tmpchr = ord($seq[1][0]);
                                if (($seq[2] == 1) AND ($tmpchr >= 241) AND ($tmpchr <= 244) AND isset($sequence[($key + 1)]) AND ($sequence[($key + 1)][0] != 'B')) {
                                    switch ($sequence[($key + 1)][0]) {
                                        case 'A':
                                        {
                                            $startid = 103;
                                            $sequence[$key][0] = 'A';
                                            $code_data[] = $fnc_a[$tmpchr];
                                            break;
                                        }
                                        case 'C':
                                        {
                                            $startid = 105;
                                            $sequence[$key][0] = 'C';
                                            $code_data[] = $fnc_a[$tmpchr];
                                            break;
                                        }
                                    }
                                    break;
                                } else {
                                    $startid = 104;
                                }
                            } elseif ($sequence[($key - 1)][0] != 'B') {
                                if (($seq[2] == 1) AND ($key > 0) AND ($sequence[($key - 1)][0] == 'A') AND (! isset($sequence[($key - 1)][3]))) {
                                    // single character shift
                                    $code_data[] = 98;
                                    // mark shift
                                    $sequence[$key][3] = true;
                                } elseif (! isset($sequence[($key - 1)][3])) {
                                    $code_data[] = 100;
                                }
                            }
                            for ($i = 0; $i < $seq[2]; ++$i) {
                                $char = $seq[1][$i];
                                $char_id = ord($char);
                                if (($char_id >= 241) AND ($char_id <= 244)) {
                                    $code_data[] = $fnc_b[$char_id];
                                } else {
                                    $code_data[] = strpos($keys_b, $char);
                                }
                            }
                            break;

                        case 'C':
                            if ($key == 0) {
                                $startid = 105;
                            } elseif ($sequence[($key - 1)][0] != 'C') {
                                $code_data[] = 99;
                            }
                            for ($i = 0; $i < $seq[2]; $i += 2) {
                                $chrnum = $seq[1][$i] . $seq[1][$i + 1];
                                $code_data[] = intval($chrnum);
                            }
                            break;

                        default:
                            throw new InvalidCharacterException('Do not support different mode then A, B or C.');
                    }
                }
        }

        // calculate check character
        if (! isset($startid)) {
            throw new BarcodeException('Could not determine start char for barcode.');
        }

        $sum = $startid;
        foreach ($code_data as $key => $val) {
            $sum += ($val * ($key + 1));
        }
        // add check character
        $code_data[] = ($sum % 103);
        // add stop sequence
        $code_data[] = 106;
        $code_data[] = 107;
        // add start code at the beginning
        array_unshift($code_data, $startid);

        // build barcode array
        $barcode = new Barcode($code);
        foreach ($code_data as $val) {
            $seq = $this->conversionTable[$val];
            for ($j = 0; $j < 6; ++$j) {
                if (($j % 2) == 0) {
                    $t = true; // bar
                } else {
                    $t = false; // space
                }
                $w = $seq[$j];

                $barcode->addBar(new BarcodeBar($w, 1, $t));
            }
        }

        return $barcode;
    }


    /**
     * Split text code in A/B sequence for 128 code
     *
     * @param $code (string) code to split.
     * @return array sequence
     * @protected
     */
    protected function get128ABsequence($code)
    {
        $len = strlen($code);
        $sequence = [];
        // get A sequences (if any)
        $numseq = [];
        preg_match_all('/([\x00-\x1f])/', $code, $numseq, PREG_OFFSET_CAPTURE);
        if (isset($numseq[1]) AND ! empty($numseq[1])) {
            $end_offset = 0;
            foreach ($numseq[1] as $val) {
                $offset = $val[1];
                if ($offset > $end_offset) {
                    // B sequence
                    $sequence[] = [
                        'B',
                        substr($code, $end_offset, ($offset - $end_offset)),
                        ($offset - $end_offset)
                    ];
                }
                // A sequence
                $slen = strlen($val[0]);
                $sequence[] = ['A', substr($code, $offset, $slen), $slen];
                $end_offset = $offset + $slen;
            }
            if ($end_offset < $len) {
                $sequence[] = ['B', substr($code, $end_offset), ($len - $end_offset)];
            }
        } else {
            // only B sequence
            $sequence[] = ['B', $code, $len];
        }

        return $sequence;
    }
}

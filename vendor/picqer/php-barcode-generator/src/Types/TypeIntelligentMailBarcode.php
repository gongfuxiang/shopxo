<?php

namespace Picqer\Barcode\Types;

use Picqer\Barcode\Barcode;
use Picqer\Barcode\BarcodeBar;
use Picqer\Barcode\Exceptions\BarcodeException;

/*
 * IMB - Intelligent Mail Barcode - Onecode - USPS-B-3200
 * (requires PHP bcmath extension)
 * Intelligent Mail barcode is a 65-bar code for use on mail in the United States.
 * The fields are described as follows:<ul><li>The Barcode Identifier shall be assigned by USPS to encode the
 * presort identification that is currently printed in human readable form on the optional endorsement line (OEL)
 * as well as for future USPS use. This shall be two digits, with the second digit in the range of 0–4. The
 * allowable encoding ranges shall be 00–04, 10–14, 20–24, 30–34, 40–44, 50–54, 60–64, 70–74, 80–84, and
 * 90–94.</li><li>The Service Type Identifier shall be assigned by USPS for any combination of services requested
 * on the mailpiece. The allowable encoding range shall be 000http://it2.php.net/manual/en/function.dechex.php–999.
 * Each 3-digit value shall correspond to a particular mail class with a particular combination of service(s). Each
 * service program, such as OneCode Confirm and OneCode ACS, shall provide the list of Service Type Identifier
 * values.</li><li>The Mailer or Customer Identifier shall be assigned by USPS as a unique, 6 or 9 digit number
 * that identifies a business entity. The allowable encoding range for the 6 digit Mailer ID shall be 000000-
 * 899999, while the allowable encoding range for the 9 digit Mailer ID shall be 900000000-999999999.</li><li>The
 * Serial or Sequence Number shall be assigned by the mailer for uniquely identifying and tracking mailpieces. The
 * allowable encoding range shall be 000000000–999999999 when used with a 6 digit Mailer ID and 000000-999999 when
 * used with a 9 digit Mailer ID. e. The Delivery Point ZIP Code shall be assigned by the mailer for routing the
 * mailpiece. This shall replace POSTNET for routing the mailpiece to its final delivery point. The length may be
 * 0, 5, 9, or 11 digits. The allowable encoding ranges shall be no ZIP Code, 00000–99999,  000000000–999999999,
 * and 00000000000–99999999999.</li></ul>
 *
 * code to print, separate the ZIP (routing code) from the rest using a minus char '-'
 *     (BarcodeID_ServiceTypeID_MailerID_SerialNumber-RoutingCode)
 */

class TypeIntelligentMailBarcode implements TypeInterface
{
    public function getBarcodeData(string $code): Barcode
    {
        $asc_chr = [
            4,
            0,
            2,
            6,
            3,
            5,
            1,
            9,
            8,
            7,
            1,
            2,
            0,
            6,
            4,
            8,
            2,
            9,
            5,
            3,
            0,
            1,
            3,
            7,
            4,
            6,
            8,
            9,
            2,
            0,
            5,
            1,
            9,
            4,
            3,
            8,
            6,
            7,
            1,
            2,
            4,
            3,
            9,
            5,
            7,
            8,
            3,
            0,
            2,
            1,
            4,
            0,
            9,
            1,
            7,
            0,
            2,
            4,
            6,
            3,
            7,
            1,
            9,
            5,
            8
        ];
        $dsc_chr = [
            7,
            1,
            9,
            5,
            8,
            0,
            2,
            4,
            6,
            3,
            5,
            8,
            9,
            7,
            3,
            0,
            6,
            1,
            7,
            4,
            6,
            8,
            9,
            2,
            5,
            1,
            7,
            5,
            4,
            3,
            8,
            7,
            6,
            0,
            2,
            5,
            4,
            9,
            3,
            0,
            1,
            6,
            8,
            2,
            0,
            4,
            5,
            9,
            6,
            7,
            5,
            2,
            6,
            3,
            8,
            5,
            1,
            9,
            8,
            7,
            4,
            0,
            2,
            6,
            3
        ];
        $asc_pos = [
            3,
            0,
            8,
            11,
            1,
            12,
            8,
            11,
            10,
            6,
            4,
            12,
            2,
            7,
            9,
            6,
            7,
            9,
            2,
            8,
            4,
            0,
            12,
            7,
            10,
            9,
            0,
            7,
            10,
            5,
            7,
            9,
            6,
            8,
            2,
            12,
            1,
            4,
            2,
            0,
            1,
            5,
            4,
            6,
            12,
            1,
            0,
            9,
            4,
            7,
            5,
            10,
            2,
            6,
            9,
            11,
            2,
            12,
            6,
            7,
            5,
            11,
            0,
            3,
            2
        ];
        $dsc_pos = [
            2,
            10,
            12,
            5,
            9,
            1,
            5,
            4,
            3,
            9,
            11,
            5,
            10,
            1,
            6,
            3,
            4,
            1,
            10,
            0,
            2,
            11,
            8,
            6,
            1,
            12,
            3,
            8,
            6,
            4,
            4,
            11,
            0,
            6,
            1,
            9,
            11,
            5,
            3,
            7,
            3,
            10,
            7,
            11,
            8,
            2,
            10,
            3,
            5,
            8,
            0,
            3,
            12,
            11,
            8,
            4,
            5,
            1,
            3,
            0,
            7,
            12,
            9,
            8,
            10
        ];
        $code_arr = explode('-', $code);
        $tracking_number = $code_arr[0];
        if (isset($code_arr[1])) {
            $routing_code = $code_arr[1];
        } else {
            $routing_code = '';
        }
        // Conversion of Routing Code
        switch (strlen($routing_code)) {
            case 0:
                $binary_code = 0;
                break;

            case 5:
                $binary_code = bcadd($routing_code, '1');
                break;

            case 9:
                $binary_code = bcadd($routing_code, '100001');
                break;

            case 11:
                $binary_code = bcadd($routing_code, '1000100001');
                break;

            default:
                throw new BarcodeException('Routing code unknown');
        }

        $binary_code = bcmul($binary_code, 10);
        $binary_code = bcadd($binary_code, $tracking_number[0]);
        $binary_code = bcmul($binary_code, 5);
        $binary_code = bcadd($binary_code, $tracking_number[1]);
        $binary_code .= substr($tracking_number, 2, 18);

        // convert to hexadecimal
        $binary_code = $this->dec_to_hex($binary_code);

        // pad to get 13 bytes
        $binary_code = str_pad($binary_code, 26, '0', STR_PAD_LEFT);

        // convert string to array of bytes
        $binary_code_arr = chunk_split($binary_code, 2, "\r");
        $binary_code_arr = substr($binary_code_arr, 0, -1);
        $binary_code_arr = explode("\r", $binary_code_arr);

        // calculate frame check sequence
        $fcs = $this->imb_crc11fcs($binary_code_arr);

        // exclude first 2 bits from first byte
        $first_byte = sprintf('%2s', dechex((hexdec($binary_code_arr[0]) << 2) >> 2));
        $binary_code_102bit = $first_byte . substr($binary_code, 2);

        // convert binary data to codewords
        $codewords = [];
        $data = $this->hex_to_dec($binary_code_102bit);
        $codewords[0] = bcmod($data, 636) * 2;
        $data = bcdiv($data, 636);
        for ($i = 1; $i < 9; ++$i) {
            $codewords[$i] = bcmod($data, 1365);
            $data = bcdiv($data, 1365);
        }
        $codewords[9] = $data;
        if (($fcs >> 10) == 1) {
            $codewords[9] += 659;
        }

        // generate lookup tables
        $table2of13 = $this->imb_tables(2, 78);
        $table5of13 = $this->imb_tables(5, 1287);

        // convert codewords to characters
        $characters = [];
        $bitmask = 512;
        foreach ($codewords as $val) {
            if ($val <= 1286) {
                $chrcode = (int)$table5of13[$val];
            } else {
                $chrcode = (int)$table2of13[($val - 1287)];
            }
            if (($fcs & $bitmask) > 0) {
                // bitwise invert
                $chrcode = ((~$chrcode) & 8191);
            }
            $characters[] = $chrcode;
            $bitmask /= 2;
        }
        $characters = array_reverse($characters);

        // build bars
        $barcode = new Barcode($code);
        for ($i = 0; $i < 65; ++$i) {
            $asc = (($characters[$asc_chr[$i]] & pow(2, $asc_pos[$i])) > 0);
            $dsc = (($characters[$dsc_chr[$i]] & pow(2, $dsc_pos[$i])) > 0);
            if ($asc AND $dsc) {
                // full bar (F)
                $p = 0;
                $h = 3;
            } elseif ($asc) {
                // ascender (A)
                $p = 0;
                $h = 2;
            } elseif ($dsc) {
                // descender (D)
                $p = 1;
                $h = 2;
            } else {
                // tracker (T)
                $p = 1;
                $h = 1;
            }
            $barcode->addBar(new BarcodeBar(1, $h, true, $p));
            if ($i < 64) {
                $barcode->addBar(new BarcodeBar(1, 2, false, 0));
            }
        }

        return $barcode;
    }

    /**
     * Convert large integer number to hexadecimal representation.
     * (requires PHP bcmath extension)
     *
     * @param $number (string) number to convert specified as a string
     * @return string hexadecimal representation
     */
    protected function dec_to_hex($number)
    {
        if ($number == 0) {
            return '00';
        }

        $hex = [];

        while ($number > 0) {
            array_push($hex, strtoupper(dechex(bcmod($number, '16'))));
            $number = bcdiv($number, '16', 0);
        }
        $hex = array_reverse($hex);

        return implode($hex);
    }


    /**
     * Intelligent Mail Barcode calculation of Frame Check Sequence
     *
     * @param $code_arr (string) array of hexadecimal values (13 bytes holding 102 bits right justified).
     * @return int 11 bit Frame Check Sequence as integer (decimal base)
     * @protected
     */
    protected function imb_crc11fcs($code_arr)
    {
        $genpoly = 0x0F35; // generator polynomial
        $fcs = 0x07FF; // Frame Check Sequence
        // do most significant byte skipping the 2 most significant bits
        $data = hexdec($code_arr[0]) << 5;
        for ($bit = 2; $bit < 8; ++$bit) {
            if (($fcs ^ $data) & 0x400) {
                $fcs = ($fcs << 1) ^ $genpoly;
            } else {
                $fcs = ($fcs << 1);
            }
            $fcs &= 0x7FF;
            $data <<= 1;
        }
        // do rest of bytes
        for ($byte = 1; $byte < 13; ++$byte) {
            $data = hexdec($code_arr[$byte]) << 3;
            for ($bit = 0; $bit < 8; ++$bit) {
                if (($fcs ^ $data) & 0x400) {
                    $fcs = ($fcs << 1) ^ $genpoly;
                } else {
                    $fcs = ($fcs << 1);
                }
                $fcs &= 0x7FF;
                $data <<= 1;
            }
        }

        return $fcs;
    }

    /**
     * Convert large hexadecimal number to decimal representation (string).
     * (requires PHP bcmath extension)
     *
     * @param $hex (string) hexadecimal number to convert specified as a string
     * @return string hexadecimal representation
     */
    protected function hex_to_dec($hex)
    {
        $dec = 0;
        $bitval = 1;
        $len = strlen($hex);
        for ($pos = ($len - 1); $pos >= 0; --$pos) {
            $dec = bcadd($dec, bcmul(hexdec($hex[$pos]), $bitval));
            $bitval = bcmul($bitval, 16);
        }

        return $dec;
    }


    /**
     * generate Nof13 tables used for Intelligent Mail Barcode
     *
     * @param $n (int) is the type of table: 2 for 2of13 table, 5 for 5of13table
     * @param $size (int) size of table (78 for n=2 and 1287 for n=5)
     * @return array requested table
     * @protected
     */
    protected function imb_tables(int $n, int $size): array
    {
        $table = [];
        $lli = 0; // LUT lower index
        $lui = $size - 1; // LUT upper index
        for ($count = 0; $count < 8192; ++$count) {
            $bit_count = 0;
            for ($bit_index = 0; $bit_index < 13; ++$bit_index) {
                $bit_count += intval(($count & (1 << $bit_index)) != 0);
            }
            // if we don't have the right number of bits on, go on to the next value
            if ($bit_count == $n) {
                $reverse = ($this->imb_reverse_us($count) >> 3);
                // if the reverse is less than count, we have already visited this pair before
                if ($reverse >= $count) {
                    // If count is symmetric, place it at the first free slot from the end of the list.
                    // Otherwise, place it at the first free slot from the beginning of the list AND place $reverse ath the next free slot from the beginning of the list
                    if ($reverse == $count) {
                        $table[$lui] = $count;
                        --$lui;
                    } else {
                        $table[$lli] = $count;
                        ++$lli;
                        $table[$lli] = $reverse;
                        ++$lli;
                    }
                }
            }
        }

        return $table;
    }

    /**
     * Reverse unsigned short value
     *
     * @param $num (int) value to reversr
     * @return int reversed value
     * @protected
     */
    protected function imb_reverse_us($num)
    {
        $rev = 0;
        for ($i = 0; $i < 16; ++$i) {
            $rev <<= 1;
            $rev |= ($num & 1);
            $num >>= 1;
        }

        return $rev;
    }
}

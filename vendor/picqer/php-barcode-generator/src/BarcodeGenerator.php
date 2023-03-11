<?php

/**
 * General PHP Barcode Generator
 *
 * @author Casper Bakker - picqer.com
 * Based on TCPDF Barcode Generator
 */

// Copyright (C) 2002-2015 Nicola Asuni - Tecnick.com LTD
//
// This file was part of TCPDF software library.
//
// TCPDF is free software: you can redistribute it and/or modify it
// under the terms of the GNU Lesser General Public License as
// published by the Free Software Foundation, either version 3 of the
// License, or (at your option) any later version.
//
// TCPDF is distributed in the hope that it will be useful, but
// WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
// See the GNU Lesser General Public License for more details.
//
// You should have received a copy of the License
// along with TCPDF. If not, see
// <http://www.tecnick.com/pagefiles/tcpdf/LICENSE.TXT>.
//
// See LICENSE.TXT file for more information.

namespace Picqer\Barcode;

use Picqer\Barcode\Exceptions\UnknownTypeException;
use Picqer\Barcode\Types\TypeCodabar;
use Picqer\Barcode\Types\TypeCode11;
use Picqer\Barcode\Types\TypeCode128;
use Picqer\Barcode\Types\TypeCode128A;
use Picqer\Barcode\Types\TypeCode128B;
use Picqer\Barcode\Types\TypeCode128C;
use Picqer\Barcode\Types\TypeCode32;
use Picqer\Barcode\Types\TypeCode39;
use Picqer\Barcode\Types\TypeCode39Checksum;
use Picqer\Barcode\Types\TypeCode39Extended;
use Picqer\Barcode\Types\TypeCode39ExtendedChecksum;
use Picqer\Barcode\Types\TypeCode93;
use Picqer\Barcode\Types\TypeEan13;
use Picqer\Barcode\Types\TypeEan8;
use Picqer\Barcode\Types\TypeIntelligentMailBarcode;
use Picqer\Barcode\Types\TypeInterleaved25;
use Picqer\Barcode\Types\TypeInterleaved25Checksum;
use Picqer\Barcode\Types\TypeKix;
use Picqer\Barcode\Types\TypeMsi;
use Picqer\Barcode\Types\TypeMsiChecksum;
use Picqer\Barcode\Types\TypePharmacode;
use Picqer\Barcode\Types\TypePharmacodeTwoCode;
use Picqer\Barcode\Types\TypePlanet;
use Picqer\Barcode\Types\TypePostnet;
use Picqer\Barcode\Types\TypeRms4cc;
use Picqer\Barcode\Types\TypeStandard2of5;
use Picqer\Barcode\Types\TypeStandard2of5Checksum;
use Picqer\Barcode\Types\TypeUpcA;
use Picqer\Barcode\Types\TypeUpcE;
use Picqer\Barcode\Types\TypeUpcExtension2;
use Picqer\Barcode\Types\TypeUpcExtension5;

abstract class BarcodeGenerator
{
    const TYPE_CODE_32 = 'C32';
    const TYPE_CODE_39 = 'C39';
    const TYPE_CODE_39_CHECKSUM = 'C39+';
    const TYPE_CODE_39E = 'C39E'; // CODE 39 EXTENDED
    const TYPE_CODE_39E_CHECKSUM = 'C39E+'; // CODE 39 EXTENDED + CHECKSUM
    const TYPE_CODE_93 = 'C93';
    const TYPE_STANDARD_2_5 = 'S25';
    const TYPE_STANDARD_2_5_CHECKSUM = 'S25+';
    const TYPE_INTERLEAVED_2_5 = 'I25';
    const TYPE_INTERLEAVED_2_5_CHECKSUM = 'I25+';
    const TYPE_CODE_128 = 'C128';
    const TYPE_CODE_128_A = 'C128A';
    const TYPE_CODE_128_B = 'C128B';
    const TYPE_CODE_128_C = 'C128C';
    const TYPE_EAN_2 = 'EAN2'; // 2-Digits UPC-Based Extention
    const TYPE_EAN_5 = 'EAN5'; // 5-Digits UPC-Based Extention
    const TYPE_EAN_8 = 'EAN8';
    const TYPE_EAN_13 = 'EAN13';
    const TYPE_UPC_A = 'UPCA';
    const TYPE_UPC_E = 'UPCE';
    const TYPE_MSI = 'MSI'; // MSI (Variation of Plessey code)
    const TYPE_MSI_CHECKSUM = 'MSI+'; // MSI + CHECKSUM (modulo 11)
    const TYPE_POSTNET = 'POSTNET';
    const TYPE_PLANET = 'PLANET';
    const TYPE_RMS4CC = 'RMS4CC'; // RMS4CC (Royal Mail 4-state Customer Code) - CBC (Customer Bar Code)
    const TYPE_KIX = 'KIX'; // KIX (Klant index - Customer index)
    const TYPE_IMB = 'IMB'; // IMB - Intelligent Mail Barcode - Onecode - USPS-B-3200
    const TYPE_CODABAR = 'CODABAR';
    const TYPE_CODE_11 = 'CODE11';
    const TYPE_PHARMA_CODE = 'PHARMA';
    const TYPE_PHARMA_CODE_TWO_TRACKS = 'PHARMA2T';

    protected function getBarcodeData(string $code, string $type): Barcode
    {
        $barcodeDataBuilder = $this->createDataBuilderForType($type);

        return $barcodeDataBuilder->getBarcodeData($code);
    }

    protected function createDataBuilderForType(string $type)
    {
        switch (strtoupper($type)) {
            case self::TYPE_CODE_32:
                return new TypeCode32();
                
            case self::TYPE_CODE_39:
                return new TypeCode39();

            case self::TYPE_CODE_39_CHECKSUM:
                return new TypeCode39Checksum();

            case self::TYPE_CODE_39E:
                return new TypeCode39Extended();

            case self::TYPE_CODE_39E_CHECKSUM:
                return new TypeCode39ExtendedChecksum();

            case self::TYPE_CODE_93:
                return new TypeCode93();

            case self::TYPE_STANDARD_2_5:
                return new TypeStandard2of5();

            case self::TYPE_STANDARD_2_5_CHECKSUM:
                return new TypeStandard2of5Checksum();

            case self::TYPE_INTERLEAVED_2_5:
                return new TypeInterleaved25();

            case self::TYPE_INTERLEAVED_2_5_CHECKSUM:
                return new TypeInterleaved25Checksum();

            case self::TYPE_CODE_128:
                return new TypeCode128();

            case self::TYPE_CODE_128_A:
                return new TypeCode128A();

            case self::TYPE_CODE_128_B:
                return new TypeCode128B();

            case self::TYPE_CODE_128_C:
                return new TypeCode128C();

            case self::TYPE_EAN_2:
                return new TypeUpcExtension2();

            case self::TYPE_EAN_5:
                return new TypeUpcExtension5();

            case self::TYPE_EAN_8:
                return new TypeEan8();

            case self::TYPE_EAN_13:
                return new TypeEan13();

            case self::TYPE_UPC_A:
                return new TypeUpcA();

            case self::TYPE_UPC_E:
                return new TypeUpcE();

            case self::TYPE_MSI:
                return new TypeMsi();

            case self::TYPE_MSI_CHECKSUM:
                return new TypeMsiChecksum();

            case self::TYPE_POSTNET:
                return new TypePostnet();

            case self::TYPE_PLANET:
                return new TypePlanet();

            case self::TYPE_RMS4CC:
                return new TypeRms4cc();

            case self::TYPE_KIX:
                return new TypeKix();

            case self::TYPE_IMB:
                return new TypeIntelligentMailBarcode();

            case self::TYPE_CODABAR:
                return new TypeCodabar();

            case self::TYPE_CODE_11:
                return new TypeCode11();

            case self::TYPE_PHARMA_CODE:
                return new TypePharmacode();

            case self::TYPE_PHARMA_CODE_TWO_TRACKS:
                return new TypePharmacodeTwoCode();
        }

        throw new UnknownTypeException();
    }
}

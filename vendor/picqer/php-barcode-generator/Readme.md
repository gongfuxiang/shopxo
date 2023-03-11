# PHP Barcode Generator 
[![Build Status](https://travis-ci.org/picqer/php-barcode-generator.svg?branch=main)](https://travis-ci.org/picqer/php-barcode-generator) [![Github Actions](https://github.com/picqer/php-barcode-generator/workflows/phpunit/badge.svg)](https://travis-ci.org/picqer/php-barcode-generator) [![Total Downloads](https://poser.pugx.org/picqer/php-barcode-generator/downloads)](https://packagist.org/packages/picqer/php-barcode-generator)

This is an easy to use, non-bloated, framework independent, barcode generator in PHP.

It creates SVG, PNG, JPG and HTML images, from the most used 1D barcode standards.

*The codebase is based on the [TCPDF barcode generator](https://github.com/tecnickcom/TCPDF) by Nicola Asuni. This code is therefor licensed under LGPLv3.*

## No support for...
- No support for any **2D** barcodes, like QR codes.
- We only generate the 'bars' part of a barcode, without text below the barcode. If you want text of the code below the barcode, you could add it later to the output of this package. 

## Installation
Install through [composer](https://getcomposer.org/doc/00-intro.md):

```
composer require picqer/php-barcode-generator
```

If you want to generate PNG or JPG images, you need the GD library or Imagick installed on your system as well.

## Usage
Initiate the barcode generator for the output you want, then call the ->getBarcode() routine as many times as you want.

```php
<?php
require 'vendor/autoload.php';

// This will output the barcode as HTML output to display in the browser
$generator = new Picqer\Barcode\BarcodeGeneratorHTML();
echo $generator->getBarcode('081231723897', $generator::TYPE_CODE_128);
```

The `getBarcode()` method accepts the following parameters:
- `$barcode` String needed to encode in the barcode
- `$type` Type of barcode, use the constants defined in the class
- `$widthFactor` Width is based on the length of the data, with this factor you can make the barcode bars wider than default
- `$height` The total height of the barcode in pixels
- `$foregroundColor` Hex code as string, or array of RGB, of the colors of the bars (the foreground color)

Example of usage of all parameters:

```php
<?php

require 'vendor/autoload.php';

$redColor = [255, 0, 0];

$generator = new Picqer\Barcode\BarcodeGeneratorPNG();
file_put_contents('barcode.png', $generator->getBarcode('081231723897', $generator::TYPE_CODE_128, 3, 50, $redColor));
```

## Image types
```php
$generatorSVG = new Picqer\Barcode\BarcodeGeneratorSVG(); // Vector based SVG
$generatorPNG = new Picqer\Barcode\BarcodeGeneratorPNG(); // Pixel based PNG
$generatorJPG = new Picqer\Barcode\BarcodeGeneratorJPG(); // Pixel based JPG
$generatorHTML = new Picqer\Barcode\BarcodeGeneratorHTML(); // Pixel based HTML
$generatorHTML = new Picqer\Barcode\BarcodeGeneratorDynamicHTML(); // Vector based HTML
```

## Accepted barcode types
These barcode types are supported. All types support different character sets or have mandatory lengths. Please see wikipedia for supported chars and lengths per type.

Most used types are TYPE_CODE_128 and TYPE_CODE_39. Because of the best scanner support, variable length and most chars supported.

- TYPE_CODE_32 (italian pharmaceutical code 'MINSAN')
- TYPE_CODE_39
- TYPE_CODE_39_CHECKSUM
- TYPE_CODE_39E
- TYPE_CODE_39E_CHECKSUM
- TYPE_CODE_93
- TYPE_STANDARD_2_5
- TYPE_STANDARD_2_5_CHECKSUM
- TYPE_INTERLEAVED_2_5
- TYPE_INTERLEAVED_2_5_CHECKSUM
- TYPE_CODE_128
- TYPE_CODE_128_A
- TYPE_CODE_128_B
- TYPE_CODE_128_C
- TYPE_EAN_2
- TYPE_EAN_5
- TYPE_EAN_8
- TYPE_EAN_13
- TYPE_UPC_A
- TYPE_UPC_E
- TYPE_MSI
- TYPE_MSI_CHECKSUM
- TYPE_POSTNET
- TYPE_PLANET
- TYPE_RMS4CC
- TYPE_KIX
- TYPE_IMB
- TYPE_CODABAR
- TYPE_CODE_11
- TYPE_PHARMA_CODE
- TYPE_PHARMA_CODE_TWO_TRACKS

[See example images for all supported barcode types](examples.md)

## A note about PNG and JPG images
If you want to use PNG or JPG images, you need to install [Imagick](https://www.php.net/manual/en/intro.imagick.php) or the [GD library](https://www.php.net/manual/en/intro.image.php). This package will use Imagick if that is installed, or fall back to GD. If you have both installed but you want a specific method, you can use `$generator->useGd()` or `$generator->useImagick()` to force your preference.

## Examples

### Embedded PNG image in HTML
```php
$generator = new Picqer\Barcode\BarcodeGeneratorPNG();
echo '<img src="data:image/png;base64,' . base64_encode($generator->getBarcode('081231723897', $generator::TYPE_CODE_128)) . '">';
```

### Save JPG barcode to disk
```php
$generator = new Picqer\Barcode\BarcodeGeneratorJPG();
file_put_contents('barcode.jpg', $generator->getBarcode('081231723897', $generator::TYPE_CODABAR));
```

### Oneliner SVG output to disk
```php
file_put_contents('barcode.svg', (new Picqer\Barcode\BarcodeGeneratorSVG())->getBarcode('6825ME601', Picqer\Barcode\BarcodeGeneratorSVG::TYPE_KIX));
```

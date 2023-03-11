<?php

namespace Picqer\Barcode;

use Imagick;

class BarcodeGeneratorJPG extends BarcodeGeneratorPNG
{
    protected function createImagickImageObject(int $width, int $height): Imagick
    {
        $image = new Imagick();
        $image->newImage($width, $height, 'white', 'JPG');

        return $image;
    }

    protected function generateGdImage($image)
    {
        imagejpeg($image);
        imagedestroy($image);
    }
}

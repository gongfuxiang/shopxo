<?php

namespace Picqer\Barcode;

use Imagick;
use imagickdraw;
use imagickpixel;
use Picqer\Barcode\Exceptions\BarcodeException;

class BarcodeGeneratorPNG extends BarcodeGenerator
{
    protected $useImagick = true;

    public function __construct()
    {
        // Auto switch between GD and Imagick based on what is installed
        if (extension_loaded('imagick')) {
            $this->useImagick = true;
        } elseif (function_exists('imagecreate')) {
            $this->useImagick = false;
        } else {
            throw new BarcodeException('Neither gd-lib or imagick are installed!');
        }
    }

    /**
     * Force the use of Imagick image extension
     */
    public function useImagick()
    {
        $this->useImagick = true;
    }

    /**
     * Force the use of the GD image library
     */
    public function useGd()
    {
        $this->useImagick = false;
    }

    /**
     * Return a PNG image representation of barcode (requires GD or Imagick library).
     *
     * @param string $barcode code to print
     * @param string $type type of barcode:
     * @param int $widthFactor Width of a single bar element in pixels.
     * @param int $height Height of a single bar element in pixels.
     * @param array $foregroundColor RGB (0-255) foreground color for bar elements (background is transparent).
     * @return string image data or false in case of error.
     */
    public function getBarcode($barcode, $type, int $widthFactor = 2, int $height = 30, array $foregroundColor = [0, 0, 0])
    {
        $barcodeData = $this->getBarcodeData($barcode, $type);
        $width = round($barcodeData->getWidth() * $widthFactor);

        if ($this->useImagick) {
            $imagickBarsShape = new imagickdraw();
            $imagickBarsShape->setFillColor(new imagickpixel('rgb(' . implode(',', $foregroundColor) .')'));
        } else {
            $image = $this->createGdImageObject($width, $height);
            $gdForegroundColor = imagecolorallocate($image, $foregroundColor[0], $foregroundColor[1], $foregroundColor[2]);
        }

        // print bars
        $positionHorizontal = 0;
        /** @var BarcodeBar $bar */
        foreach ($barcodeData->getBars() as $bar) {
            $barWidth = round(($bar->getWidth() * $widthFactor), 3);

            if ($bar->isBar() && $barWidth > 0) {
                $y = round(($bar->getPositionVertical() * $height / $barcodeData->getHeight()), 3);
                $barHeight = round(($bar->getHeight() * $height / $barcodeData->getHeight()), 3);

                // draw a vertical bar
                if ($this->useImagick && isset($imagickBarsShape)) {
                    $imagickBarsShape->rectangle($positionHorizontal, $y, ($positionHorizontal + $barWidth - 1), ($y + $barHeight));
                } else {
                    imagefilledrectangle($image, $positionHorizontal, $y, ($positionHorizontal + $barWidth - 1), ($y + $barHeight), $gdForegroundColor);
                }
            }
            $positionHorizontal += $barWidth;
        }

        if ($this->useImagick && isset($imagickBarsShape)) {
            $image = $this->createImagickImageObject($width, $height);
            $image->drawImage($imagickBarsShape);
            return $image->getImageBlob();
        }

        ob_start();
        $this->generateGdImage($image);
        return ob_get_clean();
    }

    protected function createGdImageObject(int $width, int $height)
    {
        $image = imagecreate($width, $height);
        $colorBackground = imagecolorallocate($image, 255, 255, 255);
        imagecolortransparent($image, $colorBackground);

        return $image;
    }

    protected function createImagickImageObject(int $width, int $height): Imagick
    {
        $image = new Imagick();
        $image->newImage($width, $height, 'none', 'PNG');

        return $image;
    }

    protected function generateGdImage($image)
    {
        imagepng($image);
        imagedestroy($image);
    }
}

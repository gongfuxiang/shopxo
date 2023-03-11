<?php

namespace Picqer\Barcode;

class BarcodeGeneratorSVG extends BarcodeGenerator
{
    /**
     * Return a SVG string representation of barcode.
     *
     * @param $barcode (string) code to print
     * @param $type (const) type of barcode
     * @param $widthFactor (int) Minimum width of a single bar in user units.
     * @param $height (int) Height of barcode in user units.
     * @param $foregroundColor (string) Foreground color (in SVG format) for bar elements (background is transparent).
     * @return string SVG code.
     * @public
     */
    public function getBarcode($barcode, $type, int $widthFactor = 2, int $height = 30, string $foregroundColor = 'black')
    {
        $barcodeData = $this->getBarcodeData($barcode, $type);

        // replace table for special characters
        $repstr = ["\0" => '', '&' => '&amp;', '<' => '&lt;', '>' => '&gt;'];

        $width = round(($barcodeData->getWidth() * $widthFactor), 3);

        $svg = '<?xml version="1.0" standalone="no" ?>' . PHP_EOL;
        $svg .= '<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">' . PHP_EOL;
        $svg .= '<svg width="' . $width . '" height="' . $height . '" viewBox="0 0 ' . $width . ' ' . $height . '" version="1.1" xmlns="http://www.w3.org/2000/svg">' . PHP_EOL;
        $svg .= "\t" . '<desc>' . strtr($barcodeData->getBarcode(), $repstr) . '</desc>' . PHP_EOL;
        $svg .= "\t" . '<g id="bars" fill="' . $foregroundColor . '" stroke="none">' . PHP_EOL;

        // print bars
        $positionHorizontal = 0;
        /** @var BarcodeBar $bar */
        foreach ($barcodeData->getBars() as $bar) {
            $barWidth = round(($bar->getWidth() * $widthFactor), 3);
            $barHeight = round(($bar->getHeight() * $height / $barcodeData->getHeight()), 3);

            if ($bar->isBar() && $barWidth > 0) {
                $positionVertical = round(($bar->getPositionVertical() * $height / $barcodeData->getHeight()), 3);
                // draw a vertical bar
                $svg .= "\t\t" . '<rect x="' . $positionHorizontal . '" y="' . $positionVertical . '" width="' . $barWidth . '" height="' . $barHeight . '" />' . PHP_EOL;
            }

            $positionHorizontal += $barWidth;
        }
        $svg .= "\t</g>" . PHP_EOL;
        $svg .= '</svg>' . PHP_EOL;

        return $svg;
    }
}

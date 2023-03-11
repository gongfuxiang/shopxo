<?php

namespace Picqer\Barcode;

class Barcode
{
    protected $barcode;
    protected $width = 0;
    protected $height = 0;
    protected $bars = [];

    public function __construct(string $barcode)
    {
        $this->barcode = $barcode;
    }

    public function addBar(BarcodeBar $bar)
    {
        $this->bars[] = $bar;
        $this->width += $bar->getWidth();
        $this->height = max($this->height, $bar->getHeight());
    }

    public function getBarcode(): string
    {
        return $this->barcode;
    }

    public function getWidth(): int
    {
        return $this->width;
    }

    public function getHeight(): int
    {
        return $this->height;
    }

    public function getBars(): array
    {
        return $this->bars;
    }
}
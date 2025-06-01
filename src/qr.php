<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;

function generate_qr(string $text, string $path): void {
    $result = Builder::create()
        ->writer(new PngWriter())
        ->data($text)
        ->size(300)
        ->margin(10)
        ->build();
    $result->saveToFile($path);
}

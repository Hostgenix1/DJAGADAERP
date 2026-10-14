<?php

namespace App\Support;

use BaconQrCode\Common\ErrorCorrectionLevel;
use BaconQrCode\Encoder\Encoder;

/**
 * GD-based QR code PNG generator (data URI) for PDF rendering.
 * simple-qrcode's PNG formatter needs Imagick; this only needs GD,
 * which dompdf servers normally have.
 */
class QrPng
{
    public static function dataUri(string $data, int $scale = 5, int $margin = 2): string
    {
        $qr = Encoder::encode($data, ErrorCorrectionLevel::M());
        $matrix = $qr->getMatrix();
        $moduleCount = $matrix->getWidth();

        $px = ($moduleCount + $margin * 2) * $scale;
        $img = imagecreatetruecolor($px, $px);
        $white = imagecolorallocate($img, 255, 255, 255);
        $black = imagecolorallocate($img, 0, 0, 0);
        imagefilledrectangle($img, 0, 0, $px, $px, $white);

        for ($y = 0; $y < $moduleCount; $y++) {
            for ($x = 0; $x < $moduleCount; $x++) {
                if ($matrix->get($x, $y)) {
                    $x1 = ($x + $margin) * $scale;
                    $y1 = ($y + $margin) * $scale;
                    imagefilledrectangle($img, $x1, $y1, $x1 + $scale - 1, $y1 + $scale - 1, $black);
                }
            }
        }

        ob_start();
        imagepng($img);
        $bytes = ob_get_clean();
        imagedestroy($img);

        return 'data:image/png;base64,'.base64_encode($bytes);
    }
}

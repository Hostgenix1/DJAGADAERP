<?php

namespace App\Support;

/**
 * UAE VAT invoice QR payload (FTA TLV specification).
 * Tags: 1=Seller, 2=TRN, 3=Timestamp (ISO8601), 4=Invoice total (incl VAT), 5=VAT amount.
 * The TLV byte string is base64-encoded and QR-encoded on the invoice PDF.
 */
class UaeVatQr
{
    public static function payload(
        string $sellerName,
        string $trn,
        string $timestamp,
        string $totalWithVat,
        string $vatAmount,
    ): string {
        $fields = [
            1 => $sellerName,
            2 => $trn,
            3 => $timestamp,
            4 => $totalWithVat,
            5 => $vatAmount,
        ];

        $out = '';
        foreach ($fields as $tag => $value) {
            $value = (string) $value;
            if (strlen($value) > 255) {
                $value = substr($value, 0, 255);
            }
            $out .= chr($tag).chr(strlen($value)).$value;
        }

        return base64_encode($out);
    }
}

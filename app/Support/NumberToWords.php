<?php

namespace App\Support;

class NumberToWords
{
    private const ONES = [
        '', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine',
        'Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen',
        'Seventeen', 'Eighteen', 'Nineteen',
    ];

    private const TENS = [
        '', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety',
    ];

    private const FR_ONES = [
        '', 'Un', 'Deux', 'Trois', 'Quatre', 'Cinq', 'Six', 'Sept', 'Huit', 'Neuf',
        'Dix', 'Onze', 'Douze', 'Treize', 'Quatorze', 'Quinze', 'Seize',
        'Dix-Sept', 'Dix-Huit', 'Dix-Neuf',
    ];

    private const FR_TENS = [
        '', '', 'Vingt', 'Trente', 'Quarante', 'Cinquante', 'Soixante',
        'Soixante-Dix', 'Quatre-Vingt', 'Quatre-Vingt-Dix',
    ];

    public static function toWords(float|int $number, string $lang = 'en'): string
    {
        $whole = floor($number);
        $cents = (int) round(($number - $whole) * 100);

        $words = $lang === 'fr'
            ? self::frenchWhole((int) $whole)
            : self::englishWhole((int) $whole);

        if ($cents > 0) {
            $centsWords = $lang === 'fr'
                ? self::frenchWhole($cents)
                : self::englishWhole($cents);

            $words .= ' '.($lang === 'fr' ? 'et' : 'and').' '.$centsWords
                .' '.($lang === 'fr' ? 'Centimes' : 'Cents');
        }

        return trim($words);
    }

    private static function englishWhole(int $number): string
    {
        if ($number === 0) {
            return 'Zero';
        }

        $chunks = array_reverse(array_map('intval', str_split(str_pad((string) $number, ceil(strlen((string) $number) / 3) * 3, '0', STR_PAD_LEFT), 3)));

        $names = ['', 'Thousand', 'Million', 'Billion', 'Trillion'];
        $parts = [];

        foreach ($chunks as $i => $chunk) {
            if ($chunk === 0) {
                continue;
            }
            $text = self::englishThree($chunk);
            $scale = $names[$i] ?? '';
            $parts[] = trim($text.' '.$scale);
        }

        return implode(' ', array_reverse($parts));
    }

    private static function englishThree(int $n): string
    {
        $text = '';
        $hundreds = intdiv($n, 100);
        $rest = $n % 100;

        if ($hundreds > 0) {
            $text .= self::ONES[$hundreds].' Hundred';
            if ($rest > 0) {
                $text .= ' and ';
            }
        }

        if ($rest > 0) {
            if ($rest < 20) {
                $text .= self::ONES[$rest];
            } else {
                $text .= self::TENS[intdiv($rest, 10)];
                if ($rest % 10 > 0) {
                    $text .= '-'.self::ONES[$rest % 10];
                }
            }
        }

        return $text;
    }

    private static function frenchWhole(int $number): string
    {
        if ($number === 0) {
            return 'Zéro';
        }

        $chunks = array_reverse(array_map('intval', str_split(str_pad((string) $number, ceil(strlen((string) $number) / 3) * 3, '0', STR_PAD_LEFT), 3)));

        $names = ['', 'Mille', 'Million', 'Milliard', 'Billion'];
        $parts = [];

        foreach ($chunks as $i => $chunk) {
            if ($chunk === 0) {
                continue;
            }
            $text = self::frenchThree($chunk, $i === 1);
            $scale = $names[$i] ?? '';
            $parts[] = trim($text.' '.$scale);
        }

        return implode(' ', array_reverse($parts));
    }

    private static function frenchThree(int $n, bool $isThousand = false): string
    {
        $text = '';
        $hundreds = intdiv($n, 100);
        $rest = $n % 100;

        if ($hundreds > 0) {
            $text .= $hundreds === 1 ? 'Cent' : self::FR_ONES[$hundreds].' Cent';
            if ($rest > 0) {
                $text .= ' ';
            }
        }

        if ($rest > 0) {
            if ($rest < 20) {
                $text .= self::FR_ONES[$rest];
            } else {
                $tens = intdiv($rest, 10);
                $ones = $rest % 10;

                if ($tens === 7) {
                    $text .= $ones === 1 ? 'Soixante et Onze' : 'Soixante-'.self::FR_ONES[10 + $ones];
                } elseif ($tens === 9) {
                    $text .= 'Quatre-Vingt-'.self::FR_ONES[10 + $ones];
                } elseif ($tens === 8) {
                    $text .= 'Quatre-Vingt';
                    if ($ones > 0) {
                        $text .= '-'.self::FR_ONES[$ones];
                    }
                } else {
                    $text .= self::FR_TENS[$tens];
                    if ($ones > 0) {
                        $text .= $tens >= 2 && $ones === 1 ? ' et Un' : '-'.self::FR_ONES[$ones];
                    }
                }
            }
        }

        if ($isThousand && $n === 1) {
            return '';
        }

        return $text;
    }
}

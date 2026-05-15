<?php

namespace App\Helpers;

class NumberHelper
{
    public static function toWords(float $amount): string
    {
        $amount = round($amount, 2);
        $rupees = (int) floor($amount);
        $paise  = (int) round(($amount - $rupees) * 100);

        if ($paise > 0) {
            return 'Indian Rupee ' . self::convert($rupees) . ' and ' . self::convert($paise) . ' Paise Only';
        }

        return 'Indian Rupee ' . self::convert($rupees) . ' Only';
    }

    private static function convert(int $n): string
    {
        if ($n === 0) return 'Zero';

        $ones = [
            '', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine',
            'Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen',
            'Seventeen', 'Eighteen', 'Nineteen',
        ];
        $tens = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];

        if ($n < 20)       return $ones[$n];
        if ($n < 100)      return $tens[(int) ($n / 10)] . ($n % 10 ? ' ' . $ones[$n % 10] : '');
        if ($n < 1000)     return $ones[(int) ($n / 100)] . ' Hundred' . ($n % 100 ? ' ' . self::convert($n % 100) : '');
        if ($n < 100000)   return self::convert((int) ($n / 1000)) . ' Thousand' . ($n % 1000 ? ' ' . self::convert($n % 1000) : '');
        if ($n < 10000000) return self::convert((int) ($n / 100000)) . ' Lakh' . ($n % 100000 ? ' ' . self::convert($n % 100000) : '');

        return self::convert((int) ($n / 10000000)) . ' Crore' . ($n % 10000000 ? ' ' . self::convert($n % 10000000) : '');
    }
}
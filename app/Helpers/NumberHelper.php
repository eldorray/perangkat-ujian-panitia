<?php

namespace App\Helpers;

class NumberHelper
{
    /**
     * Convert number to Indonesian words (terbilang).
     */
    public static function terbilang(int|float $angka): string
    {
        $angka = abs($angka);
        $huruf = ['', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan', 'sepuluh', 'sebelas'];
        $temp = '';

        if ($angka < 12) {
            $temp = ' ' . $huruf[$angka];
        } elseif ($angka < 20) {
            $temp = self::terbilang($angka - 10) . ' belas';
        } elseif ($angka < 100) {
            $temp = self::terbilang($angka / 10) . ' puluh' . self::terbilang($angka % 10);
        } elseif ($angka < 200) {
            $temp = ' seratus' . self::terbilang($angka - 100);
        } elseif ($angka < 1000) {
            $temp = self::terbilang($angka / 100) . ' ratus' . self::terbilang($angka % 100);
        } elseif ($angka < 2000) {
            $temp = ' seribu' . self::terbilang($angka - 1000);
        } elseif ($angka < 1000000) {
            $temp = self::terbilang($angka / 1000) . ' ribu' . self::terbilang($angka % 1000);
        } elseif ($angka < 1000000000) {
            $temp = self::terbilang($angka / 1000000) . ' juta' . self::terbilang($angka % 1000000);
        } elseif ($angka < 1000000000000) {
            $temp = self::terbilang($angka / 1000000000) . ' miliar' . self::terbilang(fmod($angka, 1000000000));
        } elseif ($angka < 1000000000000000) {
            $temp = self::terbilang($angka / 1000000000000) . ' triliun' . self::terbilang(fmod($angka, 1000000000000));
        }

        return trim($temp);
    }

    /**
     * Format number to Indonesian Rupiah format.
     */
    public static function formatRupiah(int|float $angka, bool $withPrefix = true): string
    {
        $formatted = number_format($angka, 0, ',', '.');

        return $withPrefix ? 'Rp ' . $formatted : $formatted;
    }
}

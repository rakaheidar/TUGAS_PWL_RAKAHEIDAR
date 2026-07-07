<?php

if (! function_exists('hitung_ppn')) {
    function hitung_ppn(float $total_harga): float
    {
        return $total_harga * 0.11;
    }
}

if (! function_exists('hitung_biaya_admin')) {
    function hitung_biaya_admin(float $total_harga): float
    {
        if ($total_harga <= 20000000) {
            return $total_harga * 0.006;
        } elseif ($total_harga <= 40000000) {
            return $total_harga * 0.008;
        }
        return $total_harga * 0.01;
    }
}

if (! function_exists('hitung_diskon_voucher')) {
    function hitung_diskon_voucher(float $total_harga, ?string $voucher_code): float
    {
        $vouchers = ['FLASH10' => 0.10, 'FLASH15' => 0.15, 'MEMBER20' => 0.20];
        $code = strtoupper(trim((string) $voucher_code));
        return isset($vouchers[$code]) ? $total_harga * $vouchers[$code] : 0;
    }
}
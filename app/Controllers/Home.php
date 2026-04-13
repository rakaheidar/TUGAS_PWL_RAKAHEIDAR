<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        // Pastikan ini memanggil file v_home.php kamu
        return view('v_home'); 
    }

    // Tambahkan function ini untuk halaman produk
    public function produk()
    {
        return view('v_produk'); 
    }

    // Tambahkan function ini untuk halaman keranjang
    public function keranjang()
    {
        return view('v_keranjang'); 
    }
}
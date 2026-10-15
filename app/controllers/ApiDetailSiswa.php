<?php

/**
 * Alias route akar untuk detail siswa.
 * GET /apiDetailSiswa?nis=<NIS>
 *
 * Permintaan integrasi menyebut endpoint ini tanpa prefiks /Siswa,
 * jadi route akar ini disediakan dan meneruskan ke
 * Siswa::apiDetailSiswa(). Kedua jalur (dengan/tanpa /Siswa) sama-sama bekerja.
 */
require_once __DIR__ . '/Siswa.php';

class ApiDetailSiswa extends Controller
{
    public function index()
    {
        $siswa = new Siswa();
        $siswa->apiDetailSiswa();
    }
}

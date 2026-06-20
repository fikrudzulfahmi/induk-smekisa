<!DOCTYPE html>
<html>

<head>
    <title>Presensi-<?= htmlspecialchars($data['rombel']->nama_rombel); ?></title>
    <style>
        @page {
            margin: 1.0cm 0.7cm 1.5cm 2cm;
        }

        body {
            padding: 3px;
        }

        table,
        th,
        td {
            font-family: sans-serif;
            font-size: 11px;
            border: 1px solid black;
            border-collapse: collapse;
            vertical-align: middle;
            padding: 3px;
        }

        .kop {
            font-family: sans-serif;
            text-align: center;
            font-size: 12px;
            font-weight: bold;
        }

        .ttd {
            font-family: sans-serif;
            text-align: left;
            font-size: 12px;
        }

        .nama-siswa {
            white-space: nowrap;
            /* Mencegah teks turun baris */
            overflow: hidden;
            /* Sembunyikan teks yang meluber */
            text-overflow: ellipsis;
            /* Tampilkan "..." di akhir */
            max-width: 0;
            /* Trik agar text-overflow berfungsi di tabel */
        }

        .siswa-keluar {
            color: red;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="kop">DAFTAR HADIR SISWA TAHUN PELAJARAN <?= htmlspecialchars($data['tp']); ?></div>
    <div class="kop">SEKOLAH MENENGAH KEJURUAN ISLAM 1 BLITAR</div>

    <div style="font-weight: bold; font-family: sans-serif; font-size: 11px; text-align: left; margin-top: 40px; margin-bottom: 3px;">
        Kelas : <?= htmlspecialchars($data['rombel']->nama_rombel); ?>
        <div style="float:right">Bulan : ....................................</div>
    </div>

    <table width="100%" style="margin-top: 5px; text-align:center;">
        <thead>
            <tr>
                <th width="3%" rowspan="2">No</th>
                <th width="25%" rowspan="2">Nama</th>
                <th width="3%" rowspan="2">JK</th>
                <th width="6%" rowspan="2">NIS</th>
                <th width="3%">1</th>
                <th width="3%">2</th>
                <th width="3%">3</th>
                <th width="3%">4</th>
                <th width="3%">5</th>
                <th width="3%">6</th>
                <th width="3%">7</th>
                <th width="3%">8</th>
                <th width="3%">9</th>
                <th width="3%">10</th>
                <th width="3%">11</th>
                <th width="3%">12</th>
                <th width="3%">13</th>
                <th width="3%">14</th>
                <th width="3%">15</th>
                <th width="1%" style="border-right: 2px solid black;"></th>
                <th width="6%" colspan="3">JUMLAH</th>
            </tr>
            <tr>
                <th width="3%">16</th>
                <th width="3%">17</th>
                <th width="3%">18</th>
                <th width="3%">19</th>
                <th width="3%">20</th>
                <th width="3%">21</th>
                <th width="3%">22</th>
                <th width="3%">23</th>
                <th width="3%">24</th>
                <th width="3%">25</th>
                <th width="3%">26</th>
                <th width="3%">27</th>
                <th width="3%">28</th>
                <th width="3%">29</th>
                <th width="3%">30</th>
                <th width="3%" style="border-right: 2px solid black;">31</th>
                <th width="3%">S</th>
                <th width="3%">I</th>
                <th width="3%">A</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 1;
            $laki = 0;
            $perempuan = 0;
            // Mengganti 'while' dengan 'foreach' dari data MVC
            foreach ($data['siswa'] as $siswa) :
                $jk = ($siswa->jenis_kelamin == 'Perempuan') ? 'P' : 'L';
                if ($jk == 'L') {
                    $laki++;
                } else {
                    $perempuan++;
                }
                $nis_tampil = substr((string)$siswa->no_induk, 0, 5); // Logika pemotongan NIS

                $id_siswa = $siswa->id_induk; // pastikan field ini ADA

                $is_keluar = isset($data['map_keluar'][$id_siswa]);
                $tgl_keluar = $is_keluar
                    ? date($data['map_keluar'][$id_siswa])
                    : null;
            ?>
                <tr>
                    <td><?= $i++; ?></td>

                    <td class="nama-siswa <?= $is_keluar ? 'siswa-keluar' : ''; ?>" style="text-align:left;">
                        &nbsp;<?= htmlspecialchars($siswa->nama_siswa); ?>
                    </td>

                    <td><?= $jk; ?></td>
                    <td><?= htmlspecialchars(substr($nis_tampil, 0, 5)); ?></td>

                    <?php if ($is_keluar): ?>
                        <td colspan="15" style="text-align:center; font-weight:bold;">
                            Keluar (<?= tanggal_indo($tgl_keluar); ?>)
                        </td>
                        <td style="border-right:2px solid black;"></td>
                        <td colspan="3"></td>
                    <?php else: ?>
                        <?php for ($d = 1; $d <= 15; $d++) echo "<td></td>"; ?>
                        <td style="border-right:2px solid black;"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    <?php endif; ?>

                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <table width="30%" style="margin-top: 5px; margin-bottom:0px; border:none;">
        <tr style="border:none;">
            <td style-="text-align:center; border:1px solid black;">&nbsp;Jumlah Laki-Laki</td>
            <td style="text-align:center; border:1px solid black;" width="20%"><?= $laki; ?></td>
        </tr>
        <tr style="border:none;">
            <td style-="text-align:center; border:1px solid black;">&nbsp;Jumlah Perempuan</td>
            <td style="text-align:center; border:1px solid black;" width="20%"><?= $perempuan; ?></td>
        </tr>
    </table>

    <div class="ttd" style="margin-top: 10px; float: right;">
        <div style="margin-bottom: 5px;">Blitar, ......................................</div>
        <div style="margin-bottom: 50px;">Wali Kelas</div>
        <div><b><u><?= htmlspecialchars($data['rombel']->nama_guru); ?></u></b></div>
    </div>
</body>

</html>
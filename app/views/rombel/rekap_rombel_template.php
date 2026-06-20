<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Rekap Rombel</title>
    <style>
        @page {
            margin: 1.5cm;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
            line-height: 1.2;
            color: #000;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h4 {
            text-transform: uppercase;
            margin: 0;
            font-size: 12pt;
        }

        .header p {
            margin: 2px 0;
        }

        /* Table Standard */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 5px;
        }

        th {
            background-color: #f2f2f2;
            text-align: center;
            font-weight: bold;
            text-transform: uppercase;
        }

        /* Aligment */
        .text-center {
            text-align: center;
        }

        .text-left {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .bold {
            font-weight: bold;
        }

        /* Page Break Rule */
        .page-break {
            page-break-before: always;
        }

        /* Menghilangkan page break di halaman pertama */
        .first-page {
            page-break-before: avoid;
        }

        .subtotal-row {
            background-color: #f9f9f9;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <div class="header">
        <h4>Daftar Rombongan Belajar dan Anggota Rombel</h4>
        <p>SMK Islam 1 Blitar</p>
        <p>Tahun Pelajaran <?= htmlspecialchars($data['tp']); ?></p>
    </div>

    <?php
    $g_laki = 0;
    $g_perempuan = 0;
    $g_total = 0;
    $no = 1;
    $is_first = true;
    ?>

    <?php foreach ($data['groupedRombel'] as $tingkat => $rombels) : ?>
        <?php if (!empty($rombels)) : ?>

            <div class="<?= $is_first ? 'first-page' : 'page-break'; ?>">
                <p class="bold">TINGKAT: <?= htmlspecialchars($tingkat); ?></p>
                <table>
                    <thead>
                        <tr>
                            <th width="30">NO</th>
                            <th width="100">KELAS</th>
                            <th>KONSENTRASI KEAHLIAN</th>
                            <th width="40">L</th>
                            <th width="40">P</th>
                            <th width="50">JML</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $s_laki = 0;
                        $s_perempuan = 0;
                        $s_total = 0;
                        foreach ($rombels as $rombel) :
                            $l = $rombel->jumlah_laki ?? 0;
                            $p = $rombel->jumlah_perempuan ?? 0;
                            $t = $rombel->total_siswa;

                            $s_laki += $l;
                            $s_perempuan += $p;
                            $s_total += $t;
                        ?>
                            <tr>
                                <td class="text-center"><?= $no++; ?></td>
                                <td class="text-left"><?= htmlspecialchars($rombel->nama_rombel); ?></td>
                                <td class="text-left"><?= htmlspecialchars($rombel->nama_jurusan ?? '-'); ?></td>
                                <td class="text-center"><?= $l; ?></td>
                                <td class="text-center"><?= $p; ?></td>
                                <td class="text-center"><?= $t; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr class="subtotal-row">
                            <td colspan="3" class="text-right">JUMLAH TINGKAT <?= $tingkat; ?></td>
                            <td class="text-center"><?= $s_laki; ?></td>
                            <td class="text-center"><?= $s_perempuan; ?></td>
                            <td class="text-center"><?= $s_total; ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <?php
            $g_laki += $s_laki;
            $g_perempuan += $s_perempuan;
            $g_total += $s_total;
            $is_first = false;
            ?>
        <?php endif; ?>
    <?php endforeach; ?>

    <div style="margin-top: 30px;">
        <table style="width: 50%; margin-left: auto;">
            <tr class="bold" style="background-color: #eee;">
                <td colspan="2" class="text-center">REKAPITULASI TOTAL</td>
            </tr>
            <tr>
                <td class="text-left">Total Laki-laki</td>
                <td class="text-right"><?= $g_laki; ?></td>
            </tr>
            <tr>
                <td class="text-left">Total Perempuan</td>
                <td class="text-right"><?= $g_perempuan; ?></td>
            </tr>
            <tr class="bold">
                <td class="text-left">TOTAL KESELURUHAN</td>
                <td class="text-right"><?= $g_total; ?></td>
            </tr>
        </table>
    </div>

</body>

</html>
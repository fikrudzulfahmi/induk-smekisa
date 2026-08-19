<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['judul']; ?></title>

    <link rel="stylesheet" href="<?= BASEURL; ?>/assets/compiled/css/app.css">
    <link rel="stylesheet" href="<?= BASEURL; ?>/assets/compiled/css/app-dark.css">
    <link rel="stylesheet" href="<?= BASEURL; ?>/assets/compiled/css/iconly.css">
    <link rel="stylesheet" href="<?= BASEURL; ?>/assets/compiled/css/app.css">
    <link rel="stylesheet" href="<?= BASEURL; ?>/assets/compiled/css/app-dark.css">
    <link rel="stylesheet" href="<?= BASEURL; ?>/assets/extensions/sweetalert2/sweetalert2.min.css">
    <link rel="stylesheet" href="<?= BASEURL; ?>/assets/extensions/datatables/datatables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <link rel="icon" href="<?= BASEURL; ?>/assets/images/favicon.png" type="image/png">
    <style>
        /* --- Floating Theme Toggle Button --- */
        .theme-toggle {
            position: fixed;
            bottom: 25px;
            right: 25px;
            z-index: 1050;
            padding: 10px 15px;
            border-radius: 50px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            cursor: pointer;
            transition: all 0.3s ease;

            /* ATURAN UNTUK TEMA TERANG (DEFAULT) */
            background-color: #435ebe !important;
        }

        .theme-toggle:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
        }

        /* WARNA IKON SELALU PUTIH DI SEMUA MODE */
        .theme-toggle svg g,
        .theme-toggle svg path {
            stroke: white !important;
            fill: white !important;
        }

        /* ATURAN KHUSUS SAAT TEMA GELAP AKTIF */
        .dark .theme-toggle {
            background-color: #2d3748 !important;
            /* Latar abu-abu gelap */
            border: 1px solid #435ebe;
        }

        /* Efek transisi */
        html,
        body {
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        /* ===== MOBILE FRIENDLY: TOMBOL BURGER (menu) ===== */
        /* app.js sudah punya handler klik .burger-btn (toggle sidebar + backdrop);
           elemen ini yang selama ini hilang sehingga menu tidak bisa dibuka di HP. */
        .burger-btn {
            display: none;
            position: fixed;
            top: 14px;
            left: 14px;
            z-index: 1055;
            width: 42px;
            height: 42px;
            border: none;
            border-radius: 10px;
            background-color: #435ebe !important;
            color: #fff;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(67, 94, 190, 0.35);
        }

        .burger-btn:hover {
            background-color: #3649a8 !important;
        }

        /* Tombol tutup (X) di dalam sidebar saat terbuka di mobile */
        .sidebar-toggler.x {
            color: #6c757d;
            font-size: 1.3rem;
            line-height: 1;
            text-decoration: none;
        }

        .sidebar-toggler.x:hover {
            color: #435ebe;
        }

        @media screen and (max-width: 1199px) {
            .burger-btn {
                display: flex;
            }

            /* Kurangi padding konten agar tidak sempit di HP */
            #main {
                padding: 1rem;
            }

            .page-heading {
                margin: 0 0 1.2rem;
            }

            /* Konten tabel/statistik tidak meluber */
            .page-content .row>[class*="col-"] {
                margin-bottom: 0.75rem;
            }

            /* Tabel apa pun di dalam konten bisa digeser horizontal di HP */
            .page-content .table,
            .page-content table.table {
                display: block;
                width: 100%;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
                white-space: nowrap;
            }
        }
    </style>
</head>

<body>
    <div id="app">
        <!-- Tombol menu (hamburger) — hanya tampil di layar < 1200px -->
        <button type="button" class="burger-btn d-xl-none" id="sidebarToggleBtn" aria-label="Buka Menu">
            <i class="bi bi-list fs-3"></i>
        </button>
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

        /* Di layar mobile (<1200px) tombol darkmode digeser ke atas
           agar tidak menutupi bottom navigation di bawah layar */
        @media screen and (max-width: 1199px) {
            .theme-toggle {
                bottom: 5.6rem;
                right: 18px;
                z-index: 1035;
            }
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

        /* ===== MOBILE FRIENDLY: BOTTOM NAVIGATION + MENU SHEET ===== */
        /* Di layar <1200px sidebar disembunyikan; menu pindah ke bawah layar
           (bottom nav 4 item + tombol "Menu" untuk menu lengkap). */

        /* Sembunyikan elemen mobile secara default (desktop) */
        .mobile-bottom-nav,
        .mobile-menu-sheet,
        .mobile-menu-backdrop {
            display: none;
        }

        @media screen and (max-width: 1199px) {
            /* Sidebar desktop disembunyikan di mobile */
            #sidebar {
                display: none !important;
            }

            /* Kurangi padding konten agar tidak sempit di HP */
            #main {
                padding: 1rem 1rem 5.2rem;
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

            /* ===== Bottom Navigation Bar ===== */
            .mobile-bottom-nav {
                display: flex;
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                z-index: 1040;
                background: #ffffff;
                border-top: 1px solid #e9ecef;
                box-shadow: 0 -4px 12px rgba(0, 0, 0, 0.08);
                padding-bottom: env(safe-area-inset-bottom);
            }

            .mobile-bottom-nav .mbn-item {
                flex: 1;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                gap: 3px;
                padding: 8px 2px;
                font-size: 0.68rem;
                font-weight: 600;
                color: #6c757d;
                text-decoration: none;
                background: none;
                border: none;
                cursor: pointer;
            }

            .mobile-bottom-nav .mbn-item i {
                font-size: 1.25rem;
                line-height: 1;
            }

            .mobile-bottom-nav .mbn-item.active {
                color: #435ebe;
            }

            .mobile-bottom-nav .mbn-item:active {
                background: #eef1fb;
            }

            /* ===== Menu Sheet (bottom sheet menu lengkap) ===== */
            .mobile-menu-sheet {
                display: block;
                position: fixed;
                left: 0;
                right: 0;
                bottom: 0;
                z-index: 1060;
                max-height: 82vh;
                background: #ffffff;
                border-radius: 16px 16px 0 0;
                box-shadow: 0 -8px 30px rgba(0, 0, 0, 0.18);
                transform: translateY(105%);
                transition: transform 0.3s ease;
                overflow: hidden;
                display: flex;
                flex-direction: column;
            }

            .mobile-menu-sheet.show {
                transform: translateY(0);
            }

            .mobile-menu-sheet-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 16px 20px 10px;
                border-bottom: 1px solid #eef1fb;
                flex-shrink: 0;
            }

            .mobile-menu-sheet-header h6 {
                margin: 0;
                font-weight: 700;
                color: #435ebe;
            }

            .mobile-menu-close {
                border: none;
                background: none;
                font-size: 1.3rem;
                color: #6c757d;
                padding: 4px 6px;
                border-radius: 8px;
            }

            .mobile-menu-close:hover {
                background: #eef1fb;
                color: #435ebe;
            }

            /* Menu di dalam sheet — scroll sendiri */
            .mobile-menu-sheet .sidebar-menu {
                overflow-y: auto;
                -webkit-overflow-scrolling: touch;
                padding: 10px 12px 24px;
            }

            .mobile-menu-sheet .menu {
                padding-left: 0;
                margin-top: 0;
            }

            .mobile-menu-sheet .sidebar-title {
                padding: 0.9rem 1rem 0.4rem;
                margin: 0.4rem 0 0.3rem;
                font-size: 0.85rem;
                font-weight: 700;
                color: #25396f;
                list-style: none;
            }

            .mobile-menu-sheet .sidebar-item {
                list-style: none;
                margin-top: 0.3rem;
            }

            .mobile-menu-sheet .sidebar-link {
                display: flex;
                align-items: center;
                padding: 0.7rem 1rem;
                font-size: 0.95rem;
                font-weight: 600;
                border-radius: 0.5rem;
                color: #25396f;
                text-decoration: none;
            }

            .mobile-menu-sheet .sidebar-link i {
                margin-right: 0.9rem;
                font-size: 1.1rem;
                color: #7c8db5;
            }

            .mobile-menu-sheet .sidebar-item.active .sidebar-link {
                background: #435ebe;
                color: #fff;
            }

            .mobile-menu-sheet .sidebar-item.active .sidebar-link i {
                color: #fff;
            }

            /* ===== Backdrop ===== */
            .mobile-menu-backdrop {
                display: block;
                position: fixed;
                inset: 0;
                z-index: 1050;
                background: rgba(0, 0, 0, 0.5);
                opacity: 0;
                visibility: hidden;
                transition: opacity 0.3s ease, visibility 0.3s ease;
            }

            .mobile-menu-backdrop.show {
                opacity: 1;
                visibility: visible;
            }
        }
    </style>
</head>

<body>
    <div id="app">
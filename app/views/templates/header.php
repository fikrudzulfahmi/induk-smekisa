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
    </style>
</head>

<body>
    <div id="app">
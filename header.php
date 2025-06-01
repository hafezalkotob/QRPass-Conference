<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>فرم ثبت‌نام همایش</title>
    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="assets/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/favicon/favicon-16x16.png">
    <link rel="manifest" href="assets/favicon/site.webmanifest">
    <!--link rel="mask-icon" href="assets/favicon/safari-pinned-tab.svg" color="#5bbad5"-->
    <!--meta name="msapplication-TileColor" content="#da532c"-->
    <!--meta name="theme-color" content="#ffffff"-->
    <!-- بوت‌استرپ RTL -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css">
    <style>
        /* تنظیمات فونت ایران‌سنس */
        @font-face {
            font-family: 'iransansxv';
            src: url('<?php echo $base_url; ?>/assets/fonts/IRANSansXV.woff') format('woff-variations'),
                 url('<?php echo $base_url; ?>/assets/fonts/IRANSansXV.woff') format('woff');
            font-weight: 100 1000;
            font-display: fallback;
        }

        @font-face {
            font-family: 'iransansx';
            src: url('<?php echo $base_url; ?>/assets/fonts/IRANSansX-Regular.woff') format('woff');
        }

        @font-face {
            font-family: 'iransansx';
            src: url('a<?php echo $base_url; ?>/assets/fonts/IRANSansX-Bold.woff') format('woff');
            font-weight: 700;
        }

        /* تنظیمات پایه فونت */
        body {
            font-family: iransansx, tahoma;
        }

        @supports (font-variation-settings: normal) {
            body {
                font-family: 'iransansxv', tahoma;
            }
        }

        /* کلاس‌های کمکی برای وزن‌های مختلف */
        .fw-light {
            font-weight: 300;
            font-variation-settings: "wght" 300;
        }

        .fw-normal {
            font-weight: 400;
            font-variation-settings: "wght" 400;
        }

        .fw-bold {
            font-weight: 700;
            font-variation-settings: "wght" 700;
        }

        /* کلاس‌های کمکی برای اعداد فارسی */
        .ss02 {
            -moz-font-feature-settings: "ss02";
            -webkit-font-feature-settings: "ss02";
            font-feature-settings: "ss02";
        }

        /* کلاس‌های کمکی برای اعداد هم‌عرض */
        .ss03 {
            -moz-font-feature-settings: "ss03";
            -webkit-font-feature-settings: "ss03";
            font-feature-settings: "ss03";
        }

        /* کلاس‌های کمکی برای تراز عمودی اعداد */
        .ss04 {
            -moz-font-feature-settings: "ss04";
            -webkit-font-feature-settings: "ss04";
            font-feature-settings: "ss04";
        }

        /* استایل‌های اضافی */
        .navbar-brand {
            font-weight: bold;
            font-variation-settings: "wght" 700;
        }

        .main-content {
            min-height: calc(100vh - 160px);
            padding: 2rem 0;
        }
    </style>
</head>
<body>
    <!--nav class="navbar bg-body-tertiary">
        <div class="container">
            <a class="navbar-brand" href="#">
            <img src="assets/logo.svg" alt="Logo" width="32" height="32" class="d-inline-block align-text-top">
            دومین نمایشگاه فناوری زیبایی DIT
            </a>
        </div>
    </nav-->

    <div class="main-content">
        <div class="container"> 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Correction Territory</title>

    <link rel="icon" href="images/icon.png" type="image/x-icon">
    
    <!-- All in One SEO 4.6.7.1 - aioseo.com -->
    <meta name="robots" content="max-image-preview:large" />
    <meta name="generator" content="All in One SEO (AIOSEO) 4.6.7.1" />
    <meta property="og:locale" content="en_US" />
    <meta property="og:site_name" content="Correction Territory" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="Tracking Stocks' Gap from All-Time Highs" />
    <meta property="og:url" content="https://correctionterritory.com/" />
    <meta property="article:published_time" content="2024-05-20T20:33:00+00:00" />
    <meta property="article:modified_time" content="2024-07-14T21:13:56+00:00" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="Tracking Stocks' Gap from All-Time Highs" />

    <!-- Open Graph image for social media preview (WhatsApp, Facebook, etc.) -->
    <meta property="og:image" content="https://correctionterritory.com/images/bear.jpg" />
    <meta property="og:image:width" content="128" />
    <meta property="og:image:height" content="128" />

    <meta name="viewport" content="width=device-width, initial-scale=1" />
    
    <!-- PWA Manifest -->
    <link rel="manifest" href="manifest.json">
    
    <!-- Theme Color for Android -->
    <meta name="theme-color" content="#333333">
    
    <!-- Favicon -->
    <link rel="icon" href="images/icon.png" type="image/png">
    <link rel="apple-touch-icon" href="images/icon.jpg">
    <link rel="apple-touch-icon" sizes="192x192" href="images/icon.png">
    <link rel="apple-touch-icon" sizes="512x512" href="images/icon.png">
    
    <!-- Canonical Link -->
    <link rel="canonical" href="https://correctionterritory.com/" class="yoast-seo-meta-tag" />
    <meta property="og:locale" content="en_US" class="yoast-seo-meta-tag" />
    <meta property="og:type" content="article" class="yoast-seo-meta-tag" />
    <meta property="og:title" content="Tracking Stocks' Gap from All-Time Highs" class="yoast-seo-meta-tag" />
    <meta property="og:url" content="https://correctionterritory.com" class="yoast-seo-meta-tag" />
    <meta property="og:site_name" content="Correction Territory" class="yoast-seo-meta-tag" />
    <meta property="article:modified_time" content="2024-07-14T21:13:56+00:00" class="yoast-seo-meta-tag" />
    <meta name="twitter:card" content="summary_large_image" class="yoast-seo-meta-tag" />
    
    <!-- Organization & WebSite schema for search engines (brand name in SERPs) -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@graph": [
        {
          "@type": "Organization",
          "@id": "https://correctionterritory.com/#organization",
          "name": "Correction Territory",
          "url": "https://correctionterritory.com/"
        },
        {
          "@type": "WebSite",
          "@id": "https://correctionterritory.com/#website",
          "name": "Correction Territory",
          "url": "https://correctionterritory.com/",
          "publisher": { "@id": "https://correctionterritory.com/#organization" }
        }
      ]
    }
    </script>
    
    <!-- Google AdSense -->
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-8754771874266985" crossorigin="anonymous"></script>
    <?php define('ADSENSE_LOADED', true); ?>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    
    <!-- Google reCAPTCHA -->
    <script src="https://www.google.com/recaptcha/api.js?render=6Lf9TEcqAAAAAHau8MDGhNq4BmRG2sjiaXhaX3P9"></script>
    <style>
        #loadingicon {
            /* Initial styles for loading icon */
        }
        .hidden {
            display: none;
        }
        .fa {
            font-size: 12px;
        }
        #company-input::-webkit-input-placeholder {
            color: #7a7a7a;
        }
        #company-input::-moz-placeholder {
            color: #7a7a7a;
        }
        #company-input:-ms-input-placeholder {
            color: #7a7a7a;
        }
        #company-input::placeholder {
            color: #7a7a7a;
        }
    </style>

</head>
<div class="overlay" id="tvOverlay" onclick="closeTradingView()" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,.5); z-index:9999;">
  <div style="position:absolute; left:0; right:0; bottom:0; height:75%; background:#fff; border-radius:16px 16px 0 0;"
       onclick="event.stopPropagation()">
    <iframe id="tvFrame" style="width:100%; height:100%; border:0;"></iframe>
  </div>
</div>


<body>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
<link href="custom.css?v=<?php echo @filemtime(__DIR__ . '/custom.css'); ?>" rel="stylesheet">


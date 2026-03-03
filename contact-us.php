<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Correction Territory</title>
    <link rel="icon" href="images/icon.png" type="image/x-icon">
        <!-- All in One SEO 4.6.7.1 - aioseo.com -->
        <meta name="robots" content="max-image-preview:large" />
        <meta name="generator" content="All in One SEO (AIOSEO) 4.6.7.1" />
        <meta property="og:locale" content="en_US" />
        <meta property="og:site_name" content="Correction Territory" />
        <meta property="og:type" content="article" />
        <meta property="og:title" content="Simple Calculator for Tracking Stocks' Gap from All-Time Highs" />
        <meta property="og:url" content="https://correctionterritory.com/blogs/home/" />
        <meta property="article:published_time" content="2024-05-20T20:33:00+00:00" />
        <meta property="article:modified_time" content="2024-07-14T21:13:56+00:00" />
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" content="Simple Calculator for Tracking Stocks' Gap from All-Time Highs" />

        <meta name="viewport" content="width=device-width, initial-scale=1" />
  
        <link rel="icon" href="images/icon.png" type="image/x-icon">
        <link rel="icon" href="images/icon.png" type="image/png">
        <link rel="apple-touch-icon" href="images/icon.png">


        <link rel="canonical" href="https://correctionterritory.com/" class="yoast-seo-meta-tag" />
        <meta property="og:locale" content="en_US" class="yoast-seo-meta-tag" />
        <meta property="og:type" content="article" class="yoast-seo-meta-tag" />
        <meta property="og:title" content="Simple Calculator for Tracking Stocks' Gap from All-Time Highs" class="yoast-seo-meta-tag" />
        <meta property="og:url" content="https://correctionterritory.com" class="yoast-seo-meta-tag" />
        <meta property="og:site_name" content="Correction Territory" class="yoast-seo-meta-tag" />
        <meta property="article:modified_time" content="2024-07-14T21:13:56+00:00" class="yoast-seo-meta-tag" />
        <meta name="twitter:card" content="summary_large_image" class="yoast-seo-meta-tag" />
        <!-- Organization & WebSite schema for search engines (brand name in SERPs) -->
        <script type="application/ld+json">
        {"@context":"https://schema.org","@graph":[{"@type":"Organization","@id":"https://correctionterritory.com/#organization","name":"Correction Territory","url":"https://correctionterritory.com/"},{"@type":"WebSite","@id":"https://correctionterritory.com/#website","name":"Correction Territory","url":"https://correctionterritory.com/","publisher":{"@id":"https://correctionterritory.com/#organization"}}]}
        </script>
        <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-8754771874266985"
     crossorigin="anonymous"></script>
</head>
<body>

<?php
 include "db.php";
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script> 
<style>
    
    .bg-warning {
        background-color: #f5be19 !important;
    }
    .mynumber {
    font-size: 13px !important;
    height: 15px !important;
    width: 30px !important;
    border: 1px solid white !important;
    box-shadow: 1px 1px 1px 1px white !important;
    border-bottom: 1px solid black !important;
    margin-top: -1px !important;
    padding: 5px !important;
}
    .bg-danger, .bg-success, .bg-warning {
        color: white !important;
        font-weight: 500;
    }
    thead {
        font-size: 13px;
    }
    table {
        text-align: center;
    }
    .cname1 {
        text-align: left !important;
        vertical-align: middle !important;
    }
    .cname {
        text-align: left !important;
    position: relative; /* Establish a positioning context for absolute positioning */
    padding-right: 20px; /* Add padding to the right for the icon */
}

.heart-icon {
    position: absolute; /* Position the heart icon absolutely */
    right: 3px; /* Align it to the right of the cell */
    top: 50%; /* Center vertically */
    transform: translateY(-50%); /* Adjust to perfect vertical centering */
    color: red; /* Optional: Set the color of the heart icon */
}

    .table-striped tbody tr:nth-of-type(odd) {
            background-color: #E6E6B1;
        }
    table.dataTable tbody tr {
        background-color: #FFFFC5;
    }
    table.dataTable thead th, table.dataTable thead td {
            padding: 9px 2px;
            border-bottom: 1px solid #111;
        }
    
    table.dataTable thead .sorting {
        background-image:none;
    }    
    .table-striped tbody tr:nth-of-type(odd) {
        background-color: #FFFFC5;
    }  
      
        
    @media (max-width: 768px) {
        .hidemobile {
            display: none;
        }
        .customheading{
            padding-left:10px;
            font-size:19px;
        }
            .mynumber {
        font-size: 13px !important;
        height: 13px !important;
        width: 30px !important;
        border: 1px solid white !important;
        box-shadow: 1px 1px 1px 1px white !important;
        border-bottom: 1px solid black !important;
        margin-top: -3px !important;
        padding: 5px !important;
    }
        thead, table {
            font-size: 10px;
        }
        
        table.dataTable thead th {
            padding-right: 15px; /* Add padding to the right of header cells */
        }
        
        table.dataTable thead th.sorting:after, 
        table.dataTable thead th.sorting_asc:after, 
        table.dataTable thead th.sorting_desc:after {
            margin-left: 5px; /* Adjust the margin between text and arrow */
        }
        
    }
.table-striped>tbody>tr:nth-of-type(odd)>* {
--bs-table-accent-bg: rgb(0 0 0 / 0%)!important;
border-bottom: 1px solid #e7e7e7;
color: var(--bs-table-striped-color);
text-align: center;
}
table.dataTable tbody th, table.dataTable tbody td {
border-bottom: 1px solid #e7e7e7;

color: var(--bs-table-striped-color);
text-align: center;
}

.text{
    color: #f1f1f1;
    text-decoration:none;
}
.text:hover{
    color: #ffffff;
    cursor: pointer;
    text-decoration:none;
}
.footer-ul{
    list-style: none;
}
.footer-ul>li{
    padding-top: 12px;
    border-bottom: 1px solid #ffffff3b;
    padding-bottom: 3px;
}
h2{
    font-size:18px;
}
.mt-6 {
    margin-top: 5rem !important;
}
</style>
<?php include "singe_head.php" ?>
<div class="container" style="padding:50px 5px;">
    <h1 class="mt-6">Contact Us</h1>
    <p>info@correctionterritory.com<br/>
        +972 523 770 515<br/>   
    </div>

<style type="text/css">
    .footer-wrapper {
    padding: 0px;
    background-color: #333333;
    color: #fff;
}

.footer-widgets {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
}

.footer-widgets .widget {
    flex: 1 1 200px; /* Flex-grow, flex-shrink, flex-basis */
    margin: 10px;
}

.widget-title {
    font-size: 1.2em;
    margin-bottom: 10px;
}

.is-divider {
    margin: 10px 0;
}

.footer .col-inner {
    margin: 0;
    padding: 0;
}

.footer-primary {
    text-align: center;
    margin-top: 20px;
}

@media (max-width: 767px) {
    .footer-widgets {
        flex-direction: column;
        align-items: center;
    }

    .footer-widgets .widget {
        width: 100%;
        max-width: 400px;
    }

    .widget-title {
        text-align: center;
    }
}

@media (max-width: 480px) {
    .footer-widgets .widget {
        padding: 10px;
    }

    .widget-title {
        font-size: 1em;
    }

    .is-divider {
        margin: 5px 0;
    }
}

.absolute-footer {
    text-align: center;
    padding: 7px 0;
    background-color: #111;
}

.back-to-top {
    display: none; /* Initially hidden */
}

@media (min-width: 768px) {
    .back-to-top {
        display: block;
    }
}

</style>

<?php include "footer.php"; ?>
</body>
</html>

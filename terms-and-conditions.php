<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Correction Territory</title>
    <link rel="icon" href="images/icon.png" type="image/x-icon">
        <!-- All in One SEO 4.6.7.1 - aioseo.com -->
        <meta name="robots" content="max-image-preview:large" />
        <meta name="generator" content="All in One SEO (AIOSEO) 4.6.7.1" />
        <meta property="og:locale" content="en_US" />
        <meta property="og:site_name" content="Correction Territory - Invest Simply" />
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
    <h1 class="mt-6">Terms and Conditions</h1>
    
    <h2>Agreements</h2>
    <p>Welcome to Correction Territory! These agreements frame the guidelines and guidelines for the utilization of Correction Territory, situated at <a href="https://correctionterritory.com/">Correction Territory</a>. By getting to this site, we expect you to acknowledge these arrangements. Try not to keep on utilizing Correction Territory on the off chance that you don't consent to take the entirety of the agreements expressed on this page.</p>
    
    <h2>Qualification</h2>
    <p>By utilizing our administrations, you address and warrant that you are somewhere around 18 years of age and are lawfully equipped for going into authoritative agreements. You likewise consent to agree with every single material regulation and guideline, including those connected with exchanging and protections.</p>
    
    <h2>Protected Innovation Privileges</h2>
    <p>Except if generally expressed, Correction Territory and additionally its licensors own the protected innovation privileges for all material on Correction Territory. All protected innovation freedoms are saved. You might get to this from Correction Territory for your very own utilization exposed to limitations set in these agreements.</p>
    
    <h2>Account Enrollment</h2>
    <p>To get to specific highlights of the site, you should enroll for a record. You consent to give exact, current, and complete data during the enrollment cycle and to refresh it to keep it precise and complete.</p>
    
    <h2>Exchanging Dangers</h2>
    <p>Exchanging implies a critical gamble and can bring about a deficiency of your contributed capital. You perceive and agree that you are aware of the risks suggested in trading, and you should simply trade with capital that you can tolerate losing.</p>
    
    <h2>Limitations</h2>
    <p>You are explicitly confined from the entirety of the accompanying:</p>
    <ul>
        <li>Distributing any site material in some other media</li>
        <li>Selling, sub licensing, or potentially in any case commercializing any site material</li>
        <li>Openly performing or potentially showing any site material</li>
        <li>Involving this site in any capacity that is or might be harming to this site</li>
        <li>Including this site in any way that impacts client permission to this site</li>
        <li>Utilizing this site in opposition to relevant regulations and guidelines, or in any capacity that might harm the site, or to any individual or business entity</li>
        <li>Taking part in any information mining, information gathering, information removing, or some other comparative movement according to this site</li>
        <li>Utilizing this site to participate in any publicizing or advertising</li>
    </ul>
    
    <h2>Security</h2>
    <p>Your security means a lot to us. Kindly read our Security Strategy to comprehend how we gather, use, and safeguard your own data.</p>
    
    <h2>No Monetary Counsel</h2>
    <p>All data gave on this site is to enlightening inspirations just and ought not be viewed as monetary guidance. You ought to talk with a certified monetary consultant prior to settling on any venture choices.</p>
    
    <h2>Impediment of Obligation</h2>
    <p>In no occasion will Correction Territory, nor any of its officials, chiefs, and representatives, be expected to take responsibility for anything emerging out of or in any capacity associated with your use of this site, whether such gamble is under arrangement. Correction Territory including its officials, chiefs, and representatives will not be expected to take responsibility for any roundabout, considerable, or extraordinary obligation emerging out of or in any capacity connected with your utilization of this site.</p>
    
    <h2>Reimbursement</h2>
    <p>You thus repay to the furthest reaches Correction Territory from and against any as well as all liabilities, costs, requests, reasons for activity, harms, and costs emerging in any capacity.</p>
    
    <h2>Severability</h2>
    <p>Assuming any arrangement of these Terms is viewed as invalid under any material regulation, such arrangements will be deleted without influencing the excess arrangements in this.</p>
    
    <h2>Variety of Terms</h2>
    <p>Correction Territory is allowed to change these Terms whenever as it sees fit, and by utilizing this site you are supposed to survey these Terms consistently.</p>
    
    <h2>Task</h2>
    <p>Correction Territory is permitted to allot, move, and subcontract its privileges and additional commitments under these Terms with practically no warning. Be that as it may, you are not permitted to allow, move, or subcontract any of your privileges as well as commitments under these Terms.</p>
    
    <h2>Whole Understanding</h2>
    <p>These Terms comprise the whole arrangement between Correction Territory and your connection to your utilization of this site and override every single earlier arrangement and understanding.</p>

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

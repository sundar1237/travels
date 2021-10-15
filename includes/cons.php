<?php
ob_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
include 'funcs/db.php';

define("NO_OF_AIRLINES_PER_PAGE", "200");
define("NO_OF_AIRPORTS_PER_PAGE", "500");
define("NO_OF_INV_PER_PAGE", "10");
define("NO_OF_CUSTOMER_PER_PAGE", "100");

define("MAIN_TITLE", "Saran Solutions");
define("MAIN_LOGO_PATH", "images/logo.png");
#address parameters
define("MAIN_ADDRESS_1", "Fischermättelistrasse 5");
define("MAIN_ADDRESS_2", "3008 Bern");

define("MAIN_CONTACT_NUMBER_1", "+41 31 557 23 23");
define("MAIN_CONTACT_NUMBER_2", "+41 79 414 70 67");


define("MAIN_EMAIL", "info@fimmoag.com");
define("MAIN_WEBSITE_ADDRESS", "www.fimmoag.com");

#mail parameters
define("SEND_MAIL", True);
define("SMTP_HOST", "lx16.hoststar.hosting");
define("SMTP_PORT", "587");
define("MAIL_FROM_ADDRESS", "info@fimmoag.com");
define("MAIL_TO_ADDRESS", "info@fimmoag.com");

define("FOOT_MSG", 'Rathusstr, 63,CH – 4410 LIESTAL, Tel: 061 272 23 01 Fax: 061 272 23 04 Mobile: 076 570 50 03
www.kayathri.ch info@kayathri.ch Credit Suisse IBAN: CH85 0483 5172 4580 6100 0');


define("PDF_FOOTER_SARAN_SOLUTIONS", '<div><p style="margin-left:70%;font-size: 8pt;">Developed By <font style="font-style:italic;text-decoration: underline;">www.saransolutions.in</font></p></div>');

function getMeta(){
    return '<meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="author" content="saransolutions.ch">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="generator" content="Jekyll v4.1.1">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">';
}

function getLinksFavICon(){
    return '
	<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
	<link rel="icon" href="/favicon.ico" type="image/x-icon">
	<link rel="apple-touch-icon" sizes="57x57" href="/apple-icon-57x57.png">
<link rel="apple-touch-icon" sizes="60x60" href="/apple-icon-60x60.png">
<link rel="apple-touch-icon" sizes="72x72" href="/apple-icon-72x72.png">
<link rel="apple-touch-icon" sizes="76x76" href="/apple-icon-76x76.png">
<link rel="apple-touch-icon" sizes="114x114" href="/apple-icon-114x114.png">
<link rel="apple-touch-icon" sizes="120x120" href="/apple-icon-120x120.png">
<link rel="apple-touch-icon" sizes="144x144" href="/apple-icon-144x144.png">
<link rel="apple-touch-icon" sizes="152x152" href="/apple-icon-152x152.png">
<link rel="apple-touch-icon" sizes="180x180" href="/apple-icon-180x180.png">
<link rel="icon" type="image/png" sizes="192x192"  href="/android-icon-192x192.png">
<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="96x96" href="/favicon-96x96.png">
<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
<link rel="manifest" href="/manifest.json">';
}

function getCssLinks(){
    return '
<link href="css/font-awesome.min.css" rel="stylesheet">
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/select2.min.css" rel="stylesheet" />


<!--<link href="css/carousel.css" rel="stylesheet">
<link href="css/blog.css" rel="stylesheet">-->';
}

function getHead($title,$customLinks,$directStyle){
    return getMeta().'<title> '.MAIN_TITLE.' | '.$title.'</title>'.getLinksFavICon().''.$directStyle.'
    </style>
    <!-- Custom styles for this template -->
'.getCssLinks().$customLinks;
    
}


function getNavigationMenu(){
    return '<header>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="/"><img class="img-fluid" src="images/logo_saran.png"/></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
    <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
      <li class="nav-item active">
        <a class="nav-link" href="index.php">Home <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="customers.php">Customers</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="reports.php">Reports</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="index.php?action=view_airports">Airports</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="index.php?action=view_airlines">Airlines</a>
      </li>
    </ul>    
  </div>
</nav>
</header>';
}

function getFooter(){
    return '<!-- FOOTER -->
  <footer class="container">
    <p class="float-right"><a href="#">Back to top</a></p>
    <p>&copy; Saran Solutions CH. &middot; <a href="#">Privacy</a> &middot; <a href="#">Terms</a></p>
  </footer>
<!-- FOOTER -->';
}

function getJavaScript(){
    return '<script src="js/jquery-3.5.1.min.js"></script>
	<script src="js/typeahead.bundle.js" type="text/javascript"></script>
	<script src="js/jquery.cookie.min.js"></script>
	<script src="js/popper.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
  <script src="js/select2.min.js"></script>      
';
}

define("PDF_HEAD_TEMPLATE","<head>
<style>
body {font-family: sans-serif;
    font-size: 10pt;
}
    
#custTable1 {
    border-collapse: separate;
    border-spacing: 0;
    min-width: 350px;
    border-top: 1px solid #bbb;
}
#custTable1 tr th,
#custTable1 tr td {
    border-right: 1px solid #bbb;
    border-bottom: 1px solid #bbb;
    padding: 5px;
}
#custTable1 tr th:first-child,
#custTable1 tr td:first-child {
    border-left: 1px solid #bbb;
}
#custTable1 tr th {
    background: #eee;
    border-top: 1px solid #bbb;
    text-align: left;
}
    
/* top-left border-radius */
#custTable1 tr:first-child th:first-child {
    border-top-left-radius: 6px;
}
    
/* top-right border-radius */
#custTable1 tr:first-child th:last-child {
    border-top-right-radius: 6px;
}
    
/* bottom-left border-radius */
#custTable1 tr:last-child td:first-child {
    border-bottom-left-radius: 6px;
}
    
/* bottom-right border-radius */
#custTable1 tr:last-child td:last-child {
    border-bottom-right-radius: 6px;
}
    
</style>
</head>");


?>
<!DOCTYPE html>
<!--[if lt IE 7]> <html class="lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]> <html class="lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]> <html class="lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html lang="en"> <!--<![endif]-->
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title>OMB API</title>
  <link href='http://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
  <link rel="stylesheet" href="css/style.css" />
  <!--[if lt IE 9]><script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->

  <!-- Select2 -->
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
  <link rel="stylesheet" href="css/select2.css" />
  <script src="lib/select2.min.js"></script>
  <script>$(document).ready(function() { $('.munisList').select2(); });</script>
</head>
<body>

<!-- Includes and functions -->
<?php
    include_once('lib/config.php');
    include_once('lib/functions.php');
?>

<header>
    <h1>OMB API</h1>
    <h2>Advanced search and better structured data<br />for the Ontario Municipal Board</h2>
</header>
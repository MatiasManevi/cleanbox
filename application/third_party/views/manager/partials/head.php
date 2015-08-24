<?php
/*
  Document   : head
  Author     : Rodrigo E. Torres
  Sobrelaweb Web Developer
  rtorres@sobrelaweb.com | torresrodrigoe@gmail.com
 */
?>

<meta name="description" content="SubetealaWeb | Manager"/>
<meta name="keywords" content="SubetealaWeb | Manager"/>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link rel="shortcut icon" href="<?= base_url() ?>favicon.ico"/>
<title>SubetealaWeb | Manager</title>

<link rel="stylesheet" href="<?= asset_url() ?>css/back.css?<?=filemtime('application/assets/css/back.css')?>"/>
<link rel="stylesheet" href="<?= base_url() ?>plugins/uploadify/uploadify.css"/>
<link rel="stylesheet" href="<?= base_url() ?>plugins/jquery/jquery-ui-1.8.16.custom.css"/>

<script type="text/javascript" src="<?= base_url() ?>plugins/jquery/jquery-1.7.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>plugins/jquery/jquery-ui-1.8.16.custom.min.js"></script>
<script type="text/javascript" src="<?= asset_url() ?>js/back_functions.js?<?=filemtime('application/assets/js/back_functions.css')?>"></script>
<script type="text/javascript" src="<?= base_url() ?>plugins/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="<?= base_url() ?>plugins/uploadify/swfobject.js"></script>
<script type="text/javascript" src="<?= base_url() ?>plugins/uploadify/jquery.uploadify.v2.1.4.min.js"></script>

<script type="text/javascript">
    var LOADING_HTML='<div id="loading"><div class="shadow"></div><img src="<?= img_url() ?>ajax-loader.gif"  alt="loading..."/></div>';
</script>
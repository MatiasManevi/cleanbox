<?php
/*
  Document   : sidebar
  Author     : Rodrigo E. Torres
  Sobrelaweb Web Developer
  rtorres@sobrelaweb.com | torresrodrigoe@gmail.com
 */
?>
<div class="logo_container">
    <a href="<?= site_url(""); ?>" class="logo"></a>
</div>
<div class="side_menu">
    <ul>
        <li onclick="load_section(this,'<?= site_url('manager/section/home') ?>')">
            Vista usuario
        </li>
        <li onclick="load_section(this,'<?= site_url('manager/users') ?>')">
            Usuarios
        </li>
        
    </ul>
</div>
<div class="logout">
    <a href="<?= site_url("manager/logout"); ?>">Cerrar Sesión</a>
</div>

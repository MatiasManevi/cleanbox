<?php
/*
  Document   : home
  Created on : 16-dic-2010, 11:10:26
  Author     : Rodrigo E. Torres
  Sobrelaweb Web Developer
  rtorres@sobrelaweb.com | torresrodrigoe@gmail.com
 */
?>
<h2>Noticias/Eventos</h2>


<div class="msg_display"></div>
<div class="actions_container">
    <a href="javascript:;" onclick="$('.addedit_form').slideToggle()">Agregar</a>
</div>
<!--<form action="<?= site_url('manager/search_new') ?>" onsubmit="submit_search(this); return false;"  method="post" class="search_form submit_addedit">
    <div class="field_search">
        <label>Search</label>
        <input type="text" name="search" value="<?= isset($searched) ? $searched : '' ?>"/>
        <button>Go</button>
    </div>

</form>-->

<?
echo $form;
if (isset($searched)) {
 ?>
    <b><p class="searched">Search results: <?= $searched; ?></p></b>
<? } else { ?>

<? } ?>
<table>
    <tr>
        <th>Título</th>
        <th>Contenido</th>
        <th>Activo</th>
        <th>Editado</th>
        <th>Acciones</th>
    </tr>
    <?
    if ($news_list->num_rows() > 0) {
        foreach ($news_list->result() as $row) {
            echo '<tr class="reg_' . $row->news_id . '">';
            echo '<td>' . $row->news_title_es . '</td>';
            echo '<td>' . substr($row->news_content_es, 0, 200) . '</td>';
            echo '<td>' . ($row->news_activated ? 'Enabled' : '-') . '</td>';
            echo '<td>' . date('m-d-Y G:m',  strtotime($row->news_edited)) . '</td>';
            echo '<td>';
            echo '<a href="javascript:;" onclick="load_edit(\'' . site_url('manager/load_edit_new/' . $row->news_id) . '\')">Edit</a> | ';
            echo '<a href="javascript:;" onclick="del(\'' . $row->news_id . '\',\'' . site_url('manager/del_new/' . $row->news_id) . '\')">Borrar</a>';
            echo '</td>';
            echo '</tr>';
        }
    } else {
        echo '<tr><td>No se encontraron noticias</td></tr>';
    }
    ?>
</table>
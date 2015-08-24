<?php
/*
  Document   : gallery
  Author     : Rodrigo E. Torres
  Sobrelaweb Web Developer
  rtorres@sobrelaweb.com | torresrodrigoe@gmail.com
 */
?>
<h2>Galería de fotos</h2>

<div class="actions_container">
    <a href="javascript:;" onclick="$('.addedit_form').slideToggle()">Agregar</a>
</div>

<?
echo $form;
?>

<table>
    <tr>
        <th>Título</th>
        <th>Archivo</th>
        <th>Estado</th>
        <th>Acciones</th>
    </tr>
<?
if ($gallery_list->num_rows() > 0) {
    foreach ($gallery_list->result() as $row) {
        echo '<tr class="reg_' . $row->gall_id . '">';
        echo '<td>' . $row->gall_name_es . '</td>';
        echo '<td> <img src="'. img_url().'gallery/thumbs/'. $row->gall_image . '" alt="img gallery" /></td>';
        echo '<td>' . ($row->gall_enabled ? 'Activo' : '-') . '</td>';
        echo '<td>';
        echo '<a href="javascript:;" onclick="load_edit(\'' . site_url('manager/load_edit_gall/' . $row->gall_id) . '\')">Editar</a> | ';
        echo '<a href="javascript:;" onclick="del(\'' . $row->gall_id . '\',\'' . site_url('manager/del_gallery/' . $row->gall_id) . '\')">Borrar</a>';
        echo '</td>';
        echo '</tr>';
    }
} else {
    echo '<tr><td>No hay imagenes</td></tr>';
}
?>
</table>

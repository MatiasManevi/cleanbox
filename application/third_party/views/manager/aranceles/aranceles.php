<div class="contenedor_centro">
    <h2><?= t('Aranceles') ?></h2>
    <div class="actions_container">
        <a href="javascript:;" onclick="load_edit('<?= site_url('manager/load_edit_aranceles') ?>')"><span class="icon-plus"></span>Agregar</a>
    </div>


    <table class="table table-hover">
        <tr>    
            <th>Categoria</th>
            <th>Precio</th>
            <th>Acciones</th>
        </tr>
        <?
        if ($aranceles->num_rows() > 0) {
            foreach ($aranceles->result() as $row) {
                echo '<tr class="reg_' . $row->aran_id . '">';
                echo '<td>' . $row->aran_cate . '</td>';
                echo '<td> $ ' . $row->aran_price . '</td>';
                echo '<td>';
                echo '<a href="javascript:;" class="icon-edit" onclick="load_edit(\'' . site_url('manager/load_edit_aranceles/' . $row->aran_id) . '\')"></a> | ';
                echo '<a href="javascript:;" class="icon-trash" onclick="del(\'' . $row->aran_id . '\',\'' . site_url('manager/del_aranceles/' . $row->aran_id) . '\')"></a> | ';
                echo '</tr>';
            }
        } else {
            echo '<tr><td colspan="100%">' . t('No records found.') . '</td></tr>';
        }
        ?>
    </table>



</div>

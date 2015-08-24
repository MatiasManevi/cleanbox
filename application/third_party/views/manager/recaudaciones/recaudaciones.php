<div class="contenedor_centro">
    <h2><?= t('Recaudaciones') ?></h2>
    <div class="actions_container">
        <a href="javascript:;" onclick="load_edit('<?= site_url('manager/load_edit_recaudacion') ?>')"><span class="icon-plus"></span>Agregar</a>
    </div>


    <table class="table table-hover">
        <tr>    
            <th>Año</th>
            <th>Recaudado</th>
            <th>Acciones</th>
        </tr>
        <?
        if ($recaudaciones->num_rows() > 0) {
            foreach ($recaudaciones->result() as $row) {
                echo '<tr class="reg_' . $row->rec_id . '">';
                echo '<td>' . $row->rec_ano . '</td>';
                echo '<td>' . '$ '.$row->rec_monto . '</td>';
                echo '<td>';
                echo '<a href="javascript:;" class="icon-edit" onclick="load_edit(\'' . site_url('manager/load_edit_recaudacion/' . $row->rec_id) . '\')"></a> | ';
                echo '<a href="javascript:;" class="icon-trash" onclick="del(\'' . $row->rec_id . '\',\'' . site_url('manager/del_recaudacion/' . $row->rec_id) . '\')"></a> | ';
                echo '</tr>';
            }
        } else {
            echo '<tr><td colspan="100%">' . t('No records found.') . '</td></tr>';
        }
        ?>
    </table>



</div>

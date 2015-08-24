<div class="contenedor_centro">
    <h2><?= t('Arbitros') ?></h2>
    <div class="actions_container">
        <a href="javascript:;" onclick="load_edit('<?= site_url('manager/load_edit_arbitros') ?>')"><span class="icon-plus"></span>Agregar</a>
    </div>


    <table class="table table-hover">
        <tr>    
            <th>Nombre</th>
            <th>Acciones</th>
        </tr>
        <?
        if ($arbitros->num_rows() > 0) {
            foreach ($arbitros->result() as $row) {
                echo '<tr class="reg_' . $row->arb_id . '">';
                echo '<td>' . $row->arb_name . '</td>';
                echo '<td>';
                echo '<a href="javascript:;" class="icon-print" onclick="request_post(\'' . site_url('manager/informe_juez/' . $row->arb_id) . '\')"></a> | ';
                echo '<a href="javascript:;" class="icon-edit" onclick="load_edit(\'' . site_url('manager/load_edit_arbitros/' . $row->arb_id) . '\')"></a> | ';
                echo '<a href="javascript:;" class="icon-trash" onclick="del(\'' . $row->arb_id . '\',\'' . site_url('manager/del_arbitros/' . $row->arb_id) . '\')"></a> | ';
                echo '</tr>';
            }
        } else {
            echo '<tr><td colspan="100%">' . t('No records found.') . '</td></tr>';
        }
        ?>
    </table>
   



</div>

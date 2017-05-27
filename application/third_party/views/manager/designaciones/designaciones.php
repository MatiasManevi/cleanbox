<div class="contenedor_centro">
    <h2><?= t('Designaciones') ?></h2>
    <div class="actions_container">
        <a href="javascript:;" onclick="load_edit('<?= site_url('manager/load_edit_desig') ?>')"><span class="icon-plus"></span>Agregar</a>
    </div>


    <table class="table table-hover">
        <tr>    
            <th>Fecha</th>
            <th>Fecha Encuentro</th>
            <th>Local</th>
            <th>Visita</th>
            <th>10%</th>
            <th>Acciones</th>
        </tr>
        <?
        if ($designaciones->num_rows() > 0) {
            foreach ($designaciones->result() as $row) {
                echo '<tr class="reg_' . $row->des_id . '">';
                echo '<td>' . $row->des_fecha . '</td>';
                echo '<td>' . $row->des_date . '</td>';
                echo '<td> ' . $row->des_local . '</td>';
                echo '<td> ' . $row->des_visita . '</td>';
                echo '<td> ' . ($row->des_pagado == 1 ? 'Pagado' : 'No Pagado') . '</td>';
                echo '<td>';
                echo '<a href="javascript:;" class="icon-print" onclick="request_post(\'' . site_url('manager/informe_designacion/' . $row->des_id) . '\')"></a> | ';
                echo '<a href="javascript:;" class="icon-edit" onclick="load_edit(\'' . site_url('manager/load_edit_desig/' . $row->des_id) . '\')"></a> | ';
                echo '<a href="javascript:;" class="icon-trash" onclick="del(\'' . $row->des_id . '\',\'' . site_url('manager/del_desig/' . $row->des_id) . '\')"></a> | ';
                echo '</tr>';
            }
        } else {
            echo '<tr><td colspan="100%">' . t('No records found.') . '</td></tr>';
        }
        ?>
    </table>



</div>

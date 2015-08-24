<div class="contenedor_centro">
    <style>
        .inactive {
            pointer-events: none;
            cursor: default;
        }
    </style>

    <h2><?= t('Usuarios') ?></h2>

    <label style="clear: both;font-size: 15.2px;">Unicamente el usuario admin puede crear, modificar y dar de bajas todas las cuentas. Los demas usuarios
        solo pueden gestionar la informacion de su propia cuenta</label>

    <div class="actions_container">
        <? if ($this->session->userdata('username') == 'admin') { ?>
            <a href="javascript:;" onclick="load_edit('<?= site_url('manager/load_edit_users') ?>')"><span class="glyphicon glyphicon-plus"></span>Agregar</a>
        <? } ?>
    </div>
    <table class="table table-hover">
        <tr>    
            <th>Nombre</th>
            <th>Acciones</th>
        </tr>
        <?
        if ($this->session->userdata('username') == 'admin') {
            if ($users->num_rows() > 0) {
                foreach ($users->result() as $row) {
                    echo '<tr class="reg_' . $row->id . '">';
                    echo '<td>' . $row->username . '</td>';
                    echo '<td>';
                    echo '<a href="javascript:;" class="glyphicon glyphicon-edit" onclick="load_edit(\'' . site_url('manager/load_edit_users/' . $row->id) . '\')"></a> | ';
                    echo '<a href="javascript:;" class="glyphicon glyphicon-trash" onclick="del(\'' . $row->id . '\',\'' . site_url('manager/del_user/' . $row->id) . '\')"></a>  ';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="100%">' . t('No se encontraron resultados.') . '</td></tr>';
            }
        } else {
            if ($users->num_rows() > 0) {
                foreach ($users->result() as $row) {
                    echo '<tr class="reg_' . $row->id . '">';
                    echo '<td>' . $row->username . '</td>';
                    echo '<td>';
                    if ($this->session->userdata('username') == $row->username) {
                        echo '<a href="javascript:;" class="glyphicon glyphicon-edit" onclick="load_edit(\'' . site_url('manager/load_edit_users/' . $row->id) . '\')"></a> | ';
                        echo '<a href="javascript:;" class="glyphicon glyphicon-trash" onclick="del(\'' . $row->id . '\',\'' . site_url('manager/del_user/' . $row->id) . '\')"></a>  ';
                    } else {
                        echo '<a href="javascript:;" class="glyphicon glyphicon-edit inactive" onclick="load_edit(\'' . site_url('manager/load_edit_users/' . $row->id) . '\')"></a> | ';
                        echo '<a href="javascript:;" class="glyphicon glyphicon-trash inactive" onclick="del(\'' . $row->id . '\',\'' . site_url('manager/del_user/' . $row->id) . '\')"></a>  ';
                    }
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="100%">' . t('No se encontraron resultados.') . '</td></tr>';
            }
        }
        ?>
    </table>

</div>
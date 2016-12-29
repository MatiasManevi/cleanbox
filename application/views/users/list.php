<table class="table table-hover _table _man_users_table">
    <tr>    
        <th>Nombre</th>
        <th>Acciones</th>
    </tr>
    <?php     
    if (count($users)) {
        foreach ($users as $row) {
            ?>
            <tr class="_reg_entity_<?php echo $row['id']; ?>">
                <td><?php echo $row['username']; ?></td>
                <td>
                    <?php if ($this->session->userdata('username') == 'admin') { ?>
                        <a title="Editar" onclick="general_scripts.loadEntityToEdit(<?php echo $row['id']; ?>, 'man_users', 'id');" href="javascript:;" class="glyphicon glyphicon-edit"></a>
                        <a title="Eliminar" onclick="modals.deleteEntityModal(<?php echo $row['id']; ?>, 'man_users', 'id', 'usuario');" href="javascript:;" class="glyphicon glyphicon-trash"></a>
                    <?php } elseif ($this->session->userdata('username') == $row['username']) { ?>
                        <a title="Editar" onclick="general_scripts.loadEntityToEdit(<?php echo $row['id']; ?>, 'man_users', 'id');" href="javascript:;" class="glyphicon glyphicon-edit"></a>
                    <?php }
                    ?>
                </td>
            </tr>
            <?php
        }
    } else {
        ?>
        <tr class="_no_records"><td colspan="100%"> No se encontraron registros </td></tr>
    <?php } ?>
</table>
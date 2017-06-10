<table class="table table-hover _table _comentarios_table">
    <tr>    
        <th>Propietario</th>
        <th>Fecha del Comentario</th>
        <th>Comentario</th>
        <th>Domicilio</th>
        <th>Acciones</th>
    </tr>
    <?php
    if (count($comentaries)) {
        foreach ($comentaries as $row) {
            ?>
            <tr class="_reg_entity_<?php echo $row['com_id']; ?>">
                <td><?php echo $row['com_prop']; ?></td>
                <td><?php echo $row['com_date']; ?></td>
                <td><?php echo substr($row['com_com'], 0, 60) . ' [...]'; ?></td>
                <td><?php echo $row['com_dom']; ?></td>
                <td>
                    <a title="Editar" onclick="general_scripts.loadEntityToEdit(<?php echo $row['com_id']; ?>, 'comentarios', 'com_id');" href="javascript:;" class="glyphicon glyphicon-edit"></a>
                    <a title="Eliminar" onclick="modals.deleteEntityModal(<?php echo $row['com_id']; ?>, 'comentarios', 'com_id', 'comentario');" href="javascript:;" class="glyphicon glyphicon-trash"></a>
                </td>
            </tr>
            <?php
        }
    } else {
        ?>
        <tr class="_no_records"><td colspan="100%"> No se encontraron registros </td></tr>
    <?php } ?>
</table>
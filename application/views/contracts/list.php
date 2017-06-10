<table class="table table-hover _table _contratos_table">
    <tr>    
        <th>Propietario</th>
        <th>Inquilino</th>
        <th>Tipo</th>
        <th>IVA/Alquiler</th>
        <th>IVA/Honorarios</th>
        <th>Activo</th>
        <th>Acciones</th>
    </tr>
    <?php
    if (count($contracts)) {
        foreach ($contracts as $row) {
            ?>
            <tr class="_reg_entity_<?php echo $row['con_id']; ?>">
                <td><?php echo $row['con_prop']; ?></td>
                <td><?php echo $row['con_inq']; ?></td>
                <td><?php echo $row['con_tipo']; ?></td>
                <td><?php echo $row['con_iva_alq']; ?></td>
                <td><?php echo $row['con_iva']; ?></td>
                <td><?php echo ($row['con_enabled'] == 1 ? 'Si' : 'No'); ?></td>
                <td>
                    <a title="Editar" onclick="general_scripts.loadEntityToEdit(<?php echo $row['con_id']; ?>, 'contratos', 'con_id');" href="javascript:;" class="glyphicon glyphicon-edit"></a>
                    <a title="Eliminar" onclick="modals.deleteEntityModal(<?php echo $row['con_id']; ?>, 'contratos', 'con_id', 'contrato');" href="javascript:;" class="glyphicon glyphicon-trash"></a>
                </td>
            </tr>
            <?php
        }
    } else {
        ?>
        <tr class="_no_records"><td colspan="100%"> No se encontraron registros </td></tr>
    <?php } ?>
</table>

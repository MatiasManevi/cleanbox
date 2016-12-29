<table class="table table-hover _table _propiedades_table">
    <tr>    
        <th>Propietario</th>
        <th>Domicilio</th>
        <th>En contrato con</th>     
        <th>Acciones</th>
    </tr>
    <?php
    if (count($properties)) {
        foreach ($properties as $row) {
            ?>
            <tr class="_reg_entity_<?php echo $row['prop_id']; ?>">
                <td><?php echo $row['prop_prop']; ?></td>
                <td><?php echo $row['prop_dom']; ?></td>
                <td><?php echo $row['prop_contrato_vigente'] != '' ? $row['prop_contrato_vigente'] : 'Libre'; ?></td>
                <td>
                    <a title="Editar" onclick="general_scripts.loadEntityToEdit(<?php echo $row['prop_id']; ?>, 'propiedades', 'prop_id');" href="javascript:;" class="glyphicon glyphicon-edit"></a>
                    <a title="Eliminar" onclick="modals.deleteEntityModal(<?php echo $row['prop_id']; ?>, 'propiedades', 'prop_id', 'propiedad');" href="javascript:;" class="glyphicon glyphicon-trash"></a>
                </td>
            </tr>
            <?php
        }
    } else {
        ?>
        <tr class="_no_records"><td colspan="100%"> No se encontraron registros </td></tr>
    <?php } ?>
</table>




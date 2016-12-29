<table class="table table-hover _table _proveedores_table">
    <tr>    
        <th>Nombre</th>
        <th>Telefono</th>
        <th>Email</th>    
        <th>Domicilio</th>
        <th>Calificación</th>
        <th>Acciones</th>
    </tr>
    <?php
    if (count($providers)) {
        foreach ($providers as $row) {
            ?>
            <tr class="_reg_entity_<?php echo $row['prov_id']; ?>">
                <td><?php echo $row['prov_name']; ?></td>
                <td><?php echo $row['prov_tel']; ?></td>
                <td><?php echo $row['prov_email']; ?></td>
                <td><?php echo $row['prov_domicilio']; ?></td>
                <td><?php echo $row['prov_nota']; ?></td>
                <td>
                    <a title="Editar" onclick="general_scripts.loadEntityToEdit(<?php echo $row['prov_id']; ?>, 'proveedores', 'prov_id');" href="javascript:;" class="glyphicon glyphicon-edit"></a>
                    <a title="Eliminar" onclick="modals.deleteEntityModal(<?php echo $row['prov_id']; ?>, 'proveedores', 'prov_id', 'proveedor');" href="javascript:;" class="glyphicon glyphicon-trash"></a>
                </td>
            </tr>
            <?php
        }
    } else {
        ?>
        <tr class="_no_records"><td colspan="100%"> No se encontraron registros </td></tr>
    <?php } ?>
</table>
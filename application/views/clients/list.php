<table class="table table-hover _table _clientes_table">
    <tr>    
        <th>Nombre</th>
        <th>Email</th>
        <th>Fijo</th>
        <th>Celular</th>
        <th>C.U.I.T</th>
        <th>Domicilio</th>
        <th>Acciones</th>
    </tr>
    <?php
    if (count($clients)) {
        foreach ($clients as $row) {
            if (!in_array($row['client_name'], array('INMOBILIARIA', 'CAJA FUERTE'))) {
            ?>
            <tr class="_reg_entity_<?php echo $row['client_id']; ?>">
                <td><?php echo $row['client_name']; ?></td>
                <td><?php echo $row['client_email']; ?></td>
                <td><?php echo $row['client_tel']; ?></td>
                <td><?php echo $row['client_celular']; ?></td>
                <td><?php echo $row['client_cuit']; ?></td>
                <td><?php echo $row['client_calle'] . ' ' . $row['client_nro_calle']; ?></td>
                <td>
                    <a title="Editar" onclick="general_scripts.loadEntityToEdit(<?php echo $row['client_id']; ?>, 'clientes', 'client_id');" href="javascript:;" class="glyphicon glyphicon-edit"></a>
                    <a title="Eliminar" onclick="modals.deleteEntityModal(<?php echo $row['client_id']; ?>, 'clientes', 'client_id', 'cliente');" href="javascript:;" class="glyphicon glyphicon-trash"></a>
                </td>
            </tr>
        <?php
            }
        }
    } else {
    ?>
    <tr class="_no_records"><td colspan="100%"> No se encontraron registros </td></tr>
<?php  } ?>
</table>
<table class="table table-hover _table _providers_rols_table">
    <tr>    
        <th>Area</th>
        <th>Acciones</th>
    </tr>
    <?php
    if (count($providers_rols)) {
        foreach ($providers_rols as $row) {
            ?>
            <tr class="_reg_entity_<?php echo $row['id']; ?>">
                <td><?php echo $row['rol']; ?></td>
                <td>
                    <a title="Editar" onclick="general_scripts.loadEntityToEdit(<?php echo $row['id']; ?>, 'providers_rols', 'id');" href="javascript:;" class="glyphicon glyphicon-edit"></a>
                </td>
            </tr>
            <?php
        }
    } else {
        ?>
        <tr class="_no_records"><td colspan="100%"> No se encontraron registros </td></tr>
    <?php } ?>
</table>
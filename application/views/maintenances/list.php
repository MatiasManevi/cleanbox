<table class="table table-hover _table _mantenimientos_table">
    <tr>    
        <th>Domicilio</th>
        <th>Propietario</th>
        <th>Inquilino</th>    
        <th>Proveedores</th>
        <th>Fecha limite</th>
        <th>Estado</th>
        <th>Acciones</th>
    </tr>
    <?php
    if (count($maintenances)) {
        foreach ($maintenances as $row) {
            if ($row['mant_status'] == 1) {
                $status = '<a title="Creada" class="glyphicon glyphicon-folder-open maintenance_status"></a>';
            } elseif ($row['mant_status'] == 2) {
                $status = '<a title="Asignada y en marcha" class="glyphicon glyphicon-play maintenance_status color_yellow"></a>';
            } else {
                $status = '<a title="Terminada" class="glyphicon glyphicon-ok maintenance_status color_green"></a>';
            }
            ?>
            <tr class="_reg_entity_<?php echo $row['mant_id']; ?>">
                <td><?php echo $row['mant_domicilio']; ?></td>
                <td><?php echo $row['mant_prop']; ?></td>
                <td><?php echo $row['mant_inq']; ?></td>
                <td><?php echo $row['mant_prov_1'] . ($row['mant_prov_2'] ? ', ' . $row['mant_prov_2'] : '') . ' ' . ($row['mant_prov_3'] ? ', ' . $row['mant_prov_3'] : ''); ?></td>
                <td><?php echo $row['mant_date_deadline']; ?></td>
                <td><?php echo $status; ?></td>
                <td>
                    <a title="Editar" onclick="general_scripts.loadEntityToEdit(<?php echo $row['mant_id']; ?>, 'mantenimientos', 'mant_id');" href="javascript:;" class="glyphicon glyphicon-edit"></a>
                    <a title="Eliminar" onclick="modals.deleteEntityModal(<?php echo $row['mant_id']; ?>, 'mantenimientos', 'mant_id', 'mantenimiento');" href="javascript:;" class="glyphicon glyphicon-trash"></a>
                    <a title="Imprimir" onclick="report.buildReportFromList('<?php echo site_url('maintenanceReport') ?>', <?php echo $row['mant_id']; ?>);" href="javascript:;" class="glyphicon glyphicon-print"></a>
                </td>
            </tr>
            <?php
        }
    } else {
        ?>
        <tr class="_no_records"><td colspan="100%"> No se encontraron registros </td></tr>
    <?php } ?>
</table>
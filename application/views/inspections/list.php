<table class="table table-hover _table _inspections_table">
    <tr>    
        <th>Domicilio</th>
        <th>Inquilino</th>    
        <th>Fecha</th>
        <th>Momento</th>
        <th>Acciones</th>
    </tr>
    <?php
    if (count($inspections)) {
        foreach ($inspections as $row) { ?>
            <tr class="_reg_entity_<?php echo $row['id']; ?>">
                <? if ($row['momentum'] == 1) {
                    $momentum = 'Previo contrato';
                } elseif ($row['momentum'] == 2) {
                    $momentum = 'Durante contrato';
                } else {
                    $momentum = 'Post contrato';
                }?>
                <td><?php echo $row['address']; ?></td>
                <td><?php echo $row['renter']; ?></td>
                <td><?php echo $row['date']; ?></td>
                <td><?php echo $momentum ?></td>
                <td>
                    <a title="Editar" onclick="general_scripts.loadEntityToEdit(<?php echo $row['id']; ?>, 'inspections', 'id');" href="javascript:;" class="glyphicon glyphicon-edit"></a>
                    <a title="Eliminar" onclick="modals.deleteEntityModal(<?php echo $row['id']; ?>, 'inspections', 'id', 'inspección');" href="javascript:;" class="glyphicon glyphicon-trash"></a>
                    <!-- <a title="Imprimir" onclick="report.buildReportFromList('<?php echo site_url('inspectionReport') ?>', <?php echo $row['id']; ?>);" href="javascript:;" class="glyphicon glyphicon-print"></a> -->
                </td>
            </tr>
            <?php
        }
    } else {
        ?>
        <tr class="_no_records"><td colspan="100%"> No se encontraron registros </td></tr>
    <?php } ?>
</table>
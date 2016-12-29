<table class="table table-hover _table _debitos_table">
    <tr>    
        <th>Cta. Cte. Debitada</th>
        <th>Concepto</th>
        <th>Monto</th>
        <th>Domicilio Inmueble</th>
        <th>Fecha</th>
        <th>Acciones</th>
    </tr>
    <?
    if (count($debits)) {
        foreach ($debits as $row) {
            ?>
            <tr class="_reg_entity_<?php echo $row['deb_id']; ?>">
                <td><?php echo $row['deb_cc']; ?></td>
                <td><?php echo $row['deb_concepto'] . ' (' . $row['deb_mes'] . ')'; ?></td>
                <td><?php echo '$ ' . $row['deb_monto']; ?></td>
                <td><?php echo $row['deb_domicilio']; ?></td>
                <td><?php echo $row['deb_fecha']; ?></td>
                <td>    
                    <a title="Eliminar" onclick="modals.deleteTransactionModal(<?php echo $row['trans']; ?>, 'debitos');" href="javascript:;" class="glyphicon glyphicon-trash"></a>
                    <?php
                    $must_print = false;
                    if (strpos($row['deb_concepto'], 'Rendicion') !== false) {
                        $must_print = true;
                    }
                    ?>
                    <?php if ($must_print) { ?>
                        &nbsp;<a title="Imprimir" onclick="report.buildReportFromList(show_debit_report_list, <?php echo $row['deb_id']; ?>);" href="javascript:;" class="glyphicon glyphicon-print"></a>
                    <?php } ?>
                </td>
            </tr>
            <?
        }
    } else {
        ?>
        <tr class="_no_records"><td colspan="100%"> No se encontraron registros </td></tr>
    <? } ?>
</table>
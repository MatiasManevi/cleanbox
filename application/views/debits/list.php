<table class="table table-hover _table _debitos_table">
    <tr>    
        <th>Cta. Cte. Debitada</th>
        <th>Concepto</th>
        <th>Monto</th>
        <th>Domicilio Inmueble</th>
        <th>Fecha</th>
        <th>Acciones</th>
    </tr>
    <?php
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
                    $is_rendition = false;
                    if (strpos($row['deb_concepto'], 'Rendicion') !== false) {
                        $is_rendition = true;
                    }
                    ?>
                    <?php if ($is_rendition) { ?>
                        &nbsp;<a title="Imprimir" onclick="report.buildReportFromList(show_debit_report_list, <?php echo $row['deb_id']; ?>);" href="javascript:;" class="glyphicon glyphicon-print"></a>
                    <?php } elseif(User::printDebit() && Report::mustPrintDebit($row['deb_concepto'])) { ?>
                        &nbsp;<a title="Imprimir" onclick="report.buildReportFromList(print_debit_receive_list, <?php echo $row['trans']; ?>);" href="javascript:;" class="glyphicon glyphicon-print"></a>
                    <?php } ?>
                </td>
            </tr>
            <?php
        }
    } else {
        ?>
        <tr class="_no_records"><td colspan="100%"> No se encontraron registros </td></tr>
    <?php } ?>
</table>
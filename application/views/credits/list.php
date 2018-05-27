<table class="table table-hover _table _creditos_table">
    <tr>    
        <th>Depositante o Impositor</th>
        <th>Cta. Cte.</th>
        <th>Concepto</th>
        <th>Monto</th>
        <th>Fecha</th>
        <th>Acciones</th>
    </tr>
    <?php
    if (count($credits)) {
        foreach ($credits as $row) {
            if(isset($row)){
            ?>
            <tr class="_reg_entity_<?php echo $row['cred_id']; ?>">
                <td><?php echo $row['cred_depositante']; ?></td>
                <td><?php echo $row['cred_cc']; ?></td>
                <?php
                if ($row['cred_tipo_pago'] == 'A Cuenta') {
                    $type = ' a cuenta ';
                } else {
                    $type = '';
                }
                ?>
                <td><?php echo $row['cred_concepto'] . $type . ' (' . $row['cred_mes_alq'] . ')'; ?></td>
                <td><?php echo '$ ' . $row['cred_monto']; ?></td>
                <td><?php echo $row['cred_fecha']; ?></td>
                <td>    
                    <a title="Eliminar" onclick="modals.deleteTransactionModal(<?php echo $row['trans']; ?>, 'creditos');" href="javascript:;" class="glyphicon glyphicon-trash"></a>

                    <?php if (Report::mustPrintReport($row['cred_concepto'])) { ?>
                        &nbsp;<a title="Imprimir" onclick="report.buildReportFromList(show_credit_report_list, <?php echo $row['trans']; ?>);" href="javascript:;" class="glyphicon glyphicon-print"></a>
                    <?php } ?>
                </td>
            </tr>
            <?php }
        }
    } else {
        ?>
        <tr class="_no_records"><td colspan="100%"> No se encontraron registros </td></tr>
    <?php } ?>
</table>

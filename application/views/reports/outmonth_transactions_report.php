<div id="printable" class="report_sheet _excel">
    <div id="non-printable" class="report_actions">
        <a class="btn btn-primary button_report" href="javascript:;" onclick="window.print();return false;">Imprimir</a>
        <a class="btn btn-primary button_report" href="<?php echo site_url('outmonthTransactionsReport') ?>">Volver</a>
    </div>
    
    <h4>Transacciones Outmonth  <?php echo '(' . $month . ')' ?></h4>

    <table class="table">
        <tr><th class="cell" colspan="5">Créditos</th></tr>
        <tr>    
            <th class="cell">Fecha</th>
            <th class="cell">Concepto</th>
            <th class="cell">Cta. Cte.</th>
            <th class="cell">Depositante</th>
            <th class="cell">Monto</th>
        </tr>
        
        <?php $total_cred = 0;?>
        <?php $total_deb = 0;?>

        <?php foreach ($credits as $credit) { ?>
            <tr>
                <td class="cell"><?php echo $credit['cred_fecha']; ?></td>
                <td class="cell"><?php echo $credit['cred_concepto'] . ' - ' . $credit['cred_mes_alq']; ?></td>
                <td class="cell"><?php echo $credit['cred_cc']; ?></td>
                <td class="cell"><?php echo $credit['cred_depositante']; ?></td>
                <td class="cell">$ <?php echo $credit['cred_monto']; ?></td>
            </tr>
            <?php $total_cred += $credit['cred_monto']; ?>
        <?php } ?>
        <tr>
            <td colspan="4" class="cell">Totales</td>
            <td class="cell">$ <?php echo $total_cred; ?></td>
        </tr>
        <tr>
            <td></td>
        </tr>
    </table>
    
    <?php if (!empty($months_ins)) { ?>
        <table style="margin-bottom: 54px;width: 50%;" class="table">
            <tr><th class="cell" colspan="4">Totales mensuales</th></tr>
            <?php foreach ($months_ins as $month => $month_value) {
                ?>
                    <tr>
                        <th class="cell"><?php echo 'Ingreso de ' . $month ?></th>
                        <td class="cell"><?php echo '$ ' . $month_value ?></td>
                    </tr>
                <?php
            }?>
        </table>
    <?php } ?>
    
    <table class="table">
        <tr><th class="cell" colspan="4">Débitos</th></tr>
        <tr>    
            <th class="cell">Fecha</th>
            <th class="cell">Concepto</th>
            <th class="cell">Cta. Cte.</th>
            <th class="cell">Monto</th>
        </tr>
        <?php foreach ($debits as $debit) { ?>
            <tr>
                <td class="cell"><?php echo $debit['deb_fecha']; ?></td>
                <td class="cell"><?php echo $debit['deb_concepto'] . ' - ' . $debit['deb_mes']; ?></td>
                <td class="cell"><?php echo $debit['deb_cc']; ?></td>
                <td class="cell">$ <?php echo $debit['deb_monto']; ?></td>
            </tr>
            <?php $total_deb += $debit['deb_monto']; ?>
        <?php } ?>
        <tr>
            <td colspan="3" class="cell">Totales</td>
            <td class="cell">$ <?php echo $total_deb; ?></td>
        </tr>
        <tr>
            <td></td>
        </tr>
    </table>

    <?php if (!empty($months_outs)) { ?>
        <table class="table" style="width: 50%">
            <tr><th class="cell" colspan="4">Totales mensuales</th></tr>
            <?php foreach ($months_outs as $month => $month_value) {
                ?>
                    <tr>
                        <th class="cell"><?php echo 'Egreso de ' . $month ?></th>
                        <td class="cell"><?php echo '$ ' . $month_value ?></td>
                    </tr>
                <?php
            }?>
        </table>
    <?php } ?>
    
    <table class="table">
        <tr>    
            <td class="cell">Balance final</td>
            <td class="cell">$ <?php echo ($total_cred - $total_deb); ?></td>
        </tr>
    </table>
</div>

<script>
    $(function(){
        $("._excel").tableExport({
            formats: ["xlsx"],  
            position: 'bottom',
            fileName: "Transacciones Outmonth  <?php echo '(' . $month . ')' ?>"
        });
    });
</script>
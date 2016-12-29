<div id="printable" class="report_sheet _excel">
    <div id="non-printable" class="report_actions">
        <a class="btn btn-primary button_report" href="javascript:;" onclick="window.print();return false;">Imprimir</a>
        <a class="btn btn-primary button_report" href="<?php echo site_url('bankTransactionsReport') ?>">Volver</a>
    </div>
    
    <h4>Transacciones Bancarias  <?php echo '(' . $from . ' a ' . $to . ')' ?></h4>

    <table style="margin-bottom: 54px" class="table">
        <tr><th class="cell" colspan="5">Créditos</th></tr>
        <tr>    
            <th class="cell">Fecha</th>
            <th class="cell">Concepto</th>
            <th class="cell">Cta. Cte.</th>
            <th class="cell">Depositante</th>
            <th class="cell">Monto</th>
        </tr>
        <?php foreach ($credits as $credit) { ?>
            <tr>
                <td class="cell"><?php echo $credit['cred_fecha']; ?></td>
                <td class="cell"><?php echo $credit['cred_concepto'] . ' - ' . $credit['cred_mes_alq']; ?></td>
                <td class="cell"><?php echo $credit['cred_cc']; ?></td>
                <td class="cell"><?php echo $credit['cred_depositante']; ?></td>
                <td class="cell">$ <?php echo $credit['cred_monto']; ?></td>
            </tr>
        <?php } ?>
        <tr>
            <td colspan="4" class="cell">Totales</td>
            <td class="cell">$ <?php echo $total_cred; ?></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
        </tr>
    </table>

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
        <?php } ?>
        <tr>
            <td colspan="3" class="cell">Totales</td>
            <td class="cell">$ <?php echo $total_deb; ?></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
        </tr>
    </table>

    <table class="table">
        <tr>    
            <td class="cell">Total final</td>
            <td class="cell">$ <?php echo ($total_cred - $total_deb); ?></td>
        </tr>
    </table>
</div>

<script>
    $(function(){
        $("._excel").tableExport({
            formats: ["xlsx"],  
            position: 'bottom',
            fileName: "Transacciones Bancarias  <?php echo '(' . $from . ' a ' . $to . ')' ?>"
        });
    });
</script>
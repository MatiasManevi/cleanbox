<div id="printable" class="report_sheet _excel">
    <div id="non-printable" class="report_actions">
        <a class="btn btn-primary button_report" href="javascript:;" onclick="window.print();return false;">Imprimir</a>
        <a class="btn btn-primary button_report" href="<?php echo site_url('generalBalanceReport') ?>">Volver</a>
    </div>
    <h4>Balance General  <?php echo '(' . $from . ' a ' . $to . ')' ?></h4>

    <table style="margin-bottom: 54px" class="table">
        <tr><th class="cell" colspan="2">Ingresos</th></tr>
        <tr>
            <th class="cell">Honorarios</th>
            <td class="cell">$ <?php echo $honorary; ?></td>
        </tr>
        <tr>
            <th class="cell">Tasacion</th>
            <td class="cell">$ <?php echo $tasation; ?></td>
        </tr>
        <tr>    
            <th class="cell">Gestion de Cobro</th>
            <td class="cell">$ <?php echo $gestion; ?></td>
        </tr>
        <tr>
            <th class="cell">Total</th>
            <td class="cell">$ <?php echo $total_facturation; ?></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
        </tr>
    </table>

    <table class="table">
        <tr><th class="cell" colspan="2">Gastos</th></tr>
        <?php foreach ($expenses as $concept => $expense) { ?>
            <tr>    
                <th class="cell"><?php echo $concept; ?></th>
                <td class="cell">$ <?php echo $expense; ?></td>
            </tr>
        <?php } ?>
        <tr>
            <th class="cell">Total</th>
            <td class="cell">$ <?php echo $total_expenses; ?></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
        </tr>
    </table>

    <table class="table">
        <tr>    
            <th class="cell">Balance final</th>
            <td class="cell">$ <?php echo round($total_facturation - $total_expenses, 2); ?></td>
        </tr>
    </table>
</div>

<script>
    $(function(){
        $("._excel").tableExport({
            formats: ["xlsx"],  
            position: 'bottom',
            fileName: "Balance General  <?php echo '(' . $from . ' a ' . $to . ')' ?>"
        });
    });
</script>
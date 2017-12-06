<div id="printable" class="report_sheet _excel">
    <?php
    $i = 0;
    $total = 0;
    ?>
    <div id="non-printable" class="report_actions">
        <a class="btn btn-primary button_report" href="javascript:;" onclick="window.print();return false;">Imprimir</a>
        <a class="btn btn-primary button_report" href="<?php echo site_url('accountsBalanceReport') ?>">Volver</a>
    </div>

    <h4>Informe de Balance de Cuentas <?php echo $month ?></h4>

    <table class="table table-hover">
        <tr>    
            <th class="cell">Nro.</th>
            <th class="cell">Nombre</th>
            <th class="cell">Creditos</th>
            <th class="cell">Debitos</th>
            <th class="cell">Saldo periodo</th>
        </tr>
        <?php if (count($accounts) > 0) { ?>
            <?php foreach ($accounts as $account) { ?>
                <?php $i++; ?>
                <?php $total += $account['balance']; ?>
                <tr>
                    <td class="cell"><?php echo $i; ?></td>
                    <td class="cell"><?php echo $account['name']; ?></td>
                    <td class="cell" title="Entradas del periodo">$ <?php echo $account['ins']; ?></td>
                    <td class="cell" title="Salidas del periodo">$ <?php echo $account['outs']; ?></td>
                    <td class="cell" title="Saldo del periodo">$ <?php echo $account['balance']; ?></td>
                </tr>
            <?php } ?>
                <tr>
                    <td  colspan="5"></td>
                </tr>
                <tr>
                    <td class="cell" colspan="4">TOTAL</td>
                    <td class="cell" >$ <?php echo $total; ?></td>
                </tr>
        <?php } ?>
    </table>

</div>
<script>
    $(function(){
        $("._excel").tableExport({
            formats: ["xlsx"],  
            position: 'bottom',
            fileName: "Informe de Balance de Cuentas  <?php echo $month ?>"
        });
    });
</script>
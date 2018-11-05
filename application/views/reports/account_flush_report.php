<div id="printable" class="report_sheet _excel">
    <div id="non-printable" class="report_actions">
        <a class="btn btn-primary button_report" href="javascript:;" onclick="window.print();return false;">Imprimir</a>
        <a class="btn btn-primary button_report" href="<?php echo site_url('accountFlushReport') ?>">Volver</a>
    </div>
    <h4><?php echo date('d-m-Y') ?></h4>
    <h4><?php echo $heading; ?></h4>
    <?php $total = 0; ?>

    <table class="table" style="margin-bottom: 40px">
        <tr>    
            <th class="cell">Propietario</th>
            <th class="cell">Entradas</th>
            <th class="cell">Salidas</th>
            <th class="cell">Balance</th>
        </tr>    
        <?php foreach ($accounts as $account) { ?>
            <tr class="reg<?php echo $account['cc_id']; ?>">
                <td class="cell"><?php echo $account['cc_prop']; ?></td>
                <td class="cell">$ <?php echo $account['ins']; ?></td>
                <td class="cell">$ <?php echo $account['outs']; ?></td>
                <td class="cell">$ <?php echo $account['balance']; ?></td>
            </tr>
            <?php $total += $account['balance'] ?>
        <?php } ?>
        <tr>    
            <th class="cell">Total</th>
            <th class="cell"></th>
            <th class="cell"></th>
            <th class="cell">$ <?php echo $total; ?></th>
        </tr>  
    </table>
</div>       

<script>
    $(function(){
        $("._excel").tableExport({
            formats: ["xlsx"],  
            position: 'bottom',
            fileName: "<?php echo $heading; ?>"
        });
    });
</script>


<div id="printable" class="report_sheet _excel">
    <div id="non-printable" class="report_actions">
        <a class="btn btn-primary button_report" href="javascript:;" onclick="window.print();return false;">Imprimir</a>
        <a class="btn btn-primary button_report" href="<?php echo site_url('pendingRenditionsReport') ?>">Volver</a>
    </div>
    <h4><?php echo date('d-m-Y') ?></h4>
    <h4><?php echo $heading; ?></h4>
    <?php $total = 0; ?>

    <table class="table" style="margin-bottom: 40px">
        <tr>    
            <th class="cell">Propietario</th>
            <th class="cell">Saldo Operativo Actual</th>
        </tr>    
        <?php foreach ($pending_renditions as $pending_rendition) { ?>
            <tr class="reg<?php echo $pending_rendition['cc_id']; ?>">
                <td class="cell"><?php echo $pending_rendition['cc_prop']; ?></td>
                <td class="cell">$ <?php echo round($pending_rendition['cc_saldo'] + $pending_rendition['cc_varios'], 2); ?></td>
            </tr>
            <?php $total += $pending_rendition['cc_saldo'] + $pending_rendition['cc_varios']; ?>
        <?php } ?>
        <tr>    
            <th class="cell">Total</th>
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


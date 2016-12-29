<div id="printable" class="report_sheet _excel">
    <?php
    $total_renditions = 0;
    $i = 0;
    ?>
    <div id="non-printable" class="report_actions">
        <a class="btn btn-primary button_report" href="javascript:;" onclick="window.print();return false;">Imprimir</a>
        <a class="btn btn-primary button_report" href="<?php echo site_url('propietaryRenditionsReport') ?>">Volver</a>
    </div>

    <h4>Informe de Rendiciones a Propietarios  <?php echo '(' . $from . ' a ' . $to . ')' ?></h4>

    <table class="table table-hover">

        <tr>    
            <th class="cell">Nro.</th>
            <th class="cell">Nombre</th>
            <th class="cell" title="Rendiciones retiradas en el periodo">Rendiciones</th>
            <th class="cell">Entradas periodo</th>
            <th class="cell">Salidas periodo</th>
            <th class="cell">Saldo periodo</th>
            <th class="cell" title="Indistinto del periodo">Saldo operativo</th>
        </tr>
        <?php if (count($accounts) > 0) { ?>
            <?php foreach ($accounts as $account) { ?>
                <?php $i++; ?>
                <?php $total_renditions += $account['rendition']; ?>

                <tr class="<?php echo $account['extract_rendition'] ? 'extract' : '' ?>">
                    <td class="cell"><?php echo $i; ?></td>
                    <td class="cell"><?php echo $account['name']; ?></td>
                    <td class="cell" title="Rendiciones del periodo">$ <?php echo $account['rendition']; ?></td>
                    <td class="cell" title="Entradas del periodo">$ <?php echo $account['ins']; ?></td>
                    <td class="cell" title="Salidas del periodo">$ <?php echo $account['outs']; ?></td>
                    <td class="cell" title="Saldo del periodo">$ <?php echo $account['account_movements_sald']; ?></td>
                    <td class="cell" title="Indistinto del periodo">$ <?php echo $account['account_operative_sald']; ?></td>
                </tr>
            <?php } ?>
        <?php } ?>
        <tr>
            <td></td>
            <td></td>
        </tr>
    </table>

    <table class="table table-hover">
        <tr>
            <td class="cell">Total retirado en Rendiciones</td>
            <td class="cell">$ <?php echo $total_renditions; ?></td>
        </tr>
    </table>

    <span id="non-printable" style="float: left;margin-bottom: 7px;margin-left: 20px;margin-top: 21px;">Referencias:</span>

    <div id="non-printable" class="referencias">                   
        <div style="border: 2px solid;border-radius: 5px;background: none repeat scroll 0 0 #f5da81;float: left;height: 20px;margin-right: 5px;width: 20px;"></div><span style="float: left;">Retiro dinero de Rendicion</span>
        <div style="border: 2px solid;border-radius: 5px;background: none repeat scroll 0 0 white;float: left;height: 20px;margin-left: 10px;margin-right: 5px;width: 20px;"></div><span>No Retiro dinero de Rendicion</span>
    </div>

</div>
<script>
    $(function(){
        $("._excel").tableExport({
            formats: ["xlsx"],  
            position: 'bottom',
            fileName: "Informe de Rendiciones a Propietarios  <?php echo '(' . $from . ' a ' . $to . ')' ?>"
        });
    });
</script>
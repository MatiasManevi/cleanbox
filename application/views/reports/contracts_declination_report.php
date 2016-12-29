<div id="printable" class="report_sheet _excel">
    <div id="non-printable" class="report_actions">
        <a class="btn btn-primary button_report" href="javascript:;" onclick="window.print();return false;">Imprimir</a>
        <a class="btn btn-primary button_report" href="<?php echo site_url('contractsDeclinationReport') ?>">Volver</a>
    </div>
    <h4 style="float:right;margin-right: 15px;margin-top: 15px;"><?php echo date('d-m-Y') ?></h4>
    <h4 style="margin-bottom: 50px;margin-top: 47px;"><?php echo $heading ?></h4>

    <?php if (count($contracts) > 0) { ?>
        <table class="table">
            <tr>    
                <th class="cell">Propietario</th>
                <th class="cell">Inquilino</th>
                <th class="cell">Vencimiento</th>
                <th class="cell">Ultimo Pago</th>
                <th class="cell">Tipo Contrato</th>
                <?php foreach ($contracts as $contract) { ?>
                <tr class="reg_<?php echo $contract['con_id']; ?>">
                    <td class="cell"><?php echo $contract['con_prop']; ?></td>
                    <td class="cell"><?php echo $contract['con_inq']; ?></td>
                    <td class="cell"><?php echo date('d-m-Y', $contract['con_venc']); ?></td>
                    <td class="cell"><?php echo strlen($contract['last_payment']['cred_mes_alq']) ? $contract['last_payment']['cred_mes_alq'] . ' (' . $contract['last_payment']['cred_fecha'] . ')' : '-'; ?></td>
                    <td class="cell"><?php echo $contract['con_tipo']; ?></td>
                </tr>
            <?php } ?>
            </tr>
        </table>
    <?php } else { ?>
        <div class="alignCenter">No se encontraron registros en la fecha indicada</div>
    <?php } ?>
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


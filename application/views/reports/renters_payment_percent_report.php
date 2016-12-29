<div id="printable" class="report_sheet _excel">
    <div id="non-printable" class="report_actions">
        <a class="btn btn-primary button_report" href="javascript:;" onclick="window.print();return false;">Imprimir</a>
        <a class="btn btn-primary button_report" href="<?php echo site_url('rentersPaymentPercentReport') ?>">Volver</a>
    </div>
    <h4 style="float:right;margin-right: 15px;margin-top: 15px;"><?php echo date('d-m-Y') ?></h4>
    <h4 style="margin-bottom: 50px;margin-top: 47px;"><?php echo $heading ?></h4>

    <table class="table">
        <tr>
            <th class="cell">Tipo de contrato</th>
            <th class="cell">Contratos</th>
            <th class="cell">Contratos pagados</th>
            <th class="cell">Porcentaje</th>
        </tr>
        <tr>
            <td class="cell">Alquiler</td>
            <td class="cell"><?php echo $rent_contracts; ?></td>
            <td class="cell"><?php echo $payed_rent_contracts; ?></td>
            <td class="cell">% <?php echo round($payed_rent_contracts / $rent_contracts * 100, 2); ?></td>
        </tr>
        <tr>
            <td class="cell">Alquiler Comercial</td>
            <td class="cell"><?php echo $comercial_rents_contracts; ?></td>
            <td class="cell"><?php echo $payed_comercial_rents_contracts; ?></td>
            <td class="cell">% <?php echo round($payed_comercial_rents_contracts / $comercial_rents_contracts * 100, 2); ?></td>
        </tr>
        <tr>
            <td class="cell">Loteo</td>
            <td class="cell"><?php echo $lot_contracts; ?></td>
            <td class="cell"><?php echo $payed_lot_contracts; ?></td>
            <td class="cell">% <?php echo round($payed_lot_contracts / $lot_contracts * 100, 2); ?></td>
        </tr>
        <tr>
            <th class="cell">TOTAL</th>
            <td class="cell"><?php echo $total_contracts; ?></td>
            <td class="cell"><?php echo $total_payed; ?></td>
            <td class="cell">% <?php echo round($total_payed / $total_contracts * 100, 2); ?></td>
        </tr>
    </table>  
</div> 

<script>
    $(function(){
        $("._excel").tableExport({
            formats: ["xlsx"],  
            position: 'bottom',
            fileName: "<?php echo $heading ?>"
        });
    });
</script>
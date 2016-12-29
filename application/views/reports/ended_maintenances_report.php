<div id="printable" class="report_sheet _excel">
    <div id="non-printable" class="report_actions">
        <a class="btn btn-primary button_report" href="javascript:;" onclick="window.print();return false;">Imprimir</a>
        <a class="btn btn-primary button_report" href="<?php echo site_url('endedMaintenancesReport') ?>">Volver</a>
    </div>
    <h4>Historial de Mantenimientos finalizados  <?php echo '(' . $from . ' a ' . $to . ')' ?></h4>

    <?php if (count($maintenances) > 0) { ?>
        <table style="margin-bottom: 54px" class="table">
            <tr>
                <th class="cell">Domicilio</th>
                <th class="cell">Descripcion</th>
                <th class="cell">Proveedores</th>
                <th class="cell">Inquilino</th>
                <th class="cell">Propietario</th>
                <th class="cell">Fecha de Finalizacion</th>
            </tr>
            <?php foreach ($maintenances as $maintenance) { ?>
                <?php
                $providers = strlen($maintenance['mant_prov_1']) > 0 ? $maintenance['mant_prov_1'] : '';
                $providers .= (strlen($providers) > 0 ? ',' : '') . strlen($maintenance['mant_prov_2']) > 0 ? $maintenance['mant_prov_2'] . ',' : '';
                $providers .= (strlen($providers) > 0 ? ',' : '') . strlen($maintenance['mant_prov_3']) > 0 ? $maintenance['mant_prov_3'] . ',' : '';
                ?>
                <tr>
                    <td class="cell"><?php echo $maintenance['mant_domicilio']; ?></td>
                    <td class="cell"><?php echo $maintenance['mant_desc']; ?></td>
                    <td class="cell"><?php echo $providers; ?></td>
                    <td class="cell"><?php echo $maintenance['mant_inq']; ?></td>
                    <td class="cell"><?php echo $maintenance['mant_prop']; ?></td>
                    <td class="cell"><?php echo $maintenance['mant_date_end']; ?></td>
                </tr>
            <?php } ?>
        </table>
    <?php } else { ?>
        <div class="alignCenter">No se encontraron registros en las fechas indicadas</div>
    <?php } ?>
</div>

<script>
    $(function(){
        $("._excel").tableExport({
            formats: ["xlsx"],  
            position: 'bottom',
            fileName: "Historial de Mantenimientos finalizados  <?php echo '(' . $from . ' a ' . $to . ')' ?> "
        });
    });
</script>
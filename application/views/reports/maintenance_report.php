
<div id="printable"  class="report_sheet">
    <h4 style="float:left;">Posadas, Misiones</h4>           
    <h4 style="float:right;"><?php echo date('d-m-Y') ?></h4>
    <h4>Informe Mantenimiento</h4>

    <table class="table">
        <tr>    
            <th class="cell" style="line-height: 35px;">Propietario</th>
            <td class="cell" style="line-height: 35px;"><?php echo $maintenance['mant_prop'] ?></td>
        </tr>
        <tr>
            <th class="cell" style="line-height: 35px;">Inquilino</th>
            <td class="cell" style="line-height: 35px;"><?php echo $maintenance['mant_inq'] ?></td>
        </tr>
        <tr>
            <th class="cell" style="line-height: 35px;">Proveedor</th>
            <td class="cell" style="line-height: 35px;"><?php echo $maintenance['mant_prov_1'] . ' ' . $maintenance['mant_prov_2'] . ' ' . $maintenance['mant_prov_3'] ?></td>
        </tr>
        <tr>
            <th class="cell" style="line-height: 35px;">Domicilio</th>
            <td class="cell" style="line-height: 35px;"><?php echo $maintenance['mant_domicilio'] ?></td>
        </tr>
    </table>
    
    <table class="table">
        <tr>
            <th class="cell" style="line-height: 35px;">Descripción detallada</th>
        </tr>
        <tr>
            <td class="cell" style="padding: 7px !important;"><?php echo $maintenance['mant_desc'] ?></td>
        </tr>
    </table>
    
    <table class="table">
        <?
        if ($maintenance['mant_prioridad'] == 1) {
            $prioridad = 'Alta';
        } elseif ($maintenance['mant_prioridad'] == 2) {
            $prioridad = 'Media';
        } else {
            $prioridad = 'Baja';
        }
        if ($maintenance['mant_status'] == 1) {
            $status = 'Creada';
        } elseif ($maintenance['mant_status'] == 2) {
            $status = 'Asignada y en marcha';
        } else {
            $status = 'Terminada';
        }
        ?>            
        <tr>
            <th class="cell" style="line-height: 35px;">Prioridad de Tarea</th>
            <td class="cell" style="line-height: 35px;"><?php echo $prioridad ?></td>
        </tr>
        <tr>    
            <th class="cell" style="line-height: 35px;">Fecha límite</th>
            <td class="cell" style="line-height: 35px;"><?php echo $maintenance['mant_date_deadline'] ?></td>
        </tr>
        <tr>
            <th class="cell" style="line-height: 35px;">Fecha de terminación</th>
            <td class="cell" style="line-height: 35px;"><?php echo $maintenance['mant_date_end'] ?></td>
        </tr>
        <tr>
            <th class="cell" style="line-height: 35px;">Presupuesto</th>
            <td class="cell" style="line-height: 35px;">$ <?php echo $maintenance['mant_monto'] ?></td>
        </tr>
        <tr>    
            <th class="cell" style="line-height: 35px;">Calificación</th>
            <td class="cell" style="line-height: 35px;"><?php echo $maintenance['mant_calif'] ?></td>
        </tr>
        <tr style="height: 60px;">
            <th class="cell">Firma:</th>
            <td class="cell"></td>
        </tr>
    </table>
</div>   

<div id="non-printable" class="report_actions">
    <a class="btn btn-primary button_report" href="javascript:;" onclick="window.print();return false;">Imprimir</a>
    <a class="btn btn-primary button_report" href="<?php echo site_url('maintenances') ?>">Volver</a>
</div>


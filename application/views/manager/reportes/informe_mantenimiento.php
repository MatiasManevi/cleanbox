<style>
    .table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
        padding: 0 !important;
    }
    body {
        background-color: white;
        padding-top: 60px;
    }
    .contenedor_centro{
        display: block;
        float: left;
        margin-left: 0px !important;
        width: 86%;
    }
</style>
<form style="overflow: visible;margin-bottom: 15px;">
    <div id="printable">
        <div style="height: auto;" class="hoja_informe">
            <h2 style="font-size: 16px;float:left;margin-left: 15px;margin-top: 15px;">Posadas, Misiones</h2>           
            <h2 style="font-size: 12px;float:right;margin-right: 15px;margin-top: 15px;"><?= date('d-m-Y') ?></h2>
            <h2 style="text-decoration:underline;margin-bottom: 8px;margin-top: 47px;">Informe Mantenimiento</h2>
            <h2 style="margin-bottom: 22px;margin-top: 17px;font-size: 16px;text-decoration: underline;"></h2>
            <table class="table" style="width: 93%;margin: 0 auto;">
                <tr>    
                    <th class="centrar" style="line-height: 35px;">Propietario</th>
                    <td class="centrar" style="line-height: 35px;"><?= $mantenimiento['mant_prop'] ?></td>
                </tr>
                <tr>
                    <th class="centrar" style="line-height: 35px;">Inquilino</th>
                    <td class="centrar" style="line-height: 35px;"><?= $mantenimiento['mant_inq'] ?></td>
                </tr>
                <tr>
                    <th class="centrar" style="line-height: 35px;">Proveedor</th>
                    <td class="centrar" style="line-height: 35px;"><?= $mantenimiento['mant_prov'] ?></td>
                </tr>
                <tr>
                    <th class="centrar" style="line-height: 35px;">Domicilio</th>
                    <td class="centrar" style="line-height: 35px;"><?= $mantenimiento['mant_domicilio'] ?></td>
                </tr>
            </table>
            <table class="table" style="width: 93%;margin-top: 37px;margin-left: 33px;margin-bottom: 0px;">
                <tr>
                    <th class="centrar" style="line-height: 35px;">Descripción detallada</th>
                </tr>
                <tr>
                    <td class="centrar" style="padding: 7px !important;"><?= $mantenimiento['mant_desc'] ?></td>
                </tr>
            </table>
            <table class="table" style="width: 93%;margin-left: 33px;">
                <?
                if ($mantenimiento['mant_prioridad'] == 1) {
                    $prioridad = 'Alta';
                } elseif ($mantenimiento['mant_prioridad'] == 2) {
                    $prioridad = 'Media';
                } else {
                    $prioridad = 'Baja';
                }
                if ($mantenimiento['mant_status'] == 1) {
                    $status = 'Creada';
                } elseif ($mantenimiento['mant_status'] == 2) {
                    $status = 'Asignada y en marcha';
                } else {
                    $status = 'Terminada';
                }
                ?>            
                <tr>
                    <th class="centrar" style="line-height: 35px;">Prioridad de Tarea</th>
                    <td class="centrar" style="line-height: 35px;"><?= $prioridad ?></td>
                </tr>
                <tr>    
                    <th class="centrar" style="line-height: 35px;">Fecha límite</th>
                    <td class="centrar" style="line-height: 35px;"><?= $mantenimiento['mant_date_deadline'] ?></td>
                </tr>
                <tr>
                    <th class="centrar" style="line-height: 35px;">Fecha de terminación</th>
                    <td class="centrar" style="line-height: 35px;"><?= $mantenimiento['mant_date_end'] ?></td>
                </tr>
                <tr>
                    <th class="centrar" style="line-height: 35px;">Presupuesto</th>
                    <td class="centrar" style="line-height: 35px;">$ <?= $mantenimiento['mant_monto'] ?></td>
                </tr>
                <tr>    
                    <th class="centrar" style="line-height: 35px;">Calificación</th>
                    <td class="centrar" style="line-height: 35px;"><?= $mantenimiento['mant_calif'] ?></td>
                </tr>
            </table>
            <span style=" float: left;margin-left: 38px;margin-top: 26px;">Firma: ________________________</span>
        </div>      
    </div>   
    <div id="non-printable">
        <input id="deb" class="btn btn-primary" style="margin-bottom: 15px; margin-top: 22px;float: right;font-family: 'futura';text-transform: inherit;
               font-size: 20px;" type="button" value="Imprimir Informe" onclick="window.print();return false;" />
        <a id="buttons_cli" style=" margin-top: 23px;" class="btn" href="<?= site_url('manager/mantenimientos') ?>"><?= 'Volver' ?></a>
    </div>
</form>  

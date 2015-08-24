<style>
    .table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
        padding: 0 !important;
    }
    body {
        background-color: white;
        padding-top: 60px;
    }
</style>
<div>
    <form style="overflow: visible;margin-bottom: 15px;">

        <div id="printable" class="row">

            <div  style="height: auto;width: 600px;margin-left: 0" class="hoja_informe">
                <h2 style="margin-top: 47px;">Informe General Caja Diaria <?= $fecha ?></h2>

                <div style="clear: both;"class="global_cabezera">                   
                    <div style="width: 600px;position:inherit;"class="cuerpo">

                        <table style="width: 100%;" class="table">
                            <tr class="centrar">
                                <td style="font-weight: bold" class="centrar">Cuentas</td>
                                <td style="font-weight: bold" class="centrar">Saldos</td>
                            </tr>
                            <tr class="centrar">
                                <td class="centrar">Cuentas Principales Propietarios</td>
                                <td class="centrar"><?= '$ ' . $alquileres_monto ?></td>
                            </tr>
                            <tr class="centrar">
                                <td class="centrar">Cuentas Secundarias Propietarios</td>
                                <td class="centrar"><?= '$ ' . $varios ?></td>
                            </tr>
                            <tr class="centrar">
                                <td class="centrar">Cuenta Inmobiliaria (Pcpal y Sec)</td>
                                <td class="centrar"><?= '$ ' . $rima ?></td>
                            </tr>
                            <tr class="centrar">
                                <td class="centrar">Sub-Total</td>
                                <td class="centrar"><?= '$ ' . $subtotal ?></td>
                            </tr>
                            <tr class="centrar">
                                <td class="centrar"></td>
                                <td class="centrar"></td>
                            </tr>
                            <tr class="centrar">
                                <td class="centrar">Loteos</td>
                                <td class="centrar"><?= '$ ' . $loteos ?></td>
                            </tr>
                            <tr class="centrar">
                                <td class="centrar"></td>
                                <td class="centrar"></td>
                            </tr>
                            <tr class="centrar">
                                <td class="centrar">Total</td>
                                <td class="centrar"><?= '$ ' . $total ?></td>
                            </tr>
                        </table>    

                    </div>
                    <div style="width: 600px;position:inherit;"class="cuerpo">
                        <table style="width: 100%;" class="table">
                            <tr class="centrar">
                                <td style="font-weight: bold" class="centrar">Conceptos Generales</td>
                                <td style="font-weight: bold" class="centrar">Saldos</td>
                            </tr>
                            <tr class="centrar">
                                <td class="centrar">Ingreso Mensual IVA (Rima)</td>
                                <td class="centrar"><?= '$ ' . $iva_mensual ?></td>
                            </tr>
                            <tr class="centrar">
                                <td class="centrar">Ingreso Alquileres</td>
                                <td class="centrar"><?= '$ ' . $entrada_alquileres ?></td>
                            </tr>
                            <tr class="centrar">
                                <td class="centrar">Ingreso Gestion de Cobro</td>
                                <td class="centrar"><?= '$ ' . $entrada_gestion ?></td>
                            </tr>
                            <tr class="centrar">
                                <td class="centrar">Ingreso Comision</td>
                                <td class="centrar"><?= '$ ' . $entrada_comision ?></td>
                            </tr>
                            <tr class="centrar">
                                <td class="centrar">Ingreso IVA Hoy</td>
                                <td class="centrar"><?= '$ ' . $iva ?></td>
                            </tr>
                            <tr class="centrar">
                                <td class="centrar">Ingreso Varios</td>
                                <td class="centrar"><?= '$ ' . $entrada_varios ?></td>
                            </tr>
                            <tr class="centrar">
                                <td class="centrar"></td>
                                <td class="centrar"></td>
                            </tr>
                            <tr class="centrar">
                                <td class="centrar">Egreso Alquileres</td>
                                <td class="centrar"><?= '$ ' . $salida_alquileres ?></td>
                            </tr>
                            <tr class="centrar">
                                <td class="centrar">Egreso Arreglos</td>
                                <td class="centrar"><?= '$ ' . $salida_arreglos ?></td>
                            </tr>
                            <tr class="centrar">
                                <td class="centrar">Egreso Varios</td>
                                <td class="centrar"><?= '$ ' . $salida_varios ?></td>
                            </tr>
                        </table>    

                    </div>
                </div>        
            </div>
            <div id="non-printable">
                <input  class="btn btn-primary" style="margin-bottom: 15px; margin-top: 22px;float: right;font-family: 'futura';text-transform: inherit;
                        font-size: 20px;" type="button" value="Imprimir Informe" onclick="window.print();return false;" />
                <a id="buttons_cli" style=" margin-top: 23px;" class="btn" href="<?= site_url('manager/caja_general') ?>"><?= 'Volver' ?></a>
            </div>
        </div>

    </form>
</div>
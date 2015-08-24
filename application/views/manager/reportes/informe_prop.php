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

    <div id="printable" class="">
        <input id="con_prop" type="hidden" value="<?= $cuenta ?>">
        <div style="height: auto;" class="hoja_informe">
            <h2 style="font-size: 16px;float:left;margin-left: 15px;margin-top: 15px;">Posadas, Misiones</h2>           
            <h2 style="font-size: 12px;float:right;margin-right: 15px;margin-top: 15px;"><?= date('d-m-Y') ?></h2>
            <h2 style="margin-bottom: 8px;margin-top: 47px;">Informe Detallado Cta. Cte. <?= $cuenta ?></h2>
            <h2 style="margin-bottom: 42px;margin-top: 14px;">Periodo <?= ' (' . $desde . ' a ' . $hasta . ')' ?></h2>
            <h2 style="margin-bottom: 22px;margin-top: 17px;font-size: 16px;text-decoration: underline;">Movimientos Cta. Principal</h2>
            <table class="table">
                <tr>    

                    <th class="centrar">Concepto</th>
                    <th class="centrar">Fecha</th>
                    <th class="centrar">Debitos</th>
                    <th class="centrar">Creditos</th>
                    <th class="centrar">Saldo</th>

                    <?
                    $saldo = 0;
                    if (count($alquileres) > 0) {
                        for ($x = 0; $x < count($alquileres); $x++) {
                            if ($alquileres[$x]['mostrar'] == 1) {

                                echo '<tr class="reg_' . $alquileres[$x]['id'] . '">';

                                if ($alquileres[$x]['operacion'] == 'credito') {
                                    if (strpos($alquileres[$x]['concepto'], "Alquiler") === false) {
                                        echo '<td class="centrar">' . $alquileres[$x]['concepto'] . ' ' . $alquileres[$x]['mes'] . ' (con ' . $alquileres[$x]['depositante'] . ')</td>';
                                    } else {
                                        echo '<td class="centrar">' . str_replace("N", " ", $alquileres[$x]['concepto']) . ' ' . $alquileres[$x]['mes'] . ' (con ' . $alquileres[$x]['depositante'] . ')</td>';
                                    }
                                } else {
                                    echo '<td class="centrar">' . $alquileres[$x]['concepto'] . ' ' . $alquileres[$x]['domicilio'] . ' ' . $alquileres[$x]['mes'] . '</td>';
                                }
                                echo '<td class="centrar">' . $alquileres[$x]['fecha'] . '</td>';
                                if ($alquileres[$x]['operacion'] == 'credito') {
                                    echo '<td class="centrar"></td>';
                                    echo '<td class="centrar">$ ' . $alquileres[$x]['monto'] . '</td>';
                                    $saldo += round($alquileres[$x]['monto'], 2);
                                } else {
                                    echo '<td class="centrar">$ ' . $alquileres[$x]['monto'] . '</td>';
                                    echo '<td class="centrar"></td>';
                                    $saldo -= round($alquileres[$x]['monto'], 2);
                                }
                                echo '<td class="centrar">$ ' . round($saldo, 2) . '</td>';

                                echo '</tr>';
                            }
                        }
                        echo '<td class=""></td>';
                        echo '<td style="border: 1px solid;font-weight: bold;text-align: center;" class="">Saldo Alquileres</td>';
                        echo '<td class="centrar"> $ ' . round($salida_prin, 2) . '</td>';
                        echo '<td class="centrar"> $ ' . round($entrada_prin, 2) . '</td>';
                        echo '<td class="centrar">$ ' . round($saldo, 2) . '</td>';
                    } else {
                        echo '<div id="no_mov">No se registraron movimientos en las fechas indicadas</div>';
                    }
                    ?>
                </tr>
            </table>
            <h2 style="margin-bottom: 22px;margin-top: 17px;font-size: 16px;text-decoration: underline;">Movimientos Cta. Secundaria</h2>
            <table class="table">
                <tr>    

                    <th class="centrar">Concepto</th>
                    <th class="centrar">Domicilio</th>
                    <th class="centrar">Fecha</th>
                    <th class="centrar">Debitos</th>
                    <th class="centrar">Creditos</th>
                    <th class="centrar">Saldo</th>

                    <?
                    $r = 0;
                    $saldovarios = 0;
                    if (count($varios) > 0) {
                        for ($x = 0; $x < count($varios); $x++) {
                            echo '<tr class="reg_' . $varios[$x]['id'] . '">';
                            if ($varios[$x]['operacion'] == 'credito') {
                                echo '<td class="centrar">' . $varios[$x]['concepto'] . ' (' . $varios[$x]['mes'] . '-' . $varios[$x]['depositante'] . ')</td>';
                            } else {
                                echo '<td class="centrar">' . $varios[$x]['concepto'] . ' ' . $varios[$x]['mes'] . '</td>';
                            }
                            echo '<td class="centrar">' . $varios[$x]['domicilio'] . '</td>';
                            echo '<td class="centrar">' . $varios[$x]['fecha'] . '</td>';
                            if ($varios[$x]['operacion'] == 'credito') {
                                echo '<td class="centrar"></td>';
                                echo '<td class="centrar">$ ' . $varios[$x]['monto'] . '</td>';
                                $saldovarios += round($varios[$x]['monto'], 2);
                            } else {
                                echo '<td class="centrar">$ ' . $varios[$x]['monto'] . '</td>';
                                echo '<td class="centrar"></td>';
                                $saldovarios -= round($varios[$x]['monto'], 2);
                            }
                            echo '<td class="centrar">$ ' . round($saldovarios, 2) . '</td>';
                            echo '</tr>';
                        }
                        echo '<td class=""></td>';
                        echo '<td class=""></td>';
                        echo '<td style="border: 1px solid;font-weight: bold;text-align: center;" class="">Saldo Expensas y Otros</td>';
                        echo '<td class="centrar"> $ ' . round($salida_sec, 2) . '</td>';
                        echo '<td class="centrar"> $ ' . round($entrada_sec, 2) . '</td>';
                        echo '<td class="centrar">$ ' . round($saldovarios, 2) . '</td>';
                    } else {
                        echo '<div  id="no_mov">No se registraron movimientos en las fechas indicadas</div>';
                        echo '<tr class="">';
                        echo '<td style="border: 1px solid;font-weight: bold;text-align: center;height: 23px;"></td>';
                        echo '<td style="border: 1px solid;font-weight: bold;text-align: center;height: 23px;"></td>';
                        echo '<td style="border: 1px solid;font-weight: bold;text-align: center;height: 23px;"></td>';
                        echo '<td style="border: 1px solid;font-weight: bold;text-align: center;height: 23px;"></td>';
                        echo '<td style="border: 1px solid;font-weight: bold;text-align: center;height: 23px;"></td>';
                        echo '<td style="border: 1px solid;font-weight: bold;text-align: center;height: 23px;"></td>';
                        echo '</tr>';

                        echo '<tr class="">';
                        echo '<td class="" style="height: 34px;border: 0px;"></td>';

                        echo '</tr>';
                        echo '<tr class="">';
                        echo '<td style="border: 1px solid;font-weight: bold;text-align: center;">Saldo del periodo: $ ' . round($saldo + $saldovarios, 2) . '</td>';
                        echo '<td style="border: 1px solid;font-weight: bold;text-align: center;">Saldo Operativo: $ ' . round($prop['cc_saldo'] + $prop['cc_varios'], 2) . '</td>';

                        echo '</tr>';
                        $r = 1;
                    }
                    if ($r == 0) {
                        echo '<tr class="">';
                        echo '<td class="" style="height: 34px;border: 0px;"></td>';

                        echo '</tr>';
                        echo '<tr class="">';
                        echo '<td style="border: 1px solid;font-weight: bold;text-align: center;">Saldo del periodo: $ ' . round($saldo + $saldovarios, 2) . '</td>';
                        echo '<td style="border: 1px solid;font-weight: bold;text-align: center;">Saldo Operativo: $ ' . round($prop['cc_saldo'] + $prop['cc_varios'], 2) . '</td>';

                        echo '</tr>';
                    }
                    ?>
                </tr> 
            </table>

            <div id="non-printable">
                <? foreach ($contratos->result_array() as $contrato) { ?>   
                    <div class="cabeza">Contrato con <?= $contrato['con_inq'] ?> en: <?= $contrato['con_domi'] ?></div>
                    <table class="table">
                        <tr>    
                            <th class="centrar">Concepto</th>
                            <th class="centrar">Pagado</th>
                            <th class="centrar">Fecha Pago</th>
                            <th class="centrar">Mes Pagado</th>
                            <?
                            foreach ($servicios->result_array() as $servicio) {
                                if ($servicio['serv_contrato'] == $contrato['con_id']) {
                                    $entro = false;
                                    for ($x = 0; $x < count($varios); $x++) {
                                        if (isset($varios[$x]['depositante'])) {
                                            if ($varios[$x]['depositante'] == $contrato['con_inq'] && $varios[$x]['operacion'] == 'credito' && $varios[$x]['concepto'] == $servicio['serv_concepto']) {
                                                echo '<tr class="reg_' . $servicio['serv_id'] . '">';
                                                echo '<td class="centrar">' . $servicio['serv_concepto'] . '</td>';
                                                echo '<td class="centrar">Si</td>';
                                                echo '<td class="centrar">' . $varios[$x]['fecha'] . '</td>';
                                                echo '<td class="centrar">' . $varios[$x]['mes'] . '</td>';
                                                echo '</tr>';
                                                $entro = true;
                                                /* Pago */
                                            }
                                        }
                                    }
                                    if (!$entro) {
                                        if ($servicio['serv_accion'] == 'Pagar') {
                                            echo '<tr class="reg_' . $servicio['serv_id'] . '">';
                                            echo '<td class="centrar">' . $servicio['serv_concepto'] . '</td>';
                                            echo '<td class="centrar">No</td>';
                                            echo '<td class="centrar">' . '-' . '</td>';
                                            echo '<td class="centrar">' . '-' . '</td>';
                                            echo '</tr>';
                                            /* No Pago */
                                        } else {
                                            echo '<tr class="reg_' . $servicio['serv_id'] . '">';
                                            echo '<td class="centrar">' . $servicio['serv_concepto'] . '</td>';
                                            echo '<td class="centrar"> Solo Controlar</td>';
                                            echo '<td class="centrar">' . '-' . '</td>';
                                            echo '<td class="centrar">' . '-' . '</td>';
                                            echo '</tr>';
                                            /* Solo se controla */
                                        }
                                    }
                                }
                                $entro = false;
                            }
                            $entro1 = false;
                            for ($x = 0; $x < count($alquileres); $x++) {
                                if (isset($alquileres[$x]['depositante'])) {
                                    if ($alquileres[$x]['depositante'] == $contrato['con_inq'] && $alquileres[$x]['operacion'] == 'credito') {
                                        $pos = strpos($alquileres[$x]['concepto'], "Alquiler");
                                        $pos2 = strpos($alquileres[$x]['concepto'], "Loteo");
                                        if ($pos === false && $pos2 === false) {
                                            
                                        } else {
                                            $entro1 = true;
                                            echo '<tr class="reg_' . $alquileres[$x]['id'] . '">';
                                            echo '<td class="centrar">' . str_replace("N", " ", $alquileres[$x]['concepto']) . '</td>';
                                            echo '<td class="centrar">Si</td>';
                                            echo '<td class="centrar">' . $alquileres[$x]['fecha'] . '</td>';
                                            echo '<td class="centrar">' . $alquileres[$x]['mes'] . '</td>';
                                            echo '</tr>';
                                            break;
                                            /* Pago */
                                        }
                                    }
                                }
                            }
                            if (!$entro1) {
                                echo '<tr class="reg_">';
                                echo '<td class="centrar">Alquiler</td>';
                                echo '<td class="centrar">No</td>';
                                echo '<td class="centrar">-</td>';
                                echo '<td class="centrar">-</td>';
                                echo '</tr>';
                                /* No Pago */
                            }
                            $entro1 = false;
                            ?>
                        </tr>
                    </table>
                <? } ?>

                <div class="cabeza">Intereses en Mora</div>
                <table class="table">
                    <tr>    
                        <th class="centrar">Inquilino</th>
                        <th class="centrar">Fecha Pago Alquiler</th>
                        <?
                        for ($x = 0; $x < count($intereses_mora); $x++) {
                            echo '<tr class="reg_' . $intereses_mora[$x]['int_id'] . '">';
                            echo '<td class="centrar">' . $intereses_mora[$x]['int_depositante'] . '</td>';
                            echo '<td class="centrar">' . $intereses_mora[$x]['int_fecha_pago'] . '</td>';
                            echo '</tr>';
                        }
                        ?>
                    </tr>
                </table>
            </div>
            <div class="comentarios">
                <table class="table">
                    <tr>    
                        <th class="centrar">Fecha</th>
                        <th class="centrar">Inmueble</th>
                        <th class="centrar">Comentario</th>
                        <?
                        if ($comentarios->num_rows() > 0) {
                            foreach ($comentarios->result_array() as $com) {
                                echo '<tr class="reg_' . $com['com_id'] . '">';
                                echo '<td class="centrar">' . '[' . $com['com_date'] . ']' . '</td>';
                                echo '<td class="centrar">' . $com['com_dom'] . '</td>';
                                echo '<td class="centrar">' . $com['com_com'] . '</td>';
                                echo '</tr>';
                            }
                        } else {
                            echo '<div id="no_mov">No se registraron comentarios</div>';
                        }
                        ?>
                    </tr>
                </table>
            </div>
            <div style="margin: 46px auto 0;width: 87%;" class="leyenda">
                Recibimos conforme de ANDRES DAVIÑA INMOBILIARIA, la cantidad de Pesos (<?= '$ ' . $monto_rendicion_hoy . ') ' . $monto_rendicion_hoy_letra ?>
                en concepto de rendición de cuenta por la cobranza de alquileres, habiendo verificado los comprobantes de ingresos y egresos del inmueble/s
                sito domicilio/os <?= $monto_rendicion_domis ?>
                correspondiente al mes/es de <?= $monto_rendicion_meses ?>
            </div>
            <span style=" float: left;margin-left: 15px;margin-top: 26px;">Firma: ________________________</span>
            <span style=" float: left;margin-left: 15px;margin-top: 26px;"><?= $cuenta ?></span>
        </div>      
    </div>   
    <div id="non-printable">
        <input id="deb" class="btn btn-primary" style="margin-bottom: 15px; margin-top: 22px;float: right;font-family: 'futura';text-transform: inherit;
               font-size: 20px;" type="button" value="Imprimir Informe" onclick="window.print();return//setTimeout(redirect, 10000); false;" />
        <a id="buttons_cli" style=" margin-top: 23px;" class="btn" href="<?= site_url('manager/reporte_prop') ?>"><?= 'Volver' ?></a>
    </div>

</form>
<script>
    var redirect = function redirect(){
        var prop = $('#con_prop').val();    
        request_redirect('<?= site_url() . 'redirect_debitos/' ?>'+prop,'','.contenedor_centro');
    }
</script>    

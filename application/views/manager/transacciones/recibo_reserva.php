<style>
    .table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
        padding: 0 !important;
    }
    body {
        background-color: white;
        padding-top: 60px;
    }
    /*    .contenedor_centro{
            display: block;
           
            margin-left: 0px !important;
            width: 86%;
        }*/
    .dots{    
        clear: both;
        border-bottom: 1px dashed;
        display: block;
        margin-left: 15px;
        margin: 5px auto;
        width: 96%;
    }

    .leyenda{
        font-family: monospace;
        font-size: 14px;
        margin: 5px auto;
        text-align: left;
        width: 96%;
    }

    .hoja_informe{
        background: none repeat scroll 0 0 window;
        margin: 60px auto 0;
        padding-bottom: 60px;
        border:none;
        width: 900px;
    }
    .form{
        margin: 0;
        padding: 0;
    }

</style>
<form style="float:none !important;overflow: visible">

    <div id="printable" style="float:none !important;margin:0 auto;width: 100%">

        <div style="height: auto;float:none !important;" class="hoja_informe_recibo">
            <div class="a" style="float:none !important;margin-top:-65px;">
                <?
                for ($x = 0; $x < count($creditos_recibo_alquiler); $x++) {
                    $total = 0;
                    // PARA SOLUCIONAR EL BUG DE PRINT DE GOOGLE SE PONE A TODOS LOS DIVS CON FLOAT:NONE
                    // LUEGO ALGUNO DE MIS DIVS NECESITABAN FLOATS LEFT O RIGHT Y LOS PUSE MANUALMENTE, IT WORKS!
                    ?>

                    <div class="central">
                        <div class="superior">
                            <div class="leyenda">Ref.: Inmueble <?= $creditos_recibo_alquiler[$x]['cred_domicilio'] ?> <span class="span_fecha" >Fecha: <?= Date('d-m-Y') ?> </span></div>
                            <div class="leyenda">
                                <?= $creditos_recibo_alquiler[$x]['cred_depositante'] ?> 
                                mencionado/s mas abajo entregan la cantidad de <?= '$ ' . $total_transaccion . ' ' . $total_transaccion_letra
                                ?> en concepto de pago de <?= $creditos_recibo_alquiler[$x]['cred_concepto'] ?> correspondiente alquiler mes 
                                de <?= $creditos_recibo_alquiler[$x]['cred_mes_alq'] ?>. 
                                La cobranza de los importes recibidos en concepto de <?= $creditos_recibo_alquiler[$x]['cred_concepto'] ?> se realiza al solo
                                efecto de ser entregada al propietario del inmueble y en un todo de acuerdo al articulo 19 de R.G N° 3803/94
                                La presente ahora tendra una validez de cinco (5) dias habiles a partir de la fecha.-
                            </div>
                            <span class="dots"></span>
                            <div class="leyenda">
                                <div style="width: 85px" class="item_locador">Locador/es</div>
                                <div style="width: 45%"class="item_locador">Nombre: <?= $propietario['client_name'] ?></div>
                                <div class="item_locador">CUIT: <?= $propietario['client_cuit'] ?></div>
                            </div>

                            <span class="dots"></span>
                            <?
                            $adeuda = $creditos_recibo_alquiler[$x]['adeuda'];
                            $gastos_pun = 0;
                            $gastos = 0;
                            for ($i = 0; $i < count($creditos_recibo_servicios); $i++) {
                                if ($creditos_recibo_servicios[$i]['usado_cont'] == 0) {
                                    $gastos_pun += $creditos_recibo_servicios[$i]['cred_interes_calculados'];
                                    $gastos += $creditos_recibo_servicios[$i]['cred_monto'];
                                    $creditos_recibo_servicios[$i]['usado_cont'] = 1;
                                }
                            }
                            $neto = $creditos_recibo_alquiler[$x]['cred_iva_comi'] + $creditos_recibo_alquiler[$x]['cred_iva_alq'] + $creditos_recibo_alquiler[$x]['cred_monto'] + $creditos_recibo_alquiler[$x]['cred_interes_calculados'];
                            $total = $neto + $gastos + $gastos_pun;
                            ?>
                            <div class="leyenda">

                                <div class="item_locador"><?= $creditos_recibo_alquiler[$x]['cred_concepto'] == 'Alquiler Comercial' ? $creditos_recibo_alquiler[$x]['cred_concepto'] : $creditos_recibo_alquiler[$x]['cred_concepto'] . '.........' ?>: $ <?= $creditos_recibo_alquiler[$x]['cred_monto'] ?></div>
                                <div style="" class="item_locador">Neto a cobrar..: $ <?= $neto ?></div>
                                <div class="item_locador" style="float:right !important">Gastos.....: $ <?= $gastos ?></div>
                                <div style="clear: both" class="item_locador">Punitorios......: $ <?= $creditos_recibo_alquiler[$x]['cred_interes_calculados'] ?></div>
                                <div class="item_locador">Efectivo.......: $ <?= $creditos_recibo_alquiler[$x]['cred_forma'] == 'Efectivo' ? $creditos_recibo_alquiler[$x]['cred_interes_calculados'] + $creditos_recibo_alquiler[$x]['cred_monto'] + $creditos_recibo_alquiler[$x]['cred_iva_comi'] + $creditos_recibo_alquiler[$x]['cred_iva_alq'] : 0.00 ?></div>
                                <div style="float: right;margin-left: 164px;margin-right: 0;"class="item_locador">Adeuda.....: $ <?= $adeuda ?></div>
                                <div style="clear:both" class="item_locador">I.V.A...........: $ <?= $creditos_recibo_alquiler[$x]['cred_concepto'] == 'Comision' ? $creditos_recibo_alquiler[$x]['cred_iva_comi'] : $creditos_recibo_alquiler[$x]['cred_iva_alq'] ?></div>
                                <div style="widht:250px !important;" class="item_locador">Cheque..: $ <?= $creditos_recibo_alquiler[$x]['cred_forma'] == 'Efectivo' ? 0.00 : $creditos_recibo_alquiler[$x]['cred_interes_calculados'] + $creditos_recibo_alquiler[$x]['cred_iva_comi'] + $creditos_recibo_alquiler[$x]['cred_iva_alq'] + $creditos_recibo_alquiler[$x]['cred_monto'] . ' N° ' . $creditos_recibo_alquiler[$x]['cred_nro_cheque'] . ' ' . $creditos_recibo_alquiler[$x]['cred_banco'] ?></div>
                                <div class="item_locador" style="clear:both;float:right !important;margin-top: -19px">Cobrado....: $ <?= $creditos_recibo_alquiler[$x]['cred_iva_comi'] + $creditos_recibo_alquiler[$x]['cred_iva_alq'] + $creditos_recibo_alquiler[$x]['cred_monto'] + $creditos_recibo_alquiler[$x]['cred_interes_calculados'] + $gastos ?></div>
                            </div>


                            <span class="dots"></span>
                            <table style="margin: 0px auto;width: 96%;">
                                <? if ($gastos != 0) { ?>
                                    <tr>
                                        <td class="item_tabla">Mes pagado</td>     
                                        <td class="item_tabla">Concepto</td>     
                                        <td class="item_tabla">Monto</td>     
                                        <td class="item_tabla">Intereses</td>     
                                    </tr>
                                    <?
                                }
                                $count = 0;
                                for ($i = 0; $i < count($creditos_recibo_servicios); $i++) {
                                    if ($creditos_recibo_servicios[$i]['usado'] == 0) {
                                        $count++;
                                        $creditos_recibo_servicios[$i]['usado'] = 1;
                                        ?>
                                        <tr>
                                            <td class="item_tabla"><?= $creditos_recibo_servicios[$i]['cred_mes_alq'] ?></td>
                                            <td class="item_tabla"><?= $creditos_recibo_servicios[$i]['cred_concepto'] ?></td>
                                            <td class="item_tabla"><?= '$ ' . $creditos_recibo_servicios[$i]['cred_monto'] ?></td>
                                            <td class="item_tabla"><?= '$ ' . $creditos_recibo_servicios[$i]['cred_interes_calculados'] ?></td>
                                        </tr>
                                        <?
                                    }
                                }
                                $total = $creditos_recibo_alquiler[$x]['cred_iva_comi'] + $creditos_recibo_alquiler[$x]['cred_iva_alq'] + $creditos_recibo_alquiler[$x]['cred_monto'] + $gastos + $gastos_pun + $creditos_recibo_alquiler[$x]['cred_interes_calculados'];
                                ?>
                            </table>
                        </div>

                        <div class="total_global">
                            <?= '$ ' . $total ?>
                        </div>
                    </div>
                <? } ?>
            </div>   
        </div>
    </div>

    <div style="clear: both;float: right;margin-bottom: 10px;margin-top: 103px;"id="non-printable">
        <input id="deb" class="btn btn-primary" style="margin-bottom: 15px; margin-top: 22px;float: right;font-family: 'futura';text-transform: inherit;
 font-size: 20px;" type="button" value="Imprimir Informe" onclick="window.print();return false;" />
    </div>

</form>


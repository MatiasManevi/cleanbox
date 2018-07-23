<div class="canvas_receive">

<?php if (User::printCopy()) { ?>
<link rel="stylesheet" href="<?php echo asset_url() ?>css/receive_with_copy.css?<?php echo filemtime('application/assets/css/receive_with_copy.css') ?>"/>
<?php }else{ ?>
<link rel="stylesheet" href="<?php echo asset_url() ?>css/receive.css?<?php echo filemtime('application/assets/css/receive.css') ?>"/>
<?php } ?>


<div id="printable">
    <?php 

    if($settings['print_copy']) { 
        $times = 2;
     } else {
        $times = 1;
     }
    for ($i = 0; $i < $times; $i++) { 
        if($i == 0){?>
        <div class="original">
        <?php }
        foreach ($receives as $receive) {

            ?>
            <div class="receive">
                
                <?php if($settings['build_receive_header']){ ?>
                    <div class="receive_header">

                        <div class="left_header">
                            <img class="_image_logo" src="<?php echo img_url() . 'bussines_logos/' . $settings['logo'] ?>" alt="logo"/>
                            <!-- <p><strong><?php echo $settings['activity']; ?></strong></p> -->
                            <p><?php echo $settings['address'] . ' - Tel: ' . $settings['phone'] . ' - Cel: ' . $settings['cel_phone']; ?></p>
                            <p><?php echo $settings['zip_code'] . ' ' . $settings['city'] . ' - ' . $settings['state'] . ' - ' . $settings['site_url']; ?></p>
                            <p><?php echo 'e-mail: ' . $settings['email']; ?></p>
                        </div>

                        <div class="center_header">
                            <div class="receive_type">X</div>
                            <div class="type_label">DOCUMENTO NO VALIDO COMO FACTURA</div>
                        </div>

                        <div class="right_header">
                            <p><strong><?php echo $settings['fiscal_status']; ?></strong></p>
                            <p>CUIT: <?php echo $settings['cuit']; ?></p>
                            <p>Ing. Brutos: <?php echo $settings['iibb_number']; ?></p>
                            <p>Fecha INIC. ACT.: <?php echo $settings['init_activity_date'] . '       '?><strong class="recibo">RECIBO</strong></p>
                        </div>

                    </div>
                <?php } ?>

                <table class="receive_table">
                    <?php if ($receive['principal_credit']['cred_concepto'] != 'Reserva' && $receive['exist_rent_credit']) { ?>
                        <tr>
                            <td colspan="5">Ref.: contrato de <?php echo $contract['con_tipo'] ?> del inmueble <?php echo $contract['con_domi'] ?></td>
                            <td colspan="2" class="alignRight"><?php echo Date('d-m-Y') ?></td>
                        </tr>

                        <tr>
                            <td colspan="7">
                            Recibimos de <?php echo $contract['con_inq'] ?>, por cuenta y orden del/os <?php echo $contract['con_tipo'] == 'Loteo' ? 'vendedor/es' : 'locador/es' ?> la cantidad de
                                $ <?php echo $receive['total'] . ' ' . $receive['total_letters'] ?> en concepto de pago de <?php echo $receive['principal_credit']['cred_concepto'] ?> y Gastos
                                correspondiente al mes de <?php echo $receive['principal_credit']['cred_mes_alq'] ?>.

                                <?php if ($receive['principal_credit']['cred_concepto'] != 'Honorarios') { ?>
                                    La cobranza de los importes recibidos en concepto de <?php echo $contract['con_tipo'] ?> se realiza al solo
                                    efecto de ser entregada al/los <?php echo $contract['con_tipo'] == 'Loteo' ? 'vendedor/es' : 'locador/es' ?> del inmueble y en un todo de acuerdo al articulo 19 de R.G N&#176; 3803/94
                                <?php } ?>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="4"><?php echo $contract['con_tipo'] == 'Loteo' ? 'Vendedor/es' : 'Locador/es' ?>: <?php echo $propietary['client_name'] ?></td>
                            <td colspan="2">CUIT: <?php echo $propietary['client_cuit'] ?></td>
                            <td colspan="1">Fin de Contrato: <?php echo $contract['con_venc'] ?></td>
                        </tr>
                   
                    <?php } else if (!$receive['exist_rent_credit']) { ?>
                        <tr>
                            <td colspan="4">Ref.: Inmueble <?php echo $receive['principal_credit']['cred_domicilio'] ?> </td>
                            <td colspan="2" class="alignRight"><?php echo Date('d-m-Y') ?></td>
                        </tr>

                        <tr>
                            <td colspan="7">
                                Recibimos de <?php echo $receive['principal_credit']['cred_depositante'] ?>, la cantidad de
                                $ <?php echo $receive['total'] . ' ' . $receive['total_letters'] ?> en conceptos detallados a continuación.
                            </td>
                        </tr>
                        
                        <tr>
                            <td colspan="4"><?php echo $contract['con_tipo'] == 'Loteo' ? 'Vendedor/es' : 'Locador/es' ?>: <?php echo $propietary['client_name'] ?></td>
                            <td colspan="2">CUIT: <?php echo $propietary['client_cuit'] ?></td>
                            <td colspan="1">Fin de Contrato: <?php echo $contract['con_venc'] ?></td>
                        </tr>
                    <?php } else { ?>
                        <tr>
                            <td colspan="4">Ref.: Inmueble <?php echo $receive['principal_credit']['cred_domicilio'] ?> </td>
                            <td colspan="3" class="alignRight"><?php echo Date('d-m-Y') ?></td>
                        </tr>

                        <tr>
                            <td colspan="7">
                                <?php echo $receive['principal_credit']['cred_depositante'] ?> 
                                mencionado/s mas abajo entregan la cantidad de <?php echo '$ ' . $receive['total'] . ' ' . $receive['total_letters']
                                ?> en concepto de pago de <?php echo $receive['principal_credit']['cred_concepto'] ?> correspondiente alquiler mes 
                                de <?php echo $receive['principal_credit']['cred_mes_alq'] ?>. 
                                La cobranza de los importes recibidos en concepto de <?php echo $receive['principal_credit']['cred_concepto'] ?> se realiza al solo
                                efecto de ser entregada al propietario del inmueble y en un todo de acuerdo al articulo 19 de R.G N° 3803/94
                                La presente ahora tendra una validez de cinco (5) dias habiles a partir de la fecha.-
                            </td>
                        </tr>

                        <tr>
                            <td class="receive_cel">Locador/es: <?php echo $propietary['client_name'] ?></td>
                            <td class="receive_cel">CUIT: <?php echo $propietary['client_cuit'] ?></td>
                        </tr>
                    <?php } ?>

                    <tr>
                        <td colspan="7"></td>
                    </tr>

                    <tr>
                        <th class="receive_cel"></th>
                        <th class="receive_cel" colspan="2">Monto</th>
                        <th class="receive_cel" colspan="2">Punitorios</th>
                        <th class="receive_cel" colspan="2">IVA</th>
                    </tr>

                    <tr>
                        <th class="receive_cel">Concepto</th>    
                        <th class="receive_cel">Efectivo</th>    
                        <th class="receive_cel">Cheque</th>    
                        <th class="receive_cel">Efectivo</th>    
                        <th class="receive_cel">Cheque</th>    
                        <th class="receive_cel">Efectivo</th>    
                        <th class="receive_cel">Cheque</th>    
                    </tr>

                    <tr>
                        <?php
                        if ($receive['principal_credit']['cred_forma'] == 'Efectivo') {
                            $form = 'Efectivo';
                        } else {
                            $form = 'Cheque';
                        }

                        if ($receive['principal_credit']['cred_tipo_trans'] == 'Bancaria') {
                            $deposit = ' (Deposito Banco)';
                        } else {
                            $deposit = '';
                        }
                        ?>
                        <td class="receive_cel"><?php echo $receive['principal_credit']['cred_concepto'] . ' ' . $receive['principal_credit']['cred_mes_alq']; ?></td>

                        <td class="receive_cel"><?php echo $form == 'Efectivo' ? '$ ' . $receive['principal_credit']['cred_monto'] . $deposit : ''; ?></td>
                        <td class="receive_cel"><?php echo $form == 'Cheque' ? '$ ' . $receive['principal_credit']['cred_monto'] . ' N°' . $receive['principal_credit']['cred_nro_cheque'] . $deposit : ''; ?></td>

                        <td class="receive_cel"><?php echo $form == 'Efectivo' && $receive['principal_credit']['cred_interes_calculado'] ? '$ ' . $receive['principal_credit']['cred_interes_calculado'] . $deposit : ''; ?></td>
                        <td class="receive_cel"><?php echo $form == 'Cheque' && $receive['principal_credit']['cred_interes_calculado'] ? '$ ' . $receive['principal_credit']['cred_interes_calculado'] . ' N°' . $receive['principal_credit']['cred_nro_cheque'] . $deposit : ''; ?></td>

                        <td class="receive_cel"><?php echo $form == 'Efectivo' && $receive['principal_credit']['cred_iva_calculado'] ? '$ ' . $receive['principal_credit']['cred_iva_calculado'] . $deposit : ''; ?></td>
                        <td class="receive_cel"><?php echo $form == 'Cheque' && $receive['principal_credit']['cred_iva_calculado'] ? '$ ' . $receive['principal_credit']['cred_iva_calculado'] . ' N°' . $receive['principal_credit']['cred_nro_cheque'] . $deposit : ''; ?></td>
                    </tr>

                    <?php if (!empty($receive['principal_credit']['other_principal'])) { ?>
                        <?php foreach ($receive['principal_credit']['other_principal'] as $other_principal) { ?>
                            <?php
                            if ($other_principal['cred_forma'] == 'Efectivo') {
                                $form = 'Efectivo';
                            } else {
                                $form = 'Cheque';
                            }

                            if ($other_principal['cred_tipo_trans'] == 'Bancaria') {
                                $deposit = ' (Deposito Banco)';
                            } else {
                                $deposit = '';
                            }
                            ?>        
                            <tr>
                                <td class="receive_cel"><?php echo $other_principal['cred_concepto'] . ' ' . $other_principal['cred_mes_alq']; ?></td>

                                <td class="receive_cel"><?php echo $form == 'Efectivo' ? '$ ' . $other_principal['cred_monto'] . $deposit : ''; ?></td>
                                <td class="receive_cel"><?php echo $form == 'Cheque' ? '$ ' . $other_principal['cred_monto'] . ' N°' . $other_principal['cred_nro_cheque'] . $deposit : ''; ?></td>

                                <td class="receive_cel"><?php echo $form == 'Efectivo' && $other_principal['cred_interes_calculado'] ? '$ ' . $other_principal['cred_interes_calculado'] . $deposit : ''; ?></td>
                                <td class="receive_cel"><?php echo $form == 'Cheque' && $other_principal['cred_interes_calculado'] ? '$ ' . $other_principal['cred_interes_calculado'] . ' N°' . $other_principal['cred_nro_cheque'] . $deposit : ''; ?></td>

                                <td class="receive_cel"><?php echo $form == 'Efectivo' && $other_principal['cred_iva_calculado'] ? '$ ' . $other_principal['cred_iva_calculado'] . $deposit : ''; ?></td>
                                <td class="receive_cel"><?php echo $form == 'Cheque' && $other_principal['cred_iva_calculado'] ? '$ ' . $other_principal['cred_iva_calculado'] . ' N°' . $other_principal['cred_nro_cheque'] . $deposit : ''; ?></td>
                            </tr>
                        <?php } ?>
                    <?php } ?>

                    <?php if (!empty($receive['secondary_credits'])) { ?>
                        <?php foreach ($receive['secondary_credits'] as $secondary) { ?>
                            <?php if ($secondary['cred_concepto'] != 'Intereses' && $secondary['cred_concepto'] != 'IVA') { ?>
                            <?php
                                if ($secondary['cred_forma'] == 'Efectivo') {
                                    $form = 'Efectivo';
                                } else {
                                    $form = 'Cheque';
                                }

                                if ($secondary['cred_tipo_trans'] == 'Bancaria') {
                                    $deposit = ' (Deposito Banco)';
                                } else {
                                    $deposit = '';
                                }
                                ?>
                                <tr>
                                    <td class="receive_cel"><?php echo $secondary['cred_concepto'] . ' ' . $secondary['cred_mes_alq'] ?></td>

                                    <td class="receive_cel"><?php echo $secondary['cred_forma'] == 'Efectivo' ? '$ ' . $secondary['cred_monto'] . $deposit : ''; ?></td>
                                    <td class="receive_cel"><?php echo $secondary['cred_forma'] == 'Cheque' ? '$ ' . $secondary['cred_monto'] . $deposit : ''; ?></td>

                                    <td class="receive_cel"><?php echo $form == 'Efectivo' && $secondary['cred_interes_calculado'] ? '$ ' . $secondary['cred_interes_calculado'] . $deposit : ''; ?></td>
                                    <td class="receive_cel"><?php echo $form == 'Cheque' && $secondary['cred_interes_calculado'] ? '$ ' . $secondary['cred_interes_calculado'] . $deposit : ''; ?></td>

                                    <td class="receive_cel"></td>
                                    <td class="receive_cel"></td>
                                </tr>                        
                            <?php } ?>
                        <?php } ?>
                    <?php } ?>

                    <?php if (!empty($receive['services_control'])) { ?>
                        <?php $presented_bills = ''; ?>
                        <tr>
                            <?php
                            foreach ($receive['services_control'] as $key => $service_control) {
                                if (count($receive['services_control']) - 1 == $key || count($receive['services_control']) == 1) {
                                    // ultimo o unico elemento
                                    $presented_bills .= $service_control['service'] . ' ' . $service_control['month_checked'];
                                } else {
                                    $presented_bills .= $service_control['service'] . ' ' . $service_control['month_checked'] . ', ';
                                }
                            }
                            ?>
                            <th class="receive_cel">Boletas presentadas</th>
                            <td colspan="6" class="receive_cel"><?php echo $presented_bills; ?></td>
                        </tr>  
                    <?php } ?>
                    <?php if (!empty($receive['services_no_control'])) { ?>
                        <?php $pending_bills = ''; ?>
                        <tr>
                            <?php
                            foreach ($receive['services_no_control'] as $key => $service_control) {
                                if (count($receive['services_no_control']) - 1 == $key || count($receive['services_no_control']) == 1) {
                                    // ultimo o unico elemento
                                    $pending_bills .= $service_control['service'] . ' ' . $service_control['month_checked'];
                                } else {
                                    $pending_bills .= $service_control['service'] . ' ' . $service_control['month_checked'] . ', ';
                                }
                            }
                            ?>
                            <th class="receive_cel">Boletas pendientes</th>
                            <td colspan="6" class="receive_cel"><?php echo $pending_bills; ?></td>
                        </tr>  
                    <?php } ?>

                    <?php if ($receive['debt'] > 0) { ?>
                        <tr>
                            <th class="receive_cel">Adeuda</th>
                            <td class="receive_cel"><?php echo '$ ' . $receive['debt']; ?></td>
                        </tr>                       
                    <?php } ?>
                    
                    <tr>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                    </tr>
                    
                    <tr>
                        <td colspan="7" rowspan="5" class="alignRight total_receive">$ <?php echo $receive['total'] ?></td>
                    </tr>

                </table>
            </div>
        <?php } ?>
       <?php if($i == 0){ ?>
            </div>
       <?php } ?>
    <?php } ?>
</div>

</div>

<div id="non-printable" class="report_actions">
    <a class="btn btn-primary button_report" href="javascript:;" onclick="window.print();return false;">Imprimir</a>
    <a class="btn btn-primary button_report" href="<?php echo site_url('credits') ?>">Volver</a>
</div>

<script type="text/javascript">
    $(function(){
        // solo estara TRUE al momento de la creacion del credito
        var mail_receive = <?php echo isset($mail_receive) ? json_decode($mail_receive) : 0; ?>;

        if(mail_receive && email_receive_renter){
            $('.original').css('background', 'white');

            var div_content = document.querySelectorAll(".original");

            html2canvas(div_content).then(function(canvas) {
                //change the canvas to jpeg image
                var receive_image = canvas.toDataURL('image/jpeg');
                
                $.post(email_receive_renter_url, {data: receive_image});
            });
        }
    });
</script>
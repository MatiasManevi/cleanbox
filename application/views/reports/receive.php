<link rel="stylesheet" href="<?php echo asset_url(); ?>css/receive.css?<?php echo filemtime('application/assets/css/receive.css') ?>"/>

<div id="printable">
    <?php
    foreach ($receives as $receive) {
        ?>
        <div class="receive">
            <table id="receive_table">
                <?php if ($receive['principal_credit']['cred_concepto'] != 'Reserva') { ?>
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
                                efecto de ser entregada al/los <?php echo $contract['con_tipo'] == 'Loteo' ? 'vendedor/es' : 'locador/es' ?> del inmueble y en un todo de acuerdo al articulo 19 de R.G N° 3803/94
                            <?php } ?>
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
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
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


                <!--
                                <tr class="dashed">
                                    <td><?php echo $receive['principal_credit']['cred_concepto'] == 'Alquiler Comercial' ? $receive['principal_credit']['cred_concepto'] : $receive['principal_credit']['cred_concepto'] . '........' ?>:</td>
                                    <td>$ <?php echo $receive['principal_credit']['cred_monto'] ?></td>
                                    <td>Neto a cobrar..:</td>
                                    <td>$ <?php echo $receive['total_principal'] ?></td>
                                    <td>Gastos.....:</td>
                                    <td>$ <?php echo $receive['total_secondarys'] ?></td>
                                </tr>
                
                                <tr>
                                    <td>Punitorios......:</td>
                                    <td>$ <?php echo $receive['principal_credit']['cred_interes_calculado'] ? $receive['principal_credit']['cred_interes_calculado'] : '0.00' ?></td>
                                    <td>Efectivo.......:</td>
                                    <td>$ <?php echo $receive['principal_credit']['cred_forma'] == 'Efectivo' ? $receive['total_principal'] : '0.00' ?></td>
                                    <td>Adeuda.....:</td>
                                    <td>$ <?php echo $receive['debt'] ? $receive['debt'] : '0.00'; ?></td>
                                </tr>
                
                                <tr>
                                    <td>I.V.A...........:</td>
                                    <td>$ <?php echo $receive['principal_credit']['cred_iva_calculado'] ? $receive['principal_credit']['cred_iva_calculado'] : '0.00' ?></td>
                                    <td>Cheque..:</td>
                                    <td>$ <?php echo $receive['principal_credit']['cred_forma'] == 'Efectivo' ? '0.00' : $receive['total_principal'] . ' N° ' . $receive['principal_credit']['cred_nro_cheque'] . ' ' . $receive['principal_credit']['cred_banco'] ?></td>
                                    <td>Cobrado....:</td>
                                    <td>$ <?php echo $receive['total'] ?></td>
                                </tr>
                -->

                <tr><td></td><tr>
                <tr><td></td><tr>

                <tr>
                    <td colspan="7" class="alignRight total_receive">$ <?php echo $receive['total'] ?></td>
                </tr>

            </table>
        </div>
    <?php } ?>
</div>

<div id="non-printable" class="report_actions">
    <a class="btn btn-primary button_report" href="javascript:;" onclick="window.print();return false;">Imprimir</a>
    <a class="btn btn-primary button_report" href="<?php echo site_url('credits') ?>">Volver</a>
</div>
<link rel="stylesheet" href="<?php echo asset_url(); ?>css/receive.css?<?php echo filemtime('application/assets/css/receive.css') ?>"/>

<div id="printable">

    <div class="receive">

        <?php if($settings['build_receive_header']){ ?>
        <div class="receive_header">
            <div class="left_header">
                <img class="_image_logo" src="<?php echo img_url() . 'bussines_logos/' . $settings['logo'] ?>" alt="logo"/>
                <p><?php echo $settings['address'] . ' - Tel: ' . $settings['phone'] . ' - Cel: ' . $settings['cel_phone']; ?></p>
                <p><?php echo 'e-mail: ' . $settings['email']; ?></p>
            </div>

            <div class="center_header" style="text-align: center;">
                <p><strong class="recibo">Comprobante debitos</strong></p>
            </div>

            <div class="right_header" style="text-align: right;margin-top: 86px;margin-left: 4px;">
                <p><strong class="recibo">Fecha impresion: <?php echo Date('d-m-Y') ?></strong></p>
                <p><strong class="recibo">Fecha transaccion: <?php echo $receives['debits'][0]['deb_fecha'] ?></strong></p>
            </div>
        </div>
        <?php } ?>

        <table id="receive_table" style="width: 100%">
            <tr>
                <td colspan="9" class="receive_cel">Cuenta corriente: <?php echo $receives['account']['cc_prop'] ?></td>
            </tr>
            <tr>
                <td colspan="9"></td>
            </tr>
            <tr>
                <th class="receive_cel" colspan="9"></th>
                <th class="receive_cel" colspan="9">Monto</th>
            </tr>
            <tr>
                <th class="receive_cel" colspan="9">Concepto</th>    
                <th class="receive_cel" colspan="6">Efectivo</th>    
                <th class="receive_cel" colspan="6">Cheque</th>    
            </tr>
            <?php
            foreach ($receives['debits'] as $debit) {
                ?>
                <tr>
                    <?php
                    if ($debit['deb_forma'] == 'Efectivo') {
                        $form = 'Efectivo';
                    } else {
                        $form = 'Cheque';
                    }

                    if ($debit['deb_tipo_trans'] == 'Bancaria') {
                        $deposit = ' (Deposito Banco)';
                    } else {
                        $deposit = '';
                    }
                    ?>

                    <td class="receive_cel" colspan="9"><?php echo $debit['deb_concepto'] . ' ' . $debit['deb_mes'] . ' ' . $debit['deb_domicilio']; ?></td>

                    <td class="receive_cel" colspan="6"><?php echo $form == 'Efectivo' ? '$ ' . $debit['deb_monto']. ' ' . $deposit : ''; ?></td>
                    <td class="receive_cel" colspan="6"><?php echo $form == 'Cheque' ? '$ ' . $debit['deb_monto'] . ' ' . $deposit : ''; ?></td>
                </tr>

                <?php } ?>
                <tr><td></td><tr>
                    <tr><td></td><tr>

                        <tr>
                            <td colspan="25" class="alignRight total_receive">$ <?php echo $receives['total'] ?></td>
                        </tr>

                    </table>
                </div>

                <div id="non-printable" class="report_actions">
                    <a class="btn btn-primary button_report" href="javascript:;" onclick="window.print();return false;">Imprimir</a>
                    <a class="btn btn-primary button_report" href="<?php echo site_url('debits') ?>">Volver</a>
                </div>
            </div>

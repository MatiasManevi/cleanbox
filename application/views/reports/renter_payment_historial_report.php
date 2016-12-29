<div id="printable" class="report_sheet _excel">
    <div id="non-printable" class="report_actions">
        <a class="btn btn-primary button_report" href="javascript:;" onclick="window.print();return false;">Imprimir</a>
        <a class="btn btn-primary button_report" href="<?php echo site_url('renterPaymentHistorialReport') ?>">Volver</a>
    </div>
    <h4>Historial de Pagos Año <?php echo $year; ?></h4>
    <h4>Inquilino: <?php echo $renter['client_name']; ?></h4>

    <?php foreach ($contract_movements as $contract_movement) { ?>
        <?php if (count($contract_movement['payments']) > 0) { ?>
            <table class="table" style="margin-bottom: 40px">
                <tr>
                    <th colspan="5" class="cell">Datos contrato</th>
                </tr>
                <tr>
                    <th class="cell">Propietario</th>
                    <th class="cell">Tipo contrato</th>
                    <th class="cell">Domicilio</th>
                    <th class="cell">Vencimiento</th>
                    <th class="cell">Estado</th>
                </tr>
                <tr>
                    <td class="cell"><?php echo $contract_movement['propietary']; ?></td>
                    <td class="cell"><?php echo $contract_movement['type']; ?></td>
                    <td class="cell"><?php echo $contract_movement['address']; ?></td>
                    <td class="cell"><?php echo $contract_movement['decline_date']; ?></td>
                    <td class="cell"><?php echo $contract_movement['enabled'] ? 'Vigente' : 'Vencido'; ?></td>
                </tr>
                <tr>
                    <th colspan="5" class="cell">Historial de pagos del contrato</th>
                </tr>
                <tr>    
                    <th class="cell">Concepto</th>
                    <th class="cell">Fecha de Pago</th>
                    <th class="cell">Mes Pagado</th>
                    <th class="cell">Tipo pago</th>
                    <th class="cell">Monto Pagado</th>
                </tr>
                <?php foreach ($contract_movement['payments'] as $payment) { ?>
                    <tr>
                        <td class="cell"><?php echo $payment['concept']; ?></td>
                        <td class="cell"><?php echo $payment['payment_date']; ?></td>
                        <td class="cell"><?php echo $payment['month_payed']; ?></td>
                        <td class="cell"><?php echo $payment['type']; ?></td>
                        <td class="cell">$ <?php echo $payment['amount']; ?></td>
                    </tr>
                <?php } ?>
            </table>
        <?php } else { ?>
            <div class="alignCenter">No se encontraron registros en la fecha indicada</div>
        <?php } ?>
    <?php } ?>

    <?php if (count($uncontract_payments) > 0) { ?>
        <table class="table">
            <tr>
                <th colspan="5" class="cell">Historial de pagos fuera de contratos</th>
            </tr>
            <tr>    
                <th class="cell">Concepto</th>
                <th class="cell">Fecha de Pago</th>
                <th class="cell">Mes Pagado</th>
                <th class="cell">Tipo pago</th>
                <th class="cell">Monto Pagado</th>
            </tr>
            <?php foreach ($uncontract_payments as $payment) { ?>
                <tr>
                    <td class="cell"><?php echo $payment['concept']; ?></td>
                    <td class="cell"><?php echo $payment['payment_date']; ?></td>
                    <td class="cell"><?php echo $payment['month_payed']; ?></td>
                    <td class="cell"><?php echo $payment['type']; ?></td>
                    <td class="cell">$ <?php echo $payment['amount']; ?></td>
                </tr>
            <?php } ?>
        </table>
    <?php } ?>

</div>

<script>
    $(function(){
        $("._excel").tableExport({
            formats: ["xlsx"],  
            position: 'bottom',
            fileName: "Historial de Pagos Año <?php echo $year; ?> Inquilino: <?php echo $renter['client_name']; ?>"
        });
    });
</script>
<div id="printable" class="report_sheet">

    <h4 style="float:left;">Posadas, Misiones</h4>           
    <h4 style="float:right;"><?php echo date('d-m-Y') ?></h4>
    <?php if ($one_renter){ ?>
        <h4>Informe de deudas al día: <?php echo $date ?></h4>
    <?php } else {?>
        <h4>Informe Inquilinos Morosos al día: <?php echo $date ?></h4>
    <?php } ?>

    <?php if (count($renters) > 0) { ?>
        <?php foreach ($renters as $renter) { ?>
            <table class="table" style="margin-bottom: 40px;">
                <tr>
                    <th colspan="4" class="cell">Inquilino: <?php echo $renter['client_name']; ?></th>
                </tr>    
                <tr>
                    <th colspan="4" class="cell">Contacto: <?php echo $renter['client_celular'] . ' - ' . $renter['client_tel'] ?></th>
                </tr>
                <tr>
                    <th colspan="4" class="cell">Contrato de: <?php echo $renter['type'] ?> - Propietario: <?php echo $renter['propietary'] . ' -  Domicilio: ' . $renter['address'] ?></th>
                </tr>
                <tr>    
                    <th class="cell">Mes debido</th>
                    <th class="cell">Dias en mora</th>
                    <th class="cell">Monto</th>
                    <th class="cell">Intereses</th>
                </tr> 
                <?php if (count($contract_renters_debts) > 0) { ?>
                    <?php foreach ($contract_renters_debts as $key => $contract_renters_debt) { ?>
                        <?php if ($key == $renter['client_id']) { ?>
                            <?php $acumulated_amount = 0; ?>
                            <?php $acumulated_interes = 0; ?>
                            <?php foreach ($contract_renters_debt as $debt) { ?>
                                <tr>
                                    <td class="cell"><?php echo $debt['concept'] . ' ' . $debt['month']; ?></td>
                                    <td class="cell"><?php echo $debt['default_days']; ?></td>
                                    <td class="cell">$ <?php echo $debt['amount']; ?></td>
                                    <td class="cell">$ <?php echo round($debt['intereses'], 2); ?></td>
                                </tr>
                                <?php $acumulated_amount += $debt['amount']; ?>
                                <?php $acumulated_interes += $debt['intereses']; ?>
                            <?php } ?>
                        <?php } ?>
                    <?php } ?>
                <?php } ?>

                <?php if (count($service_renters_debts) > 0) { ?>
                    <?php foreach ($service_renters_debts as $key_services => $debts_services) { ?>
                        <?php if ($renter['client_id'] == $key_services && !empty($debts_services)) { ?>
                            <?php foreach ($debts_services as $debt_service) { ?>
                                <?php foreach ($debt_service as $debt) { ?>
                                    <?php if (!empty($debt)) { ?>
                                        <tr>
                                            <td class="cell"><?php echo $debt['concept'] . ' ' . $debt['month']; ?></td>
                                            <td class="cell"><?php echo $debt['default_days']; ?></td>
                                            <td class="cell"> - </td>
                                            <td class="cell"> - </td>
                                        </tr>
                                    <?php } ?>
                                <?php } ?>
                            <?php } ?>
                        <?php } ?>
                    <?php } ?>
                <?php } ?>

                <?php if (count($control_service_renters_debts) > 0) { ?>
                    <?php foreach ($control_service_renters_debts as $key_services => $debts_services) { ?>
                        <?php if ($renter['client_id'] == $key_services && !empty($debts_services)) { ?>
                            <?php foreach ($debts_services as $debt_service) { ?>
                                <?php foreach ($debt_service as $debt) { ?>
                                    <?php if (!empty($debt)) { ?>
                                        <tr>
                                            <td class="cell"><?php echo 'Control ' . $debt['concept'] . ' ' . $debt['month'] . ' Boleta Pendiente'; ?></td>
                                            <td class="cell"> - </td>
                                            <td class="cell"> - </td>
                                            <td class="cell"> - </td>
                                        </tr>
                                    <?php } ?>
                                <?php } ?>
                            <?php } ?>
                        <?php } ?>
                    <?php } ?>
                <?php } ?>

                <tr>
                    <th colspan="2" class="cell">Sub-totales</th>
                    <td class="cell">$ <?php echo isset($acumulated_amount) ? $acumulated_amount : 0; ?></td>
                    <td class="cell">$ <?php echo isset($acumulated_interes) ? round($acumulated_interes, 2) : 0; ?></td>
                </tr>

                <?php $total = isset($acumulated_amount) && isset($acumulated_interes) ? round($acumulated_amount + $acumulated_interes, 2) : 0 ?>

                <tr>
                    <th colspan="2" class="cell">Total</th>
                    <td colspan="2" class="cell">$ <?php echo $total; ?></td>
                </tr>
            </table>   
        <?php } ?>
    <?php } else { ?>
        <div class="alignCenter">No se encontraron registros en la fecha indicada</div>
    <?php } ?>
</div>      

<div id="non-printable" class="report_actions">
    <a class="btn btn-primary button_report" href="javascript:;" onclick="window.print();return false;">Imprimir</a>
    <a class="btn btn-primary button_report" href="<?php echo site_url('rentersInDefaultReport') ?>">Volver</a>
</div>


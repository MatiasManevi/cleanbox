<div id="printable" class="report_sheet">
    <h4 style="float:left;margin-left: 10px">Posadas, Misiones</h4>           
    <h4 style="float:right;margin-right: 10px;"><?php echo date('d-m-Y') ?></h4>
    <h4>Informe Detallado Cta. Cte. <?php echo $account['cc_prop'] ?></h4>
    <h4>Periodo <?php echo ' (' . $from . ' a ' . $to . ')' ?></h4>

    <h4>Movimientos Cta. Principal</h4>
    <table class="table">
        <tr>    
            <th class="cell">Concepto</th>
            <th class="cell">Fecha</th>
            <th class="cell">Debitos</th>
            <th class="cell">Creditos</th>
            <th class="cell">Saldo</th>
        </tr>
        <?php $sald = 0; ?>
        <?php if (count($principal_movements) > 0) { ?>
            <?php foreach ($principal_movements as $movement) { ?>
                <tr class="reg_<?php echo $movement['id']; ?>">
                    <td class="cell"><?php echo $movement['show_concept']; ?></td>
                    <td class="cell"><?php echo $movement['date']; ?></td>
                    <?php
                    if ($movement['type'] == 'credito') {
                        $sald += $movement['amount'];
                        ?>
                        <td class="cell"></td>
                        <td class="cell">$ <?php echo $movement['amount']; ?></td>
                        <?php
                    } else {
                        $sald -= $movement['amount'];
                        ?>
                        <td class="cell">$ <?php echo $movement['amount']; ?></td>
                        <td class="cell"></td>
                    <?php } ?>
                    <td class="cell">$ <?php echo $sald; ?></td>
                </tr>
            <?php } ?>
            <tr>
                <td colspan="2" class="cell"></td>
                <td class="cell">$ <?php echo $principal_outs; ?></td>
                <td class="cell">$ <?php echo $principal_ins; ?></td>
                <td class="cell">$ <?php echo $sald; ?></td>
            </tr>
        <?php } else { ?>
            <div class="movements_zero">No se registraron movimientos en las fechas indicadas</div>
        <?php } ?>
    </table>

    <h4>Movimientos Cta. Secundaria</h4>
    <table class="table">
        <tr>    
            <th class="cell">Concepto</th>
            <th class="cell">Fecha</th>
            <th class="cell">Debitos</th>
            <th class="cell">Creditos</th>
            <th class="cell">Saldo</th>
        </tr>
        <?php $secondary_sald = 0; ?>
        <?php if (count($secondary_movements) > 0) { ?>
            <?php foreach ($secondary_movements as $movement) { ?>
                <?php if ($movement['trans']) { ?>
                    <tr class="reg_<?php echo $movement['id']; ?>">
                        <td class="cell"><?php echo $movement['show_concept']; ?></td>
                        <td class="cell"><?php echo $movement['date']; ?></td>
                        <?php
                        if ($movement['type'] == 'credito') {
                            $secondary_sald += $movement['amount'];
                            ?>
                            <td class="cell"></td>
                            <td class="cell">$ <?php echo $movement['amount']; ?></td>
                            <?php
                        } else {
                            $secondary_sald -= $movement['amount'];
                            ?>
                            <td class="cell">$ <?php echo $movement['amount']; ?></td>
                            <td class="cell"></td>
                        <?php } ?>
                        <td class="cell">$ <?php echo $secondary_sald; ?></td>
                    </tr>
                <?php } ?>
            <?php } ?>
            <tr>
                <td colspan="2" class="cell"></td>
                <td class="cell">$ <?php echo $secondary_outs; ?></td>
                <td class="cell">$ <?php echo $secondary_ins; ?></td>
                <td class="cell">$ <?php echo $secondary_sald; ?></td>
            </tr>
        <?php } else { ?>
            <div class="movements_zero">No se registraron movimientos en las fechas indicadas</div>
        <?php } ?>
    </table>

    <h4>Estado de cuenta</h4>
    <table class="table">
        <tr>
            <th class="cell">Saldo del periodo: </th><td colspan="3" class="cell">$ <?php echo round($sald + $secondary_sald, 2); ?></td>
        </tr>
        <tr>
            <th class="cell">Saldo operativo: </th><td colspan="3" class="cell">$ <?php echo round($account['cc_saldo'] + $account['cc_varios'], 2); ?></td>
        </tr>
    </table>

    <?php if (count($contracts) > 0 && $contracts_period_status) { ?>
        <div id="non-printable">
            <h4>Contratos vigentes</h4>
            <?php foreach ($contracts as $contract) { ?>  
                <table class="table">
                    <tr>
                        <td class="cell"colspan="4">Contrato con <?php echo $contract['con_inq'] ?> en: <?php echo $contract['con_domi'] ?></td>
                    </tr>
                    <tr>    
                        <th class="cell">Concepto</th>
                        <th class="cell">Pagado/Controlado</th>
                        <th class="cell">Fecha</th>
                        <th class="cell">Mes</th>
                    </tr>
                    <?php foreach ($contracts_period_status as $contract_id => $contract_period_status) { ?>  
                        <?php if ($contract_id == $contract['con_id']) { ?>  
                            <?php foreach ($contract_period_status['principals'] as $principal) { ?>
                                <tr class="reg_<?php echo $contract_id; ?>">
                                    <td class="cell"><?php echo $principal['concept']; ?></td>
                                    <td class="cell"><?php echo $principal['action']; ?></td>
                                    <td class="cell"><?php echo $principal['date']; ?></td>
                                    <td class="cell"><?php echo $principal['month']; ?></td>
                                </tr>
                            <?php } ?>
                            <?php foreach ($contract_period_status['secondarys'] as $secondary) { ?>
                                <tr class="reg_<?php echo $contract_id; ?>">
                                    <td class="cell"><?php echo $secondary['concept']; ?></td>
                                    <td class="cell"><?php echo $secondary['action']; ?></td>
                                    <td class="cell"><?php echo $secondary['date']; ?></td>
                                    <td class="cell"><?php echo $secondary['month']; ?></td>
                                </tr>
                            <?php } ?>
                        <?php } ?>
                    <?php } ?>
                </table>
            <?php } ?>
        </div>
    <?php } ?>

    <?php if (count($intereses_debt) > 0) { ?>
        <div id="non-printable">
            <h4>Intereses en Mora</h4>
            <table class="table">
                <tr>    
                    <th class="cell">Inquilino</th>
                    <th class="cell">Fecha Pago Alquiler</th>
                    <th class="cell">Monto debido</th>
                </tr>
                <?php foreach ($intereses_debt as $interes_debt) { ?>
                    <tr class="reg_<?php echo $interes_debt['int_id']; ?>">  
                        <td class="cell"><?php echo $interes_debt['int_depositante']; ?></td>
                        <td class="cell"><?php echo $interes_debt['int_fecha_pago']; ?></td>
                        <td class="cell">$ <?php echo $interes_debt['int_amount']; ?></td>
                    </tr>
                <?php } ?>
            </table>
        </div> 
    <?php } ?>

    <?php if (count($comentaries) > 0) { ?>
        <div id="non-printable">
            <h4>Comentarios</h4>
            <table class="table">
                <tr>    
                    <th class="cell">Fecha</th>
                    <th class="cell">Inmueble</th>
                    <th class="cell">Comentario</th>
                </tr>
                <?php foreach ($comentaries as $comentary) { ?>
                    <tr class="reg_<?php echo $comentary['com_id']; ?>">  
                        <td class="cell"><?php echo $comentary['com_date']; ?></td>
                        <td class="cell"><?php echo $comentary['com_dom']; ?></td>
                        <td class="cell"><?php echo $comentary['com_com']; ?></td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    <?php } ?>
    <table class="table">
        <tr>
            <td style="border:none"> Recibimos conforme de <?php echo $bussines_name; ?>, la cantidad de Pesos (<?php echo '$ ' . $today_rendition_amount . ') ' . $today_rendition_amount_letra ?>
                en concepto de rendición de cuenta por la cobranza de alquileres, habiendo verificado los comprobantes de ingresos y egresos del inmueble/s
                sito domicilio/os <?php echo $address_rendition ?>
                correspondiente al mes/es de <?php echo $month_rendition ?></td>
        </tr>
        <tr>
            <td style="border:none">Firma: ________________________              <?php echo $account['cc_prop'] ?></td>
        </tr>
    </table>

</div>      

<div id="non-printable" class="report_actions">
    <a class="btn btn-primary button_report" href="javascript:;" onclick="window.print();return false;">Imprimir</a>
</div>



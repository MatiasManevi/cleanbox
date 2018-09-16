<div class="tablas"> 

    <table style="margin-bottom: 0px"class="table">
        <tr>    
            <th class="cell">Periodo</th>
            <th class="cell">Monto</th>
        </tr>
        <?php if (count($periods)) { ?>
            <?php foreach ($periods as $period) { ?>
                <tr class="<?php echo $period['per_id'] == $painted ? 'current_period' : ''; ?>" title="<?php echo ($period['per_id'] == $painted ? 'Periodo actual' : '') ?>">
                    <td class="cell"><?php echo $period['per_inicio'] . ' | ' . $period['per_fin']; ?></td>
                    <td class="cell">$ <?php echo $period['per_monto']; ?></td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="2">No se encontraron Periodos de cobro registrados en el contrato</td>
            </tr>
        <?php } ?>
    </table>

</div>

<div class="tablas">

    <table style="margin-bottom: 0px"class="table">
        <tr>    
            <th class="cell">Servicios</th>
            <th class="cell">Accion</th>
        </tr>
        <?php if (count($services)) { ?>
            <?php foreach ($services as $service) { ?>
                <tr>
                    <td class="cell"><?php echo $service['serv_concepto']; ?></td>
                    <td class="cell"><?php echo $service['serv_accion']; ?></td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="2">No se encontraron servicios registrados en el contrato</td>
            </tr>
        <?php } ?>
    </table>

</div>

<div class="clear_both credit_contract_details">
    <div class="contract_detail" style="width: 12%;">
        <label>Gestion Cobro</label><input title="Porc. Gestion de Cobro Ej: 7% = 0.07" value="<?php echo $porc ?>"  type="text" name="con_porc" id="con_porc" class="form-control ui-autocomplete-input" placeholder="Gestion Cobro" readonly>
    </div>
    <div class="contract_detail">
        <label>Porc. Punitorio</label><input title="Porc. Punitorio para cobra de Mora Ej: 7% = 0.07" value="<?php echo $punitorio ?>"  type="text" name="con_punitorio" id="con_punitorio" class="form-control ui-autocomplete-input" placeholder="Punitorios" readonly>
    </div>
    <div class="contract_detail">
        <label>Incluye IVA/Honorarios</label><input value="<?php echo $iva ?>"  type="text" name="con_iva" id="con_iva" class="form-control ui-autocomplete-input" placeholder="IVA" readonly>
    </div>
    <div class="contract_detail">
        <label>Incluye IVA/Alquiler</label><input value="<?php echo $iva_alq ?>"  type="text" name="con_iva_alq" id="con_iva_alq" class="form-control ui-autocomplete-input" placeholder="IVA" readonly>
    </div>
    <div class="contract_detail">
        <label>Pagos Honorarios</label><input value="<?php echo $honorary_cuotes_payed ? $honorary_cuotes_payed : 0 . '/' . $honorary_cuotes ?>" title="<?php echo $honorary_cuotes_payed . ' cuotas pagadas de ' . $honorary_cuotes . ' de Honorarios' ?>" type="text" name="honorary_cuotes" id="honorary_cuotes" class="form-control ui-autocomplete-input" placeholder="IVA" readonly>
    </div>
    <div class="contract_detail">
        <label>Pagos Deposito de Garantia</label><input value="<?php echo $warranty_cuotes_payed ? $warranty_cuotes_payed : 0 . '/' . $warranty_cuotes ?>" title="<?php echo $warranty_cuotes_payed . ' cuotas pagadas de ' . $warranty_cuotes . ' de Depositos de Garantia' ?>"  type="text" name="warranty_cuotes" id="warranty_cuotes" class="form-control ui-autocomplete-input" placeholder="IVA" readonly>
    </div>
    <input type="hidden" id="con_id" value="<?php echo $con_id; ?>">
</div>

<div class="section_description">
    <label>Los créditos por Gestion de Cobro, IVA e Intereses correspondientes seran calculados y generados automaticamente por el sistema tomando los datos de la presente tabla</label>
</div>

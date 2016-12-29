<div id="printable" class="report_sheet _excel">
    <div id="non-printable" class="report_actions">
        <a class="btn btn-primary button_report" href="javascript:;" onclick="window.print();return false;">Imprimir</a>
        <a class="btn btn-primary button_report" href="<?php echo site_url('allConceptsMovementsReport') ?>">Volver</a>
    </div>
    <h4>Conceptos de Entrada y Salida / Montos  <?php echo '(' . $from . ' a ' . $to . ')' ?></h4>

    <table style="margin-bottom: 54px" class="table">
        <tr><th class="cell" colspan="2">Entradas</th></tr>
        <tr>    
            <th class="cell">Concepto</th>
            <th class="cell">Monto</th>
        </tr>
        <?php $founded = false; ?>
        <?php foreach ($in_concepts as $in_concept) { ?>
            <?php
            $concept_amount = 0;
            foreach ($credits as $credit) {
                if ($in_concept['conc_desc'] == $credit['cred_concepto']) {
                    $concept_amount += $credit['cred_monto'];
                }
            }
            if ($in_concept['conc_desc'] == 'Alquiler' && $concept_amount > $gestion && !$founded) {
                $concept_amount = $concept_amount - $gestion;
                $founded = true;
            } else if ($in_concept['conc_desc'] == 'Alquiler Comercial' && $concept_amount > $gestion && !$founded) {
                $concept_amount = $concept_amount - $gestion;
                $founded = true;
            }
            ?>
            <tr>
                <td class="cell"><?php echo $in_concept['conc_desc']; ?></td>
                <td class="cell">$ <?php echo $concept_amount; ?></td>
            </tr>
        <?php } ?>
        <tr>
            <td class="cell">TOTALES</td>
            <td class="cell">$ <?php echo $total_cred ?></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
        </tr>
    </table>

    <table class="table">
        <tr><th class="cell" colspan="2">Salidas</th></tr>
        <tr>    
            <th class="cell">Concepto</th>
            <th class="cell">Monto</th>
        </tr>
        <?php foreach ($out_concepts as $out_concept) { ?>
            <?php if (strpos($out_concept['conc_desc'], 'Gestion de Cobro') === FALSE) { ?>
                <?php
                $concept_amount = 0;
                foreach ($debits as $debit) {
                    if ($out_concept['conc_desc'] == $debit['deb_concepto']) {
                        $concept_amount += $debit['deb_monto'];
                    }
                }
                ?>
                <tr>
                    <td class="cell"><?php echo $out_concept['conc_desc']; ?></td>
                    <td class="cell">$ <?php echo $concept_amount; ?></td>
                </tr>
            <?php } ?>
        <?php } ?>
        <tr>
            <td class="cell">TOTALES</td>
            <td class="cell">$ <?php echo $total_deb ?></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
        </tr>
    </table>
    <table class="table">
        <tr>    
            <td class="cell">TOTAL FINAL</td>
            <td class="cell">$ <?php echo round($total_cred - $total_deb, 2) ?></td>
        </tr>
    </table>
</div>

<script>
    $(function(){
        $("._excel").tableExport({
            formats: ["xlsx"],  
            position: 'bottom',
            fileName: "Conceptos de Entrada y Salida / Montos  <?php echo '(' . $from . ' a ' . $to . ')' ?>"
        });
    });
</script>


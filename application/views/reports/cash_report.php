<div id="printable" class="report_sheet _excel">
    <div id="non-printable" class="report_actions">
        <a class="btn btn-primary button_report" href="javascript:;" onclick="window.print();return false;">Imprimir</a>
        <a class="btn btn-primary button_report" href="<?php echo site_url('cashReport') ?>">Volver</a>
    </div>
    
    <h4>Informe Detallado Caja Diaria (<?php echo $type_show ?>) <?php echo $date ?></h4>

    <table class="table">
        <tr>
            <th colspan="2" class="cell">La caja comienza con:</th>
            <td colspan="4" class="cell">$ <?php echo $begin_cash; ?></td>
        </tr>
        <tr>
            <td colspan="6"></td>
        </tr>
        <tr>   
            <th class="cell">N° Recibo</th>
            <th class="cell">Concepto</th>
            <th class="cell">Domicilio</th>
            <th class="cell">Debitos</th>
            <th class="cell">Creditos</th>
            <th class="cell">Saldo</th>
        </tr>
        <?php $sald = 0; ?>
        <?php foreach ($movements as $movement) { ?>
            <?php
            if ($movement['type'] == 'credito') {

                if ($movement['is_transfer']) {
                    $concept = '- - ' . $movement['concept'] . ' - -';
                } else {
                    if (strpos($movement['concept'], 'Alquiler') !== false) {
                        $concept = $movement['concept'] . ' - ' . $movement['month'] . ' (' . $movement['dep'] . ' - ' . $movement['cc'] . ')';
                    } else {
                        $concept = $movement['concept'] . ' ' . $movement['month'] . ' - ' . $movement['dep'] . ' - ' . 'a Cta de ' . $movement['cc'];
                    }
                }
            } else {

                if ($movement['is_transfer']) {
                    $concept = '- - ' . $movement['concept'] . ' - -';
                } else {
                    $concept = $movement['concept'] . ' ' . $movement['month'] . ' - a Cta de ' . $movement['cc'];
                }
            }
            ?>
            <tr onclick="report.paintRow($(this));" class="_reg_<?php echo $movement['id']; ?>">
                <td class="cell"><?php echo isset($movement['receive_number']) ? $movement['receive_number'] : ''; ?></td>
                <td class="cell"><?php echo $concept; ?></td>
                <td class="cell"><?php echo isset($movement['address']) ? $movement['address'] : ''; ?></td>
                <?php if (!$movement['is_transfer']) { ?>
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
                <?php } else { ?>
                    <?php
                    if ($movement['type'] == 'credito') {
                        $sald -= $movement['amount'];
                        ?>
                        <td class="cell">$ <?php echo $movement['amount']; ?></td>
                        <td class="cell"></td>
                        <?php
                    } else {
                        $sald += $movement['amount'];
                        ?>
                        <td class="cell"></td>
                        <td class="cell">$ <?php echo $movement['amount']; ?></td>
                    <?php } ?>
                <?php } ?>
                <td class="cell">$ <?php echo round($sald, 2); ?></td>
            </tr>
        <?php } ?>

        <?php
        if ($type == 'Caja') {
            $title = 'Ya se encuentran aplicadas las transferencias del dia de hoy';
        } else {
            $title = 'Acumulado de todos los meses, inclusive este dia';
        }
        ?>
        <tr>
            <th class="cell" colspan="3">Totales</th>
            <td class="cell">$ <?php echo $outs; ?></td>
            <td class="cell">$ <?php echo $ins; ?></td>
            <td class="cell" title="<?php echo $title; ?>">$ <?php echo round($sald, 2); ?></td>
        </tr>
        <tr>
            <td colspan="6"></td>
        </tr>
        <tr>
            <th colspan="2" class="cell">La caja termina con:</th>
            <td colspan="4" class="cell">$ <?php echo round($begin_cash + $sald, 2); ?></td>
        </tr>
    </table>
</div>

<script>
    $(function(){
        $("._excel").tableExport({
            formats: ["xlsx"],  
            position: 'bottom',
            fileName: "Informe Detallado Caja Diaria (<?php echo $type_show ?>) <?php echo $date ?>"
        });
    });
</script>




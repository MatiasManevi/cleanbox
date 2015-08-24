<style>
    .table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
        padding: 0 !important;
    }
    body {
        background-color: white;
        padding-top: 60px;
    }
</style>

<form style="overflow: visible;margin-bottom: 15px;">

    <div id="printable" class="" style="margin:0 auto;width: 100%">

        <div style="height: auto;" class="hoja_informe">
            <div class="global_cabezera">
                <h2 style="margin-top: 47px;">Informe Detallado Caja Diaria <?= $fecha ?></h2>
                <div style="float:left !important" class="caja_comienza"><?= 'La caja comenzo con: $ ' . (isset($caja) ? ($caja) : 0) ?></div>
                <div style="float:left !important" class="caja_comienza"><?= 'La caja acumulada en Banco comienza con: $ ' . (isset($comienza_banco) ? $comienza_banco : 0) ?></div>
                <table class="table">
                    <tr>                           
                        <th class="centrar">N° t</th>
                        <th class="centrar">Tipo transac.</th>
                        <th class="centrar">Concepto</th>
                        <th class="centrar">Domicilio</th>
                        <th class="centrar">Debitos</th>
                        <th class="centrar">Creditos</th>
                        <th class="centrar">Saldo</th>
                        <?
                        $saldo = 0;
                        $saldo_banco = 0;
                        $saldo_caja = 0;
                        for ($x = 0; $x < count($movimientos); $x++) {
                            echo '<tr onclick="pintar(this)" class="reg_' . $movimientos[$x]['id'] . '">';
                            echo '<td class="centrar">' . $movimientos[$x]['trans'] . '</td>';
                            echo '<td class="centrar">' . $movimientos[$x]['tipo_trans'] . '</td>';
                            if ($movimientos[$x]['operacion'] == 'credito') {
                                if ($movimientos[$x]['tipo_trans'] == 'Caja') {
                                    $saldo_caja += $movimientos[$x]['monto'];
                                } else {
                                    $saldo_banco += $movimientos[$x]['monto'];
                                }
                                if (strpos($movimientos[$x]['concepto'], 'Alquiler') !== false) {
                                    echo '<td class="centrar">' . str_replace("N", " ", $movimientos[$x]['concepto']) . ' -' . $movimientos[$x]['mes'] . ' (' . $movimientos[$x]['depositante'] . ' - ' . $movimientos[$x]['cc'] . ')</td>';
                                } else {
                                    if ($movimientos[$x]['operacion'] == 'credito') {
                                        echo '<td class="centrar">' . $movimientos[$x]['concepto'] . ' ' . $movimientos[$x]['mes'] . ' -' . $movimientos[$x]['depositante'] . '- ' . 'a Cta de ' . $movimientos[$x]['cc'] . '</td>';
                                    } else {
                                        echo '<td class="centrar">' . $movimientos[$x]['concepto'] . ' a Cta de ' . $movimientos[$x]['cc'] . '</td>';
                                    }
                                }
                            } else {
                                if ($movimientos[$x]['tipo_trans'] == 'Caja') {
                                    $saldo_caja -= $movimientos[$x]['monto'];
                                } else {
                                    $saldo_banco -= $movimientos[$x]['monto'];
                                }
                                echo '<td class="centrar">' . $movimientos[$x]['concepto'] . ' ' . $movimientos[$x]['mes'] . ' - a Cta de ' . $movimientos[$x]['cc'] . '</td>';
                            }
                            echo '<td class="centrar">' . $movimientos[$x]['domicilio'] . '</td>';
                            if ($movimientos[$x]['operacion'] == 'credito') {
                                echo '<td class="centrar"></td>';
                                echo '<td class="centrar">$ ' . $movimientos[$x]['monto'] . '</td>';
                                $saldo += $movimientos[$x]['monto'];
                            } else {
                                echo '<td class="centrar">$ ' . $movimientos[$x]['monto'] . '</td>';
                                echo '<td class="centrar"></td>';
                                $saldo -= $movimientos[$x]['monto'];
                            }
                            echo '<td class="centrar">$ ' . $saldo . '</td>';
                            echo '</tr>';
                        }
                        echo '<td class=""></td>';
                        echo '<td class=""></td>';
                        echo '<td class=""></td>';
                        echo '<td class=""></td>';
                        echo '<td class="centrar">$ ' . $salidas . '</td>';
                        echo '<td class="centrar">$ ' . $entradas . '</td>';
                        echo '<td class="centrar">$ ' . $saldo . '</td>';
                        ?>
                    </tr>
                </table>
                <? // $saldonuevo = $sal_nue; ?>
                <div style="float:left !important" class="caja_comienza"><?= 'La caja fisica termina con: $ ' . ($caja + $saldo - $saldo_banco_hoy) ?></div>
                <div style="float:left !important" class="caja_comienza"><?= 'La caja acumulada en Banco termina con: $ ' . ($comienza_banco + $saldo_banco) ?></div>
                <div style="float:left !important" class="caja_comienza"><?= 'El saldo Bancario en el intervalo (' . $intervalo_fecha_banco . ') es: $ ' . $saldo_banco_periodo ?></div>
                <div style="float:left !important" class="caja_comienza"><?= 'El saldo Bancario de hoy es: $ ' . $saldo_banco_hoy ?></div>
            </div>   
            <div class="transferencias">
                <? if (count($transferencias) > 0) { ?>
                    <span style="font-weight: bold;">Transferencias del mes</span>
                    <? for ($x = 0; $x < count($transferencias); $x++) { ?>
                        <div class="tranfer">
                            <span><?= $transferencias[$x]['transf_fecha'] ?></span>
                            <span><?= '$ ' . $transferencias[$x]['transf_monto'] ?></span>
                            <span><?= $transferencias[$x]['transf_tipo'] ?></span>
                        </div>
                    <? } ?>
                <? } ?>
            </div> 
        </div>
        <div id="non-printable">
            <input  class="btn btn-primary" style="margin-bottom: 15px; margin-top: 22px;float: right;font-family: 'futura';text-transform: inherit;
                    font-size: 20px;" type="button" value="Imprimir Informe" onclick="window.print();return false;" />
            <a id="buttons_cli" style=" margin-top: 23px;" class="btn" href="<?= site_url('manager/caja_detallada') ?>"><?= 'Volver' ?></a>
        </div>
    </div>

</form>

<script>
    function pintar(fila){
        if($(fila).hasClass('incorrecto') || !$(fila).hasClass('correcto')){
            $(fila).css('background','darkseagreen');
            $(fila).addClass('correcto');
            $(fila).removeClass('incorrecto');
        }else if($(fila).hasClass('correcto')){
            $(fila).css('background','darksalmon');
            $(fila).addClass('incorrecto');
            $(fila).removeClass('correcto');
        }
    }
</script>

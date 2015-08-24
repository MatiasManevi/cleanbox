<style>
    .table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
        padding: 0 !important;
    }
    body {
        background-color: white;
        padding-top: 60px;
    }
</style>
<div class="contenedor_centro">
    <form style="overflow: visible;margin-bottom: 15px;">

        <div id="printable" class="row">

            <div  style="height: auto" class="hoja_informe">

                <h2 style="margin-top: 47px;margin-bottom: 20px">Conceptos de Entrada y Salida / Montos  <?= '(' . $desde . ' a ' . $hasta . ')' ?></h2>

                <h2 style="text-decoration: -moz-anchor-decoration; margin-top: 10px;margin-bottom: 20px">Entradas</h2>

                <table style="margin-bottom: 54px" class="table">
                    <tr>    
                        <th class="centrar">Concepto</th>
                        <th class="centrar">Monto</th>
                    </tr>
                    <?
                    $entro = false;
                    foreach ($conceptos_entrada->result_array() as $concepto) {
                        echo '<td class="centrar">' . $concepto['conc_desc'] . '</td>';
                        $monto_conc = 0;
                        for ($x = 0; $x < count($creditos); $x++) {
                            if ($concepto['conc_desc'] == $creditos[$x]['cred_concepto']) {
                                $monto_conc += $creditos[$x]['cred_monto'];
                            }
                        }
                        if ($concepto['conc_desc'] == 'Alquiler' && $monto_conc > $gestion && !$entro) {
                            $monto_conc = $monto_conc - $gestion;
                            $entro = true;
                        } else if ($concepto['conc_desc'] == 'Alquiler Comercial' && $monto_conc > $gestion && !$entro) {
                            $monto_conc = $monto_conc - $gestion;
                            $entro = true;
                        } else if ($concepto['conc_desc'] == 'Alquiler Comercial N' && $monto_conc > $gestion && !$entro) {
                            $monto_conc = $monto_conc - $gestion;
                            $entro = true;
                        }
                        echo '<td class="centrar">$ ' . $monto_conc . '</td>';
                        echo '<td>';
                        echo '</td>';
                        echo '</tr>';
                    }
                    ?>
                    <tr>
                        <td class="centrar">TOTALES</td>
                        <td class="centrar"><?= '$ ' . $total_cred ?></td>
                    </tr>

                </table>

                <h2 style="text-decoration: -moz-anchor-decoration; margin-top: 10px;margin-bottom: 20px">Salidas</h2>

                <table class="table">
                    <tr>    
                        <th class="centrar">Concepto</th>
                        <th class="centrar">Monto</th>
                    </tr>
                    <?
                    $monto_conc_deb = 0;
                    $total = 0;
                    foreach ($conceptos_salida->result_array() as $concepto) {
                        if (strpos($concepto['conc_desc'], 'Gestion de Cobro') === FALSE) {
                            echo '<td class="centrar">' . $concepto['conc_desc'] . '</td>';
                            $monto_conc_deb = 0;
                            for ($x = 0; $x < count($debitos); $x++) {
                                if ($concepto['conc_desc'] == $debitos[$x]['deb_concepto']) {
                                    $monto_conc_deb += $debitos[$x]['deb_monto'];
                                }
                            }
                            echo '<td class="centrar">$ ' . $monto_conc_deb . '</td>';
                            echo '<td>';
                            echo '</td>';
                            echo '</tr>';
                            $total += $monto_conc_deb;                           
                        }
                        $monto_conc_deb = 0;
                    }
                    ?>
                    <tr>
                        <td class="centrar">TOTALES</td>
                        <td class="centrar"><?= '$ ' . $total_deb ?></td>
                    </tr>

                </table>
                <table class="table">
                    <tr>    
                        <td class="centrar">TOTAL FINAL</td>
                        <td class="centrar"><?= '$ ' . ($total_cred - $total_deb) ?></td>
                    </tr>
                </table>
            </div>
            <div id="non-printable">
                <input  class="btn btn-primary" style="margin-bottom: 15px; margin-top: 22px;float: right;font-family: 'futura';text-transform: inherit;
                        font-size: 20px;" type="button" value="Imprimir Informe" onclick="window.print();return false;" />
                <a id="buttons_cli" style=" margin-top: 23px;" class="btn" href="<?= site_url('mensual') ?>"><?= 'Volver' ?></a>
            </div>

        </div>

    </form>
</div>

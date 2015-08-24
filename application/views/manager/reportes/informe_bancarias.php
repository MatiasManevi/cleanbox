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
                <h2 style="margin-top: 47px;margin-bottom: 20px">Transacciones Bancarias  <?= '(' . $desde . ' a ' . $hasta . ')' ?></h2>
                <h2 style="text-decoration: -moz-anchor-decoration; margin-top: 10px;margin-bottom: 20px">Créditos</h2>

                <table style="margin-bottom: 54px" class="table">
                    <tr>    
                        <th class="centrar">Fecha</th>
                        <th class="centrar">Concepto</th>
                        <th class="centrar">Cta. Cte.</th>
                        <th class="centrar">Depositante</th>
                        <th class="centrar">Monto</th>
                    </tr>
                    <?
                    for ($x = 0; $x < count($creditos); $x++) {
                        echo '<td class="centrar">' . $creditos[$x]['cred_fecha'] . '</td>';
                        echo '<td class="centrar">' . $creditos[$x]['cred_concepto'] . ' - ' . $creditos[$x]['cred_mes_alq'] . '</td>';
                        echo '<td class="centrar">' . $creditos[$x]['cred_cc'] . '</td>';
                        echo '<td class="centrar">' . $creditos[$x]['cred_depositante'] . '</td>';
                        echo '<td class="centrar">$ ' . $creditos[$x]['cred_monto'] . '</td>';
                        echo '</tr>';
                    }
                    ?>
                    <tr>
                        <td colspan="4" class="centrar">TOTALES</td>
                        <td class="centrar"><?= '$ ' . $total_cred ?></td>
                    </tr>

                </table>

                <h2 style="text-decoration: -moz-anchor-decoration; margin-top: 10px;margin-bottom: 20px">Débitos</h2>

                <table class="table">
                    <tr>    
                        <th class="centrar">Fecha</th>
                        <th class="centrar">Concepto</th>
                        <th class="centrar">Cta. Cte.</th>
                        <th class="centrar">Monto</th>
                    </tr>
                    <?
                    for ($x = 0; $x < count($debitos); $x++) {
                        echo '<td class="centrar">' . $debitos[$x]['deb_fecha'] . '</td>';
                        echo '<td class="centrar">' . $debitos[$x]['deb_concepto'] . ' - ' . $debitos[$x]['deb_mes'] . '</td>';
                        echo '<td class="centrar">' . $debitos[$x]['deb_cc'] . '</td>';
                        echo '<td class="centrar">$ ' . $debitos[$x]['deb_monto'] . '</td>';
                        echo '</tr>';
                    }
                    ?>
                    <tr>
                        <td colspan="3" class="centrar">TOTALES</td>
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
                <a id="buttons_cli" style=" margin-top: 23px;" class="btn" href="<?= site_url('bancarias') ?>"><?= 'Volver' ?></a>
            </div>

        </div>

    </form>
</div>

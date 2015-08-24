<style>
    body {
        background-color: white;
        padding-top: 60px;
    }
    .table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
        padding: 0 !important;
    }
</style>
<div class="contenedor_centro">
    <form style="overflow: visible;margin-bottom: 15px;">

        <div id="printable" class="row">

            <div  style="height: auto" class="hoja_informe">
                <?
                $total_retirado_deudor = 0;
                $total_retirado_acreedor = 0;
                $total_ccs_deudor = 0;
                $total_ccs_acreedor = 0;
                ?>
                <h2 style="margin-top: 47px;margin-bottom: 20px">Informe General de Ctas. Ctes.  <?= '(' . $desde . ' a ' . $hasta . ')' ?></h2>

                <h2 style="margin-bottom: 22px;margin-top: 17px;font-size: 16px;text-decoration: underline;">Propietarios con saldo deudor</h2>
                <table style="width: 900px !important;" class="table-hover">
                    <tr>    
                        <th class="centrar">Nro.</th>
                        <th class="centrar">Nombre</th>
                        <th class="centrar">Rendiciones</th>
                        <th class="centrar">Entradas</th>
                        <th class="centrar">Salidas</th>
                        <th class="centrar">Saldo</th>
                    </tr>
                    <?$i = 1;
                    if (count($negativos) > 0) {
                        
                        for ($x = 0; $x < count($negativos); $x++) {
                            ?>
                            <tr class="<?= $negativos[$x]['cobro'] == 1 ? 'cobro' : 'no_cobro' ?>">
                                <?
                                echo '<td class="centrar">' . $i . '</td>';
                                echo '<td class="centrar">' . $negativos[$x]['prop'] . '</td>';
                                echo '<td class="centrar">$ ' . $negativos[$x]['retiro'] . '</td>';
                                $total_retirado_deudor += $negativos[$x]['retiro'];
                                echo '<td class="centrar">$ ' . $negativos[$x]['entro'] . '</td>';
                                echo '<td class="centrar">$ ' . $negativos[$x]['salio'] . '</td>';
                                echo '<td class="centrar">$ ' . $negativos[$x]['total_cc'] . '</td>';
                                $total_ccs_deudor += $negativos[$x]['total_cc'];
                                echo '<td>';
                                echo '</td>';
                                echo '</tr>';
                                $i++;
                            }
                        }
                        ?>
                    <tr>
                        <td class=""></td>
                        <td class="centrar">Totales</td>
                        <td class="centrar"><?= '$ ' . $total_retirado_deudor; ?></td>
                        <td class=""></td>
                        <td class=""></td>
                        <td class="centrar"><?= '$ ' . $total_ccs_deudor; ?></td>
                    </tr>

                </table>
                <h2 style="margin-bottom: 22px;margin-top: 17px;font-size: 16px;text-decoration: underline;">Propietarios con saldo acreedor</h2>
                <table style="width: 900px !important;" class="table-hover">

                    <tr>    
                        <th class="centrar">Nro.</th>
                        <th class="centrar">Nombre</th>
                        <th class="centrar">Rendiciones</th>
                        <th class="centrar">Entradas</th>
                        <th class="centrar">Salidas</th>
                        <th class="centrar">Saldo</th>
                    </tr>
                    <?
                    if (count($positivos) > 0) {
                        for ($x = 0; $x < count($positivos); $x++) {
                            ?>
                            <tr class="<?= $positivos[$x]['cobro'] == 1 ? 'cobro' : 'no_cobro' ?>">
                                <?
                                echo '<td class="centrar">' . $i . '</td>';
                                $i++;
                                echo '<td class="centrar">' . $positivos[$x]['prop'] . '</td>';
                                echo '<td class="centrar">$ ' . $positivos[$x]['retiro'] . '</td>';
                                $total_retirado_acreedor += $positivos[$x]['retiro'];
                                echo '<td class="centrar">$ ' . $positivos[$x]['entro'] . '</td>';
                                echo '<td class="centrar">$ ' . $positivos[$x]['salio'] . '</td>';
                                echo '<td class="centrar">$ ' . $positivos[$x]['total_cc'] . '</td>';
                                $total_ccs_acreedor += $positivos[$x]['total_cc'];
                                echo '<td>';
                                echo '</td>';
                                echo '</tr>';
                            }
                        }
                        ?>
                    <tr>
                        <td class=""></td>
                        <td class="centrar">Totales</td>
                        <td class="centrar"><?= '$ ' . $total_retirado_acreedor; ?></td>
                        <td class=""></td>
                        <td class=""></td>
                        <td class="centrar"><?= '$ ' . $total_ccs_acreedor; ?></td>
                    </tr>

                </table>
                <table style="width: 527px !important;margin-top: 20px;" class="table-hover">
                    <tr>
                        <td class="centrar">Total retirado</td>
                        <td class="centrar"><?= '$ ' . ($total_retirado_acreedor + $total_retirado_deudor); ?></td>
                    </tr>
                    <tr>
                        <td class="centrar">Total en Cuentas Corrientes</td>
                        <td class="centrar"><?= '$ ' . ($total_ccs_deudor + $total_ccs_acreedor); ?></td>
                    </tr>
                </table>
                <span style="float: left;margin-bottom: 7px;margin-left: 20px;margin-top: 21px;">Referencias:</span>
                <div id="non-printable" class="referencias">                   
                    <div style="border: 2px solid;border-radius: 5px;background: none repeat scroll 0 0 #f5da81;float: left;height: 20px;margin-right: 5px;width: 20px;"></div><span style="float: left;">Retiro dinero de Rendicion</span>
                    <div style="border: 2px solid;border-radius: 5px;background: none repeat scroll 0 0 darkseagreen;float: left;height: 20px;margin-left: 10px;margin-right: 5px;width: 20px;"></div><span>No Retiro dinero de Rendicion</span>
                </div>
            </div>
            <div id="non-printable">
                <input  class="btn btn-primary" style="margin-bottom: 15px; margin-top: 22px;float: right;font-family: 'futura';text-transform: inherit;
                        font-size: 20px;" type="button" value="Imprimir Informe" onclick="window.print();return false;" />
                <a id="buttons_cli" style=" margin-top: 23px;" class="btn" href="<?= site_url('manager/por_cobrar') ?>"><?= 'Volver' ?></a>
            </div>

        </div>

    </form>
</div>

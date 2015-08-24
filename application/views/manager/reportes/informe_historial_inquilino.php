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

                <h2 style="margin-top: 47px;margin-bottom: 20px">Historial de Pagos año <?= $ano ?></h2>

                <h2 style="text-decoration: -moz-anchor-decoration; margin-top: 10px;margin-bottom: 20px">Inquilino: <?= $inquilino ?> </h2>
                <h2 style="text-decoration: -moz-anchor-decoration; margin-top: 10px;margin-bottom: 20px">Domicilio: <?= $domicilio?></h2>
                <h2 style="font-size: 16px;text-decoration: -moz-anchor-decoration; margin-top: 10px;margin-bottom: 20px">Tolerancia por mora: <?= $contrato['con_tolerancia'] ?> días</h2>

                <table style="margin-bottom: 54px" class="table">
                    <tr>    
    <!--                        <th class="centrar">Mes</th>-->
                        <th class="centrar">Fecha de Pago</th>
                        <th class="centrar">Mes Pagado</th>
                        <th class="centrar">Tipo pago</th>
                        <th class="centrar">Dias de mora</th>
                        <th class="centrar">Monto Pagado</th>
                    </tr>
                    <?
                    
                        for ($x = 0; $x < count($pagos); $x++) {
                            echo '<tr>';
//                            echo '<td class="centrar">' . $pagos[$x]['mes'] . '</td>';
                            echo '<td class="centrar">' . $pagos[$x]['fecha_pago'] . '</td>';
                            echo '<td class="centrar">' . $pagos[$x]['mes_pagado'] . '</td>';
                            echo '<td class="centrar">' . $pagos[$x]['tipo'] . '</td>';
                            echo '<td class="centrar">' . $pagos[$x]['dias_mora'] . ' Días</td>';
                            echo '<td class="centrar">$ ' . $pagos[$x]['monto'] . '</td>';
                            echo '<td>';
                            echo '</td>';
                            echo '</tr>';
                        }
                    
                    ?>

                </table>

            </div>
            <div id="non-printable">
                <input  class="btn btn-primary" style="margin-bottom: 15px; margin-top: 22px;float: right;font-family: 'futura';text-transform: inherit;
                        font-size: 20px;" type="button" value="Imprimir Informe" onclick="window.print();return false;" />
                <a id="buttons_cli" style=" margin-top: 23px;" class="btn" href="<?= site_url('reporte_historial') ?>"><?= 'Volver' ?></a>
            </div>

        </div>

    </form>
</div>

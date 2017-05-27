<style>
    body {
        background-color: #FFFFFF;
        padding-top: 60px;
    }
    .contenedor_centro{
        margin-left: 40px;
    }
</style>
<div id="printable" class="contenedor_centro">
    <form style="overflow: visible;margin-bottom: 15px;">

        <div class="row">

            <div class="hoja_informe">
                <h2 style="margin-bottom: 1px;text-align: center;">Informe del Juez <?= $juez->arb_name ?></h2>
                <p style="text-align: center;margin-bottom: 68px;"><?= Date('d-m-Y') ?></p>
                <div style="width: 100%; float: left;margin-bottom: 20px;" id="cli_item">
                    <label class="subtitle">Detalle</label>
                </div>
                <table class="table table-hover">
                    <tr>    
                        <th>Fecha</th>
                        <th>Dia</th>
                        <th>Equipos</th>
                        <th>Arancel Percibido</th>
                        <th>10%</th>
                        <th>Pagado</th>
                    </tr>
                    <?
                    $arancel_total = 0;
                    $arancel_percibido = 0;
                    $mora = 0;
                    $entregado = 0;
                    $entro = false;
                    $diezmo_total = 0;
                    print_r('las desig: '.$designaciones.' count: '.count($designaciones));
                    foreach ($designaciones->result_array() as $row) {
                        foreach ($jornadas->result() as $jornada) {
                            if ($jornada->jor_des == $row['des_id']) {
                                if ($jornada->jor_pri_juez == $juez->arb_name || $jornada->jor_sec_juez == $juez->arb_name) {
                                    $entro = true;
                                    print_r('ENTROOOOOOOOO');
                                    foreach ($aranceles->result() as $categoria) {
                                        if ($categoria->aran_cate == $jornada->jor_cate) {
                                            $arancel_percibido = $categoria->aran_price;
                                            break;
                                        }
                                    }
                                }
                            }
                        }
                        if ($entro) {
                            $diezmo = $arancel_percibido * 0.10;
                            $diezmo_total += $diezmo;
                            if ($row['des_pagado'] == 1) {
                                $entregado += $diezmo;
                            }
                            if ($row['des_pagado'] == 0) {
                                $mora += $diezmo;
                            }
                            echo '<tr class="reg_' . $row['des_id'] . '">';
                            echo '<td>' . $row['des_fecha'] . '</td>';
                            echo '<td>' . $row['des_date'] . '</td>';
                            echo '<td> ' . $row['des_local'] . ' vs ' . $row['des_visita'] . '</td>';
                            echo '<td>' . '$ ' . $arancel_percibido . '</td>';
                            echo '<td>' . '$ ' . $diezmo . '</td>';
                            echo '<td>' . ($row['des_pagado'] == 1 ? 'Si' : 'No') . '</td>';
                            echo '</tr>';
                            $arancel_total += $arancel_percibido;
                            $entro = false;
                        }
                    }
                    ?>
                    <? print_r('las desig: '.$designaciones.' count: '.count($designaciones)); ?>
                </table>
                <div style="margin-left:0px !important;"id="cli_item" class="field span3">
                    <label style="float:left;margin-top: 15px;width: 49%;color: black;">Total percibido por el Juez: <?= '$ ' . $arancel_total ?></label>
                    <label class="detallesitos">Total aportes percibidos: <?= '$ ' . $diezmo_total ?></label>
                    <label class="detallesitos">Total de aportes entregados: <?= '$ ' . $entregado ?></label>
                    <label class="detallesitos">Total de aportes en mora: <?= '$ ' . $mora ?></label>
                </div> 
            </div>
        </div>
        <input id="non-printable" class="btn btn-primary" style="margin-bottom: 15px; margin-top: 22px;float: right;font-family: 'Lobster';text-transform: inherit;
               font-size: 20px;"type="button" value="Imprimir Informe" onclick="window.print();return false;" />
    </form>
</div>
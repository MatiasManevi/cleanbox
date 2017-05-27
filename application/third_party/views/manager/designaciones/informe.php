<style>
    body {
        background-color: #FFFFFF;
        padding-top: 60px;
    }
</style>
<div style="margin-left: 100px !important;"id="printable" class="contenedor_centro">
    <form style="overflow: visible;margin-bottom: 15px;" class="<?= isset($row) ? 'edit_form' : 'add_form' ?>" >

        <div class="row">

            <div class="hoja_informe">
                <h2 style="margin-bottom: 1px;text-align: center;">Detalles <?= $row->des_local . ' vs ' . $row->des_visita.' ('.$row->des_fecha.')' ?></h2>
                <p style="text-align: center;margin-bottom: 68px;"><?= Date('d-m-Y') ?></p>
                <div style="width: 100%; float: left;margin-bottom: 20px;" id="cli_item" ">
                     <label class="subtitle">Designaciones</label>
                </div>
                <table class="table table-hover">
                    <tr>    
                        <th>Categoria</th>
                        <th>Primer Juez</th>
                        <th>Segundo Juez</th>
                    </tr>
                    <?
                    foreach ($partidos->result() as $row) {
                        foreach ($aranceles->result() as $categoria) {
                            if ($categoria->aran_cate == $row->jor_cate) {
                                $arancel = $categoria->aran_price;
                                break;
                            }
                        }
                        $suma[$row->jor_pri_juez] += $arancel;
                        $suma[$row->jor_sec_juez] += $arancel;
                        echo '<tr class="reg_' . $row->jor_id . '">';
                        echo '<td>' . $row->jor_cate . '</td>';
                        echo '<td>' . $row->jor_pri_juez . '</td>';
                        echo '<td> ' . $row->jor_sec_juez . '</td>';
                        echo '</tr>';
                    }
                    ?>
                </table>
                <div style="width: 100%; float: left;margin-bottom: 20px;" id="cli_item">
                     <label class="subtitle">Aranceles Percibidos</label>
                </div>
                <? $total_aportes = 0; 
                foreach ($suma as $arbitro => $arancel_percibido) { ?>
                    <div style="margin-left:0px !important;"id="cli_item" class="field span3">                       
                        <label style="float:left;margin-left: 8px;width: 50%;color: black;"><?= $arbitro . ' percibio: ' . '$ '.$arancel_percibido ?> </label>
                        <? $aporte = $arancel_percibido*0.10?>
                        <?if($arbitro != 'Dilkin Samuel' && $arbitro != 'Figueredo Victor' && $arbitro != 'Moncada Emanuel' && $arbitro != 'Proenza Gonzalo' && $arbitro != 'Duarte Matias' && $arbitro != 'Duarte Cristian'){?>
                        <label style="float:left">Aportes al Colegio: <?= '$ '.$aporte ?></label>
                        <?$total_aportes += $aporte;
                        }else{?>
                        <label style="float:left">Aportes al Colegio: <?= '$ 0.00'?></label>
                        <?}?>
                    </div>
                <? }?>
                 <div style="margin-left:0px !important;"id="cli_item" class="field span3">
                     <label style="float:right;margin-top: 15px;width: 49%;color: black;">Total de aportes a entregar: <?= '$ '.$total_aportes ?></label>
                     <label style="float:right;margin-top: 15px;width: 49%;color: black;clear:both;">El estado de los aportes es: <?= isset($row) && $row->des_pagado == 1 ? 'Pagados' : 'No Pagados' ?></label>
                 </div> 
            </div>
        </div>
        <input id="non-printable" class="btn btn-primary" style="margin-bottom: 15px; margin-top: 22px;float: right;font-family: 'Lobster';text-transform: inherit;
               font-size: 20px;"type="button" value="Imprimir Informe" onclick="window.print();return false;" />
    </form>
</div>
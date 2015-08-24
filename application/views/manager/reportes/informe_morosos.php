<style>
    .table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
        padding: 0 !important;
    }
    body {
        background-color: white;
        padding-top: 60px;
    }
    .contenedor_centro{
        display: block;
        float: left;
        margin-left: 0px !important;
        width: 86%;
    }
</style>
<form style="overflow: visible;margin-bottom: 15px;">

    <div id="printable" class="">
        <div style="height: auto;" class="hoja_informe">
            <h2 style="font-size: 16px;float:left;margin-left: 15px;margin-top: 15px;">Posadas, Misiones</h2>           
            <h2 style="font-size: 12px;float:right;margin-right: 15px;margin-top: 15px;"><?= date('d-m-Y') ?></h2>
            <h2 style="margin-bottom: 8px;margin-top: 47px;">Informe Inquilinos Morosos al día: <?= $fecha ?></h2>
            <label style="text-align:center;width:100%;margin-bottom: 30px;font-size: 14.2px;">Nota: A los intereses punitorios ya se han descontados los depósitos a cuenta que se puedan haber hecho de los mismos </label>
            <?
            if (count($deudas_inquilinos) > 0) {
                foreach ($deudas_inquilinos as $key => $value) {
                    if (count($value) > 0) {
                        ?>

                        <table class="table">
                            <tr>
                                <th colspan="4" class="centrar">Inquilino: <?= $key ?></th>
                            </tr>    
                            <?
                            foreach ($inquilinos as $value_client) {
                                if ($value_client['client_name'] == $key) {
                                    ?>
                                    <tr>
                                        <th colspan="4" class="centrar">Contacto: <?= $value_client['client_celular'] . ' - ' . $value_client['client_tel'] ?></th>
                                    </tr>
                                    <tr>
                                        <th colspan="4" class="centrar">Contrato de: <?= $value_client['tipo'] ?> - Propietario: <?= $value_client['prop'] . ' -  Domicilio: ' . $value_client['domi'] ?></th>
                                    </tr>
                                <? } ?>
                            <? } ?>
                            <tr>    
                                <th class="centrar">Mes debido</th>
                                <th class="centrar">Dias en mora</th>
                                <th class="centrar">Monto</th>
                                <th class="centrar">Intereses</th>
                            </tr>    
                            <?
                            $acum_monto = 0;
                            $acum_interes = 0;
                            for ($z = 0; $z < count($value); $z++) {
                                echo '<tr>';
                                echo '<td class="centrar">' . $value[$z]['mes'] . ' ' . $value[$z]['ano'] . '</td>';
                                echo '<td class="centrar">' . $value[$z]['dias_mora'] . '</td>';
                                echo '<td class="centrar">$ ' . $value[$z]['monto'] . '</td>';
                                echo '<td class="centrar">$ ' . round($value[$z]['intereses'],2) . '</td>';
                                echo '</tr>';
                                $acum_monto += $value[$z]['monto'];
                                $acum_interes += $value[$z]['intereses'];
                            }
                            ?>
                            <?
                            if (count($deudas_inquilinos_serv) > 0) {
                                foreach ($deudas_inquilinos_serv as $key1 => $value1) {
                                    if ($key1 == $key) {
                                        for ($z = 0; $z < count($value1); $z++) {
                                            if (!empty($value1[$z])) {
                                                echo '<tr>';
                                                echo '<td class="centrar">' . $value1[$z]['concepto'] . ' ' . $value1[$z]['mes'] . ' ' . $value1[$z]['ano'] . '</td>';
                                                echo '<td class="centrar">' . $value1[$z]['dias_mora'] . '</td>';
                                                echo '<td class="centrar"> - </td>';
                                                echo '<td class="centrar"> - </td>';
                                                echo '</tr>';
                                            }
                                        }
                                    }
                                }
                            }
                            ?>
                            <tr>
                                <th colspan="2" class="centrar">Sub-totales</th>
                                <td class="centrar"><?= '$ ' . $acum_monto ?></td>
                                <td class="centrar"><?= '$ ' . round($acum_interes,2) ?></td>
                            </tr>
                            <? $total = ($acum_monto + $acum_interes) ?>
                            <tr>
                                <th colspan="2" class="centrar">Total</th>
                                <td colspan="2" class="centrar"><?= '$ ' . round($total,2) ?></td>
                            </tr>
                        </table>   
                        <?
                    }
                }
            }
            ?>

        </div>      
    </div>   
    <div id="non-printable">
        <input id="deb" class="btn btn-primary" style="margin-bottom: 15px; margin-top: 22px;float: right;font-family: 'futura';text-transform: inherit;
               font-size: 20px;" type="button" value="Imprimir Informe" onclick="window.print();return false;" />
        <a id="buttons_cli" style=" margin-top: 23px;" class="btn" href="<?= site_url('manager/reporte_morosos') ?>"><?= 'Volver' ?></a>
    </div>

</form>

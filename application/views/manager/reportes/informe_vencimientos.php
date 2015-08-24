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
            <h2 style="font-size: 12px;float:right;margin-right: 15px;margin-top: 15px;">Hoy: <?= date('d-m-Y') ?></h2>
            <h2 style="margin-bottom: 50px;margin-top: 47px;"><?= $encabezado ?></h2>
            <? if (count($contratos) > 0) { ?>
                <table class="table">
                    <tr>    
                        <th class="centrar">Propietario</th>
                        <th class="centrar">Inquilino</th>
                        <th class="centrar">Vencimiento</th>
                        <th class="centrar">Ultimo Pago</th>
                        <th class="centrar">Tipo Contrato</th>
                        <?
                        for ($x = 0; $x < count($contratos); $x++) {
                            echo '<tr class="reg_' . $contratos[$x]['con_id'] . '">';
                            echo '<td class="centrar">' . $contratos[$x]['con_prop'] . '</td>';
                            echo '<td class="centrar">' . $contratos[$x]['con_inq'] . '</td>';
                            echo '<td class="centrar">' . $contratos[$x]['con_venc'] . '</td>';
                            echo '<td class="centrar">' . $contratos[$x]['ultimo_pago']['cred_mes_alq'] . ' (' . $contratos[$x]['ultimo_pago']['cred_fecha'] . ')' . '</td>';
                            echo '<td class="centrar">' . $contratos[$x]['con_tipo'] . '</td>';
                            echo '</tr>';
                        }
                        ?>
                    </tr>
                </table>
            <? } else { ?>
                <span style="margin-left: 286px;">En este periodo no hay vencimiento de contratos !</span>
            <? }
            ?>
        </div>      
    </div>   
    <div id="non-printable">
        <input id="deb" class="btn btn-primary" style="margin-bottom: 15px; margin-top: 22px;float: right;font-family: 'futura';text-transform: inherit;
               font-size: 20px;" type="button" value="Imprimir Informe" onclick="window.print();return//setTimeout(redirect, 10000); false;" />
        <a id="buttons_cli" style=" margin-top: 23px;" class="btn" href="<?= site_url('manager/vencimientos') ?>"><?= 'Volver' ?></a>
    </div>

</form>


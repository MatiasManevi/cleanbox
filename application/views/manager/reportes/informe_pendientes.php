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
            <table class="table">
                <tr>    
                    <th class="centrar">Propietario</th>
                    <th class="centrar">Saldo Operativo Actual</th>
                </tr>    
                <?
                for ($x = 0; $x < count($pendientes); $x++) {
                    echo '<tr class="reg_' . $pendientes[$x]['cc_id'] . '">';
                    echo '<td class="centrar">' . $pendientes[$x]['cc_prop'] . '</td>';
                    echo '<td class="centrar">$ ' . ($pendientes[$x]['cc_saldo'] + $pendientes[$x]['cc_varios']) . '</td>';
                    echo '</tr>';
                }
                ?>

            </table>
        </div>      
    </div>   
    <div id="non-printable">
        <input id="deb" class="btn btn-primary" style="margin-bottom: 15px; margin-top: 22px;float: right;font-family: 'futura';text-transform: inherit;
               font-size: 20px;" type="button" value="Imprimir Informe" onclick="window.print();return" />
        <a id="buttons_cli" style=" margin-top: 23px;" class="btn" href="<?= site_url('manager/rendiciones_pendientes') ?>"><?= 'Volver' ?></a>
    </div>

</form>


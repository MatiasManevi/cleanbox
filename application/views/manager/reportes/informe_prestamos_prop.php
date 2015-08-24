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
            <h2 style="margin-bottom: 42px;margin-top: 47px;">Prestamos a Propietarios<?= ' (' . $desde . ' a ' . $hasta . ')' ?></h2>
            <h2 style="margin-bottom: 22px;margin-top: 17px;font-size: 16px;text-decoration: underline;">Prestamos en Mora</h2>
            <table class="table">
                <tr>    
                    <th class="centrar">Prestado A</th>
                    <th class="centrar">Monto</th>
                    <th class="centrar">Fecha</th>                   
                    <th class="centrar">Mes</th>


                    <?
                    $saldo = 0;
                    if (count($prestamos_mora) > 0) {
                        for ($x = 0; $x < count($prestamos_mora); $x++) {
                            echo '<tr class="reg_' . $prestamos_mora[$x]['cred_id'] . '">';
                            echo '<td class="centrar">' . $prestamos_mora[$x]['cred_cc'] . '</td>';
                            echo '<td class="centrar"> $ ' . $prestamos_mora[$x]['cred_monto'] . '</td>';
                            echo '<td class="centrar">' . $prestamos_mora[$x]['cred_fecha'] . '</td>';
                            echo '<td class="centrar">' . $prestamos_mora[$x]['cred_mes_alq'] . '</td>';
                            echo '</tr>';
                        }
                        echo '<tr>';
                        echo '<td class="centrar">Total Prestamos en Mora</td>';
                        echo '<td class="centrar"> $ ' . $total_prestado . '</td>';
                        echo '</tr>';
                    }
                    ?>
                </tr>
            </table>

            <h2 style="margin-bottom: 22px;margin-top: 17px;font-size: 16px;text-decoration: underline;">Prestamos Devueltos</h2>
            <table class="table">
                <tr>    
                    <th class="centrar">Prestado A</th>
                    <th class="centrar">Monto</th>
                    <th class="centrar">Fecha</th>                   
                    <th class="centrar">Mes</th>


                    <?
                    $saldo = 0;
                    if (count($prestamos_devueltos) > 0) {
                        for ($x = 0; $x < count($prestamos_devueltos); $x++) {
                            echo '<tr class="reg_' . $prestamos_devueltos[$x]['cred_id'] . '">';
                            echo '<td class="centrar">' . $prestamos_devueltos[$x]['cred_cc'] . '</td>';
                            echo '<td class="centrar"> $ ' . $prestamos_devueltos[$x]['cred_monto'] . '</td>';
                            echo '<td class="centrar">' . $prestamos_devueltos[$x]['cred_fecha'] . '</td>';
                            echo '<td class="centrar">' . $prestamos_devueltos[$x]['cred_mes_alq'] . '</td>';
                            echo '</tr>';
                        }
                        echo '<tr>';
                        echo '<td class="centrar">Total Prestamos Devueltos</td>';
                        echo '<td class="centrar"> $ ' . $total_devuelto . '</td>';
                        echo '</tr>';
                    }
                    ?>
                </tr>
            </table>
        </div>      
    </div>
   
    <div id="non-printable">
        <input id="deb" class="btn btn-primary" style="margin-bottom: 15px; margin-top: 22px;float: right;font-family: 'futura';text-transform: inherit;
               font-size: 20px;" type="button" value="Imprimir Informe"/>
        <a id="buttons_cli" style=" margin-top: 23px;" class="btn" href="<?= site_url('manager/prestamos') ?>"><?= 'Volver' ?></a>
    </div>

</form>


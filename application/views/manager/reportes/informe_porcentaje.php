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
            <? $porcentaje_rendiciones = round($props_rendidos / $props_activos, 2) * 100 ?>
            <div style="margin: 46px auto 0;width: 87%;" class="leyenda">
                En el rango de fechas determinado se han calculado que existen:
                <br><br><strong><?= $props_activos ?></strong> propietarios activos (perciben ingresos por al menos un contrato en vigencia)
                <br><strong><?= $props_rendidos ?></strong> propietarios cuyas rendiciones fueron efectuadas
                <br><br>Por lo tanto, el porcentaje de rendiciones se calcula en un <strong><?= $porcentaje_rendiciones ?> %</strong>.
            </div>
        </div>      
    </div>   
    <div id="non-printable">
        <input id="deb" class="btn btn-primary" style="margin-bottom: 15px; margin-top: 22px;float: right;font-family: 'futura';text-transform: inherit;
               font-size: 20px;" type="button" value="Imprimir Informe" onclick="window.print();return" />
        <a id="buttons_cli" style=" margin-top: 23px;" class="btn" href="<?= site_url('manager/porcentaje_rendiciones') ?>"><?= 'Volver' ?></a>
    </div>

</form>


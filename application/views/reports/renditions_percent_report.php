<div id="printable" class="report_sheet">
    <h4 style="float:right;margin-right: 15px;margin-top: 15px;"><?php echo date('d-m-Y') ?></h4>
    <h4 style="margin-bottom: 50px;margin-top: 47px;"><?php echo $heading ?></h4>

    <div style="margin: 46px auto 0;width: 100%;margin-left: 162px;" class="leyenda">
        En el rango de fechas determinado se han calculado que existen:
        <br><br><strong><?php echo $active_accounts ?></strong> propietarios activos (perciben ingresos por al menos un contrato en vigencia)
        <br><strong><?php echo $renditioned_accounts ?></strong> propietarios cuyas rendiciones fueron efectuadas
        <br><br>Por lo tanto, el porcentaje de rendiciones se calcula en un <strong><?php echo round($renditioned_accounts / $active_accounts, 2) * 100 ?> %</strong>.
    </div>
</div>      

<div id="non-printable" class="report_actions">
    <a class="btn btn-primary button_report" href="javascript:;" onclick="window.print();return false;">Imprimir</a>
    <a class="btn btn-primary button_report" href="<?php echo site_url('renditionsPercentReport') ?>">Volver</a>
</div>



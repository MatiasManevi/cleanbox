<style>

@charset "utf-8";
@font-face {
    font-family: 'MyriadProSemibold';
    src: url('../../../fonts/myriadpro-semibold_0-webfont.eot');
    src: url('../../../fonts/myriadpro-semibold_0-webfont.eot?iefix') format('eot'),
    url('../../../fonts/myriadpro-semibold_0-webfont.woff') format('woff'),
    url('../../../fonts/myriadpro-semibold_0-webfont.ttf') format('truetype'),
    url('../../../fonts/myriadpro-semibold_0-webfont.svg#webfontSMxjHBeJ') format('svg');
    font-weight: normal;
    font-style: normal;
}
@font-face {
    font-family: 'MagnetoBold';
    src: url('../../../fonts/magnetob-webfont.eot');
    src: url('../../../fonts/magnetob-webfont.eot?iefix') format('eot'),
    url('../../../fonts/magnetob-webfont.woff') format('woff'),
    url('../../../fonts/magnetob-webfont.ttf') format('truetype'),
    url('../../../fonts/magnetob-webfont.svg#webfontSMxjHBeJ') format('svg');
    font-weight: normal;
    font-style: normal;
}
@font-face {
    font-family: 'MyriadProRegular';
    src: url('../../../fonts/myriad_pro_regular-webfont.eot');
    src: url('../../../fonts/myriad_pro_regular-webfont.eot?#iefix') format('embedded-opentype'),
    url('../../../fonts/myriad_pro_regular-webfont.woff') format('woff'),
    url('../../../fonts/myriad_pro_regular-webfont.ttf') format('truetype'),
    url('../../../fonts/myriad_pro_regular-webfont.svg#MyriadProRegular') format('svg');
    font-weight: normal;
    font-style: normal;
}
@font-face {
    font-family: 'futura';
    src: url('../../../fonts/futura-webfont.eot');
    src: url('../../../fonts/futura-webfont.eot?#iefix') format('embedded-opentype'),
    url('../../../fonts/futura-webfont.woff') format('woff'),
    url('../../../fonts/futura-webfont.ttf') format('truetype'),
    url('../../../fonts/futura-webfont.svg#futura1.4Regular') format('svg');
    font-weight: normal;
    font-style: normal;
}
@font-face {
    font-family: 'futura';
    src: url('../../../fonts/futura_light-webfont.eot');
    src: url('../../../fonts/futura_light-webfont.eot?#iefix') format('embedded-opentype'),
    url('../../../fonts/futura_light-webfont.woff2') format('woff2'),
    url('../../../fonts/futura_light-webfont.woff') format('woff'),
    url('../../../fonts/futura_light-webfont.ttf') format('truetype'),
    url('../../../fonts/futura_light-webfont.svg#futura_lightregular') format('svg');
    font-weight: normal;
    font-style: normal;
}
*{
    border:none;
    margin:0;
    padding:0;
}
a:active, a:visited, a:focus,button{
    outline: 0 none;
    text-decoration: none;
}
a{
    text-decoration: none;
}
a:hover{
    text-decoration: underline;
}
p{
    margin-bottom: 10px;
}
h1,h2,h3,h4,h5{
    font-weight: normal;
}
li{
    list-style: none;
}
body{
    font-family: "Trebuchet MS";
    background: Menu;
    padding-top: 60px;
}
input, select, textarea{
    border:1px solid #010101;
    -moz-border-radius: 4px 4px 4px 4px;
    -webkit-border-top-left-radius: 4px;
    -webkit-border-top-right-radius: 4px;
    -webkit-border-bottom-left-radius: 4px;
    -webkit-border-bottom-right-radius: 4px;
    border-radius: 4px 4px 4px 4px;
    z-index: 0 !important;
}
input[type="checkbox"],input[type="radio"]{
    border:none;
}
input[readonly]{
    cursor:initial !important;
}
.reports_header_separator{
    color: #999999;
    padding-left: 22px;
    font-weight: 700;
    font-family: inherit;
    width: 256px;
    border-bottom: 1px solid white;
    margin-left: -6px;
    font-size: 15px;
}
button, .button{
    color: white;
    cursor:pointer;
    font-size:10px;
    text-transform:uppercase;
    -moz-border-radius: 4px 4px 4px 4px;
    -webkit-border-top-left-radius: 4px;
    -webkit-border-top-right-radius: 4px;
    -webkit-border-bottom-left-radius: 4px;
    -webkit-border-bottom-right-radius: 4px;
    border-radius: 4px 4px 4px 4px;
}
/* INFORMES */
.report_sheet{
    background: none repeat scroll 0 0 window;
    margin: 0 auto;
    padding: 5px;
    width: 1100px;
    font-family: monospace;
}
.movements_zero{
    margin-left: 8px;
    position: relative;
    top: 45px;
}
.report_sheet h4{
    margin-bottom: 20px;
    padding-top: 30px;
    text-align: center;
    text-decoration: underline;
}
.report_sheet label{
    text-align: center;
    width: 100%;
    margin-bottom: 17px;
}
.cell{
    text-align: center !important;
    border: 1px solid black !important;
    padding: 4px !important;
}
.button_report{
    float: right;
    font-family: 'futura';
    text-transform: capitalize !important;
    margin-right: 3px;
}
.report_actions{
    margin-bottom: 47px;
    margin-left: 123px;
}
.totales{
    clear: both;
    float: left;
    margin-right: 76px;
    margin-left: 24px;
    margin-top: 24px;
}
.acumulatives{
    border: 1px solid;
    float: left;
    margin-bottom: 20px;
    margin-left: 20px;
    margin-top: 10px;
    padding: 4px;
    font-size: 13px;
    width: auto;
}
.extract{
    background: #F5DA81;
}
/* HEADER MENU DESPLEGABLE*/
.header_user_icon{
    padding: 14px;
    font-size: 13px;
    margin-top: 4px;
    margin-right: 11px;
    margin-left: 11px;
}
ul.nav li:first-child a { border-left: none; }
ul.nav li:last-child a{ border-right: none; }
ul.nav li:hover > a { color: #ffffff;text-decoration: none; }
ul.nav ul {
    background: none repeat scroll 0 0 #1B1B1B;
    margin-left: 2px;
    padding-left: 6px;
    position: absolute;
    transition: opacity 0.25s ease 0.1s;
    position: absolute;
    -webkit-border-radius: 0 0 5px 5px;
    -moz-border-radius: 0 0 5px 5px;
    border-radius: 0 0 5px 5px;
    -webkit-transition: opacity .25s ease .1s;
    -moz-transition: opacity .25s ease .1s;
    -o-transition: opacity .25s ease .1s;
    -ms-transition: opacity .25s ease .1s;
    transition: opacity .25s ease .1s;
}
ul.nav li:hover > ul { opacity: 1; }
ul.nav ul li {
    height: 0;
    overflow: hidden;
    padding: 0;
    width: 250px;
    -webkit-transition: height .25s ease .1s;
    -moz-transition: height .25s ease .1s;
    -o-transition: height .25s ease .1s;
    -ms-transition: height .25s ease .1s;
    transition: height .25s ease .0s;
}
ul.nav li:hover > ul li {
    height: 36px;
    overflow: visible;
    padding: 0;
}
ul.nav ul li a {
    width: 244px;
    padding: 0 10px;
    line-height: 32px;
    margin: 0;
    font-size: 14px;
    color:#999999;
    border: none;
}
ul.nav ul li:last-child a { border: none; }
/* HEADER MENU DESPLEGABLE*/
.home_maintenances{
    float: left;
    margin-right: 32px;
    width: 47%;
    clear: both;
}
.home_maintenances .panel-body{
    max-height: 355px;
    overflow-x: hidden;
    overflow-y: scroll;
}
.transferencias{
    clear: both;
    margin-left: 20px;
}
.tranfer{
    clear: both;
    margin-top: 5px;
}
.tranfer span{
    float: left;
    margin-bottom: 8px;
    margin-right: 14px;
}
.referencias{
    clear: both;
    margin-left: 20px;
    margin-top: 5px;
}
.glyphicon {
    text-decoration: none !important;
}
#ccnav a:hover{
    color:black;
}
@media print
{
    #non-printable { display: none; }
    ._non_printable { display: none; }
    #printable { display: block;}
}
.section .section_description{
    clear: both;
    float: left;
    margin-right: 32px;
    margin-top: 20px;
    margin-bottom: 20px;
    width:100%;
}
.section .section_description label{
    font-size: 14px;
}
.section .section_form{
    width: 100%;
    float: left;
}
.section .section_form .section_input{
    clear: both;
    margin-bottom: 5px;
    margin-right: 5px;
    width: 24%;
    float: left;
}
.section .section_form label {
    float: left;
    font-size: 13px;
}
.section .section_form .section_area{
    clear:both;
    width: 50%;
    height: 15%;
    margin-bottom: 5px;
    float: left;
}
.section .section_form .section_selects{
    clear: both;
    float: left; 
    margin-top: 6px;
    width: 30%;
}
.section .section_form .add_button{
    float: left;
    clear: both;
    font-size: 13px;
}
.section .section_form .sub{
    width: 399px;
}
.section .section_form button{
    font-family: "futura";
    margin-left: 6px;
    text-transform: inherit;
    font-size: 15px;
    margin-bottom: 15px;
    clear: both;
    float: left;
    line-height: 0;
    margin-top: 15px;
}
.section .section_form .clear_button{
    font-family: "futura";
    margin-left: 6px;
    text-transform: inherit;
    font-size: 15px;
    margin-bottom: 15px;
    clear: none;
    float: left;
    line-height: 18px;
    height: 30px;
    margin-top: 15px;
}
.section .section_form .separate_line{
    margin-bottom: 13px;
    float: left;
    width: 100%;
    border: 1px solid;
    color: gray; 
}
.section .filter_container{
    height: 50px;
    margin-top:20px;
    margin-bottom: 2px;
}
.section .filter_container .filter_input{
    margin-right: 5px;
    font-size: 13px;
    width: 18%;
    float: left;
}
.section .filter_container .refresh{
    margin-top: 8px;
    text-decoration: none;
    cursor: pointer;
}
.section .filter_container button{
    margin-top: -3px;
    font-size: 15px;
    text-transform: inherit;
    font-family: "futura";
}
.period_container, .service_container{
    clear: both;
    float: left;
    width: 100%;
    margin-bottom: 10px;
}
.credit_block{
    width: 100%;
    float: left;
    margin-top: 10px;
    margin-bottom: 10px;
}
.debit_block{
    width: 100%;
    float: left;
    margin-top: 10px;
}
.contract_info{
    margin-top: 10px;
    width: 100%;
    float: left;
}
.credit_contract_details{
    float: left;
    margin-top: 34px;
}
.contract_detail{
    float: left;
    width: 13%;
    margin-right: 4px;
}
.send_mail_container{
    float: left;
    width: 5%;
    display: none;
    margin-left: 12px;
    margin-top: 20px;
    margin-bottom: 20px;
}
.contract_selects{
    width: 100%;
    float: left;
    margin-top: 10px;
    margin-bottom: 10px;
}
.contract_fields{
    margin-bottom: 5px;
    margin-right: 5px;
    float: left;
    font-size: 13px !important;
}
.active_contracts{
    margin-right: 5px;
    font-size: 13px; 
    width: 403px; 
    float: left;
}
.contract_selects .select{
    float: left;
    margin-right: 6px;
    width: 18%;
}
.contract_periods, .contract_services{
    float: left;
    width: 100%;
    margin-top: 8px;
}
.current_period{
    background:#B0C4DE;
    border: 2px solid;
}
.contract_detail input{
    width: 50%;
    float: left;
}
.credit_types{
    float: left;
}
.credit_parts{
    width: 50%;
    float: left;
}
.totals{
    display: none;
    width: 100%;
    float: left;
    margin-top: 10px;
}
.forma_pago_select{
    float: left;
    margin-right: 7px;
    width: 105px;
}
.inactive {
    pointer-events: none;
    cursor: default;
}
.table{
    font-size: 14px;
}
.delete_area{
    font-size: 25px;
    color: black;
    cursor: pointer;
}
.delete_area:hover{
    text-decoration: none;
}
.provider_area{
    width: 100%;
    float: left;
    clear: both;
}
.areas{
    margin-top: 18px;
    width: 100%;
    float: left;
}
.tablas{
    float: left;
    margin-bottom: 0;
    margin-right: 23px;
    width: 34%;
}
a.delete{
    color: white;
    margin-left: 6px;
    font-size: 11px;
    font-weight: bold;
}
a.delete:hover{
    text-decoration: none;
    color:white;
}
.maintenance_status{
    font-size: 14px;
}
.modal-body .description{
    font-size: 14px;
}
.submit_button{
    font-family: "Trebuchet MS";
    margin-right: 13px;
    font-size: 20px;
    line-height: 0;
    width: auto;
    height: 30px;
}
.button_back{
    float: right;
    font-size: 20px;
}
.cash_flush{
    width: 47%;
    float: left;
    margin-right: 32px;
}
.cash_transfers{
    float: left;
    margin: 0 auto;
    width: 49%;
}
.logo_container label{
    font-size: 15px !important;
    margin-bottom: 15px;
    margin-left: 30px;
}
.logo_container {
    position: absolute;
    right: 300px;
    top: 237px;
}
.uploadifyQueueItem{
    display: none;
    /*    dont show progress bar*/
}
#logo{ 
    display: none;
}
.logo{
    clear: both;
    float: left;
}
.button_uploader{
    float: left;
    clear: both;
    margin-top: 19px;
    margin-left: 17px;
}
.img_shadow {
    border: 4px solid #FFFFFF;
    border-radius: 5px 5px 5px 5px;
    box-shadow: 0 0 20px 5px #A0A0A0;
}
#logoUploader {
    height: 30px;
    width: 106px;
}
/*like inline*/
.clear_none{
    clear: none !important;
}
.margB33{
    margin-bottom: 33px;
}
.margin0{
    margin: 0;
}
.back_menu{
    background: menu;
}
.floatRight{
    float: right;
}
.alignLeft{
    text-align: left;
}
.alignCenter{
    text-align: center;
}
.alignRight{
    text-align: right;
}
.priority-maximum{
    background: red;
    border-color: red;
}
.priority-medium{
    background: yellow;
    border-color: yellow;
}
.priority-low{
    background: greenyellow;
    border-color: greenyellow;
}
.priority-medium, .priority-low span{
    color:black;
    font-family: "Trebuchet MS"
}
.priority-maximum span{
    color:white;
    font-family: "Trebuchet MS"
}
.color_green, .color_green:hover{
    color:darkseagreen;
}
.color_yellow, .color_yellow:hover{
    color:yellow;
}
.clear_both{
    clear: both !important;
}
.zindexHigh{
    z-index: 9999999;
}
.zindexNone{
    z-index: -1;
}
</style>

<div id="printable" class="report_sheet">
    <h4 style="float:left;margin-left: 10px">Posadas, Misiones</h4>           
    <h4 style="float:right;margin-right: 10px;"><?php echo date('d-m-Y') ?></h4>
    <h4>Informe Detallado Cta. Cte. <?php echo $account['cc_prop'] ?></h4>
    <h4>Periodo <?php echo ' (' . $from . ' a ' . $to . ')' ?></h4>

    <h4>Movimientos Cta. Principal</h4>
    <table class="table">
        <tr>    
            <th class="cell">Concepto</th>
            <th class="cell">Fecha</th>
            <th class="cell">Debitos</th>
            <th class="cell">Creditos</th>
            <th class="cell">Saldo</th>
        </tr>
        <?php $sald = 0; ?>
        <?php if (count($principal_movements) > 0) { ?>
            <?php foreach ($principal_movements as $movement) { ?>
                <tr class="reg_<?php echo $movement['id']; ?>">
                    <td class="cell"><?php echo $movement['show_concept']; ?></td>
                    <td class="cell"><?php echo $movement['date']; ?></td>
                    <?php
                    if ($movement['type'] == 'credito') {
                        $sald += $movement['amount'];
                        ?>
                        <td class="cell"></td>
                        <td class="cell">$ <?php echo $movement['amount']; ?></td>
                        <?php
                    } else {
                        $sald -= $movement['amount'];
                        ?>
                        <td class="cell">$ <?php echo $movement['amount']; ?></td>
                        <td class="cell"></td>
                    <?php } ?>
                    <td class="cell">$ <?php echo round($sald, 2); ?></td>
                </tr>
            <?php } ?>
            <tr>
                <td colspan="2" class="cell"></td>
                <td class="cell">$ <?php echo $principal_outs; ?></td>
                <td class="cell">$ <?php echo $principal_ins; ?></td>
                <td class="cell">$ <?php echo round($sald, 2); ?></td>
            </tr>
        <?php } else { ?>
            <div class="movements_zero">No se registraron movimientos en las fechas indicadas</div>
        <?php } ?>
    </table>

    <h4>Movimientos Cta. Secundaria</h4>
    <table class="table">
        <tr>    
            <th class="cell">Concepto</th>
            <th class="cell">Fecha</th>
            <th class="cell">Debitos</th>
            <th class="cell">Creditos</th>
            <th class="cell">Saldo</th>
        </tr>
        <?php $secondary_sald = 0; ?>
        <?php if (count($secondary_movements) > 0) { ?>
            <?php foreach ($secondary_movements as $movement) { ?>
                <?php if ($movement['trans']) { ?>
                    <tr class="reg_<?php echo $movement['id']; ?>">
                        <td class="cell"><?php echo $movement['show_concept']; ?></td>
                        <td class="cell"><?php echo $movement['date']; ?></td>
                        <?php
                        if ($movement['type'] == 'credito') {
                            $secondary_sald += $movement['amount'];
                            ?>
                            <td class="cell"></td>
                            <td class="cell">$ <?php echo $movement['amount']; ?></td>
                            <?php
                        } else {
                            $secondary_sald -= $movement['amount'];
                            ?>
                            <td class="cell">$ <?php echo $movement['amount']; ?></td>
                            <td class="cell"></td>
                        <?php } ?>
                        <td class="cell">$ <?php echo round($secondary_sald, 2); ?></td>
                    </tr>
                <?php } ?>
            <?php } ?>
            <tr>
                <td colspan="2" class="cell"></td>
                <td class="cell">$ <?php echo $secondary_outs; ?></td>
                <td class="cell">$ <?php echo $secondary_ins; ?></td>
                <td class="cell">$ <?php echo round($secondary_sald, 2); ?></td>
            </tr>
        <?php } else { ?>
            <div class="movements_zero">No se registraron movimientos en las fechas indicadas</div>
        <?php } ?>
    </table>

    <h4>Estado de cuenta</h4>
    <table class="table">
        <tr>
            <th class="cell">Saldo del periodo: </th><td colspan="3" class="cell">$ <?php echo round($sald + $secondary_sald, 2); ?></td>
        </tr>
        <tr>
            <th class="cell">Saldo operativo: </th><td colspan="3" class="cell">$ <?php echo round($account['cc_saldo'] + $account['cc_varios'], 2); ?></td>
        </tr>
    </table>

    <?php if (count($contracts) > 0 && $contracts_period_status) { ?>
        <div id="non-printable">
            <h4>Contratos vigentes</h4>
            <?php foreach ($contracts as $contract) { ?>  
                <table class="table">
                    <tr>
                        <td class="cell"colspan="4">Contrato con <?php echo $contract['con_inq'] ?> en: <?php echo $contract['con_domi'] ?></td>
                    </tr>
                    <tr>    
                        <th class="cell">Concepto</th>
                        <th class="cell">Pagado/Controlado</th>
                        <th class="cell">Fecha</th>
                        <th class="cell">Mes</th>
                    </tr>
                    <?php foreach ($contracts_period_status as $contract_id => $contract_period_status) { ?>  
                        <?php if ($contract_id == $contract['con_id']) { ?>  
                            <?php foreach ($contract_period_status['principals'] as $principal) { ?>
                                <tr class="reg_<?php echo $contract_id; ?>">
                                    <td class="cell"><?php echo $principal['concept']; ?></td>
                                    <td class="cell"><?php echo $principal['action']; ?></td>
                                    <td class="cell"><?php echo $principal['date']; ?></td>
                                    <td class="cell"><?php echo $principal['month']; ?></td>
                                </tr>
                            <?php } ?>
                            <?php foreach ($contract_period_status['secondarys'] as $secondary) { ?>
                                <tr class="reg_<?php echo $contract_id; ?>">
                                    <td class="cell"><?php echo $secondary['concept']; ?></td>
                                    <td class="cell"><?php echo $secondary['action']; ?></td>
                                    <td class="cell"><?php echo $secondary['date']; ?></td>
                                    <td class="cell"><?php echo $secondary['month']; ?></td>
                                </tr>
                            <?php } ?>
                        <?php } ?>
                    <?php } ?>
                </table>
            <?php } ?>
        </div>
    <?php } ?>

    <?php if (count($intereses_debt) > 0) { ?>
        <div id="non-printable">
            <h4>Intereses en Mora</h4>
            <table class="table">
                <tr>    
                    <th class="cell">Inquilino</th>
                    <th class="cell">Fecha Pago Alquiler</th>
                    <th class="cell">Monto debido</th>
                </tr>
                <?php foreach ($intereses_debt as $interes_debt) { ?>
                    <tr class="reg_<?php echo $interes_debt['int_id']; ?>">  
                        <td class="cell"><?php echo $interes_debt['int_depositante']; ?></td>
                        <td class="cell"><?php echo $interes_debt['int_fecha_pago']; ?></td>
                        <td class="cell">$ <?php echo $interes_debt['int_amount']; ?></td>
                    </tr>
                <?php } ?>
            </table>
        </div> 
    <?php } ?>

    <?php if (count($comentaries) > 0) { ?>
        <div id="non-printable">
            <h4>Comentarios</h4>
            <table class="table">
                <tr>    
                    <th class="cell">Fecha</th>
                    <th class="cell">Inmueble</th>
                    <th class="cell">Comentario</th>
                </tr>
                <?php foreach ($comentaries as $comentary) { ?>
                    <tr class="reg_<?php echo $comentary['com_id']; ?>">  
                        <td class="cell"><?php echo $comentary['com_date']; ?></td>
                        <td class="cell"><?php echo $comentary['com_dom']; ?></td>
                        <td class="cell"><?php echo $comentary['com_com']; ?></td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    <?php } ?>
    <?php if ($account['cc_prop'] != 'CAJA FUERTE' && $account['cc_prop'] != 'INMOBILIARIA') { ?>
        <table class="table">
            <tr>
                <td style="border:none"> Recibimos conforme de <?php echo $bussines_name; ?>, la cantidad de Pesos (<?php echo '$ ' . $today_rendition_amount . ') ' . $today_rendition_amount_letra ?>
                    en concepto de rendición de cuenta por la cobranza de alquileres, habiendo verificado los comprobantes de ingresos y egresos del inmueble/s
                    sito domicilio/os <?php echo $address_rendition ?>
                    correspondiente al mes/es de <?php echo $month_rendition ?></td>
            </tr>
            <tr>
                <td style="border:none">Firma: ________________________              <?php echo $account['cc_prop'] ?></td>
            </tr>
        </table>
    <?php } ?>
</div>      

<div id="non-printable" class="report_actions">
    <a class="btn btn-primary button_report" href="javascript:;" onclick="window.print();return false;">Imprimir</a>
</div>



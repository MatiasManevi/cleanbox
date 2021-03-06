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


<div id="printable" class="report_sheet _excel">
    <?php
    $total = 0;
    ?>
    <div id="non-printable" class="report_actions">
        <a class="btn btn-primary button_report" href="javascript:;" onclick="window.print();return false;">Imprimir</a>
        <a class="btn btn-primary button_report" href="<?php echo site_url('accountsBalanceReport') ?>">Volver</a>
    </div>

    <h4>Informe de Balance de <?php echo $account_name ?> del <?php echo $year ?></h4>

    <table class="table table-hover">
        <tr>    
            <th class="cell">Mes</th>
            <th class="cell">Creditos</th>
            <th class="cell">Debitos</th>
            <th class="cell">Balance mensual</th>
        </tr>
        <?php if (count($balances) > 0) { ?>
            <?php foreach ($balances as $key => $month_balance) { ?>
                <?php $total += $month_balance[0]['balance']; ?>
                <tr>
                    <td class="cell" title="Entradas del periodo"><?php echo $key; ?></td>
                    <td class="cell" title="Entradas del periodo">$ <?php echo $month_balance[0]['ins']; ?></td>
                    <td class="cell" title="Salidas del periodo">$ <?php echo $month_balance[0]['outs']; ?></td>
                    <td class="cell" title="Saldo del periodo">$ <?php echo $month_balance[0]['balance']; ?></td>
                </tr>
            <?php } ?>
                <tr>
                    <td  colspan="2"></td>
                </tr>
                <tr>
                    <td class="cell" colspan="3">Balance anual</td>
                    <td class="cell" >$ <?php echo $total; ?></td>
                </tr>
        <?php } ?>
    </table>

</div>
<script>
    $(function(){
        $("._excel").tableExport({
            formats: ["xlsx"],  
            position: 'bottom',
            fileName: "Informe de Balance de <?php echo $account_name ?> del <?php echo $year ?>"
        });
    });
</script>
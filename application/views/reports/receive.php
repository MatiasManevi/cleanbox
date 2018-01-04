<div class="canvas_receive">

<style>
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

<?php if (User::printCopy()) { ?>
    <!-- copiaaa -->
    <style>
        body {
            background-color: white;
            padding-top: 60px;
        }
        .receive_table {
            font-size: 13px;
            font-family: monospace !important;
        /*    letter-spacing: 2px;*/
        }
        .receive_table .dashed{
            border-top: 1px dashed black;
        }
        th, td {
            padding: 5px;
        }
        .receive{
            background: none repeat scroll 0 0 window;
            border: medium none;
            margin: 0 auto;
            margin-top: 90px;
            width: 800px;
        }
        .total_receive{
            font-size: 18px;
            font-weight: bold;
        }
        .receive_cel{
            text-align: center !important;
            border: 1px dashed black !important;
            padding: 3px !important;
        }
        .receive .receive_header{
            width: 884px;
            margin: 0 auto;
            float: left;
            margin-bottom: 25px;
        }
        .receive .left_header{
            float: left;
        }
        .receive .left_header img{
            width: 100px;
            margin: 0 auto;
            display: block;
        }
        .receive .left_header p{
            width: 325px;
            font-size: 11px;
            text-align: center;
            margin:0px;
        }
        .receive .center_header{
            float: left;
            width: 24%;
        }
        .receive .right_header{
            float: left;
            margin-left: 21px;
            text-align: left;
            margin-top: 81px;
        }
        .receive .right_header p{
            font-size: 11px;
            margin: 0;
        }
        .receive .right_header p strong.recibo{
            margin-left: 138px;
            font-size: 14px;
        }
        .receive .receive_type{
            font-weight: bold;
            font-size: 38px;
            text-align: center;
            padding: 1px;
            border-radius: 6px;
            border: 1px solid;
            margin: 0 auto;
            width: 58px;
        }
        .receive .type_label{
            font-size: 8px;
            margin: 0 auto;
            width: 68px;
            text-align: center;
        }
        @media all {
            .receive{
                width: 900px;
            }
        }
        @media screen{
            .receive{
                background: none repeat scroll 0 0 window;
                border: medium none;
                margin: 0 auto;
                margin-top: 60px;
                width: 900px;
            }
        }
        @media print
        {
            .container-fluid{
                margin-top: -130px;
            }
            .receive{
                margin-bottom: 75px;
                position: relative;
                page-break-after: avoid;
                page-break-inside: avoid;
                background: none repeat scroll 0 0 window;
                border: medium none;
                width: 900px;
            }
            .receive:last-child{
                page-break-after: always;
            }
        }
    </style>
<?php } else { ?>
    <!-- SIN copiaaa -->
    <style>
        body {
            background-color: white;
            padding-top: 60px;
        }
        .receive_table {
            font-size: 13px;
            font-family: monospace !important;
        /*    letter-spacing: 2px;*/
        }
        .receive_table .dashed{
            border-top: 1px dashed black;
        }
        th, td {
            padding: 5px;
        }
        .receive{
            background: none repeat scroll 0 0 window;
            border: medium none;
            margin: 0 auto;
            margin-top: 90px;
            width: 800px;
        }
        .total_receive{
            font-size: 18px;
            font-weight: bold;
        }
        .receive_cel{
            text-align: center !important;
            border: 1px dashed black !important;
            padding: 3px !important;
        }
        .receive .receive_header{
            width: 884px;
            margin: 0 auto;
            float: left;
            margin-bottom: 25px;
        }
        .receive .left_header{
            float: left;
        }
        .receive .left_header img{
            width: 100px;
            margin: 0 auto;
            display: block;
        }
        .receive .left_header p{
            width: 325px;
            font-size: 11px;
            text-align: center;
            margin:0px;
        }
        .receive .center_header{
            float: left;
            width: 24%;
        }
        .receive .right_header{
            float: left;
            margin-left: 21px;
            text-align: left;
            margin-top: 110px;
        }
        .receive .right_header p{
            font-size: 11px;
            margin: 0;
        }
        .receive .right_header p strong.recibo{
            margin-left: 138px;
            font-size: 14px;
        }
        .receive .receive_type{
            font-weight: bold;
            font-size: 38px;
            text-align: center;
            padding: 1px;
            border-radius: 6px;
            border: 1px solid;
            margin: 0 auto;
            width: 58px;
        }
        .receive .type_label{
            font-size: 8px;
            margin: 0 auto;
            width: 68px;
            text-align: center;
        }
        @media all {
            .receive{
                width: 900px;
            }
        }
        @media screen{
            .receive{
                background: none repeat scroll 0 0 window;
                border: medium none;
                margin: 0 auto;
                margin-top: 60px;
                width: 900px;
            }
        }
        @media print
        {
            .receive{
                position: relative;
                page-break-after: always;
                page-break-inside: avoid;
                background: none repeat scroll 0 0 window;
                border: medium none;
                margin: 0 auto;
                margin-top: 60px;
                width: 900px;
            }
            .receive:last-child{
                page-break-after: avoid;
            }
        }
    </style>
<?php } ?>


<div id="printable">
    <?php 

    if($settings['print_copy']) { 
        $times = 2;
     } else {
        $times = 1;
     }
    for ($i = 0; $i < $times; $i++) { 
        if($i == 0){?>
        <div class="original">
        <?php }
        foreach ($receives as $receive) {

            ?>
            <div class="receive">
                
                <?php if($settings['build_receive_header']){ ?>
                    <div class="receive_header">

                        <div class="left_header">
                            <img class="_image_logo" src="<?php echo img_url() . 'bussines_logos/' . $settings['logo'] ?>" alt="logo"/>
                            <!-- <p><strong><?php echo $settings['activity']; ?></strong></p> -->
                            <p><?php echo $settings['address'] . ' - Tel: ' . $settings['phone'] . ' - Cel: ' . $settings['cel_phone']; ?></p>
                            <p><?php echo $settings['zip_code'] . ' ' . $settings['city'] . ' - ' . $settings['state'] . ' - ' . $settings['site_url']; ?></p>
                            <p><?php echo 'e-mail: ' . $settings['email']; ?></p>
                        </div>

                        <div class="center_header">
                            <div class="receive_type">X</div>
                            <div class="type_label">DOCUMENTO NO VALIDO COMO FACTURA</div>
                        </div>

                        <div class="right_header">
                            <p><strong><?php echo $settings['fiscal_status']; ?></strong></p>
                            <p>CUIT: <?php echo $settings['cuit']; ?></p>
                            <p>Ing. Brutos: <?php echo $settings['iibb_number']; ?></p>
                            <p>Fecha INIC. ACT.: <?php echo $settings['init_activity_date'] . '       '?><strong class="recibo">RECIBO</strong></p>
                        </div>

                    </div>
                <?php } ?>

                <table class="receive_table">
                    <?php if ($receive['principal_credit']['cred_concepto'] != 'Reserva' && $receive['exist_rent_credit']) { ?>
                        <tr>
                            <td colspan="5">Ref.: contrato de <?php echo $contract['con_tipo'] ?> del inmueble <?php echo $contract['con_domi'] ?></td>
                            <td colspan="2" class="alignRight"><?php echo Date('d-m-Y') ?></td>
                        </tr>

                        <tr>
                            <td colspan="7">
                            Recibimos de <?php echo $contract['con_inq'] ?>, por cuenta y orden del/os <?php echo $contract['con_tipo'] == 'Loteo' ? 'vendedor/es' : 'locador/es' ?> la cantidad de
                                $ <?php echo $receive['total'] . ' ' . $receive['total_letters'] ?> en concepto de pago de <?php echo $receive['principal_credit']['cred_concepto'] ?> y Gastos
                                correspondiente al mes de <?php echo $receive['principal_credit']['cred_mes_alq'] ?>.

                                <?php if ($receive['principal_credit']['cred_concepto'] != 'Honorarios') { ?>
                                    La cobranza de los importes recibidos en concepto de <?php echo $contract['con_tipo'] ?> se realiza al solo
                                    efecto de ser entregada al/los <?php echo $contract['con_tipo'] == 'Loteo' ? 'vendedor/es' : 'locador/es' ?> del inmueble y en un todo de acuerdo al articulo 19 de R.G N&#176; 3803/94
                                <?php } ?>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="4"><?php echo $contract['con_tipo'] == 'Loteo' ? 'Vendedor/es' : 'Locador/es' ?>: <?php echo $propietary['client_name'] ?></td>
                            <td colspan="2">CUIT: <?php echo $propietary['client_cuit'] ?></td>
                            <td colspan="1">Fin de Contrato: <?php echo $contract['con_venc'] ?></td>
                        </tr>
                   
                    <?php } else if (!$receive['exist_rent_credit']) { ?>
                        <tr>
                            <td colspan="4">Ref.: Inmueble <?php echo $receive['principal_credit']['cred_domicilio'] ?> </td>
                            <td colspan="2" class="alignRight"><?php echo Date('d-m-Y') ?></td>
                        </tr>

                        <tr>
                            <td colspan="7">
                                Recibimos de <?php echo $receive['principal_credit']['cred_depositante'] ?>, la cantidad de
                                $ <?php echo $receive['total'] . ' ' . $receive['total_letters'] ?> en conceptos detallados a continuación.
                            </td>
                        </tr>
                        
                        <tr>
                            <td colspan="4"><?php echo $contract['con_tipo'] == 'Loteo' ? 'Vendedor/es' : 'Locador/es' ?>: <?php echo $propietary['client_name'] ?></td>
                            <td colspan="2">CUIT: <?php echo $propietary['client_cuit'] ?></td>
                            <td colspan="1">Fin de Contrato: <?php echo $contract['con_venc'] ?></td>
                        </tr>
                    <?php } else { ?>
                        <tr>
                            <td colspan="4">Ref.: Inmueble <?php echo $receive['principal_credit']['cred_domicilio'] ?> </td>
                            <td colspan="3" class="alignRight"><?php echo Date('d-m-Y') ?></td>
                        </tr>

                        <tr>
                            <td colspan="7">
                                <?php echo $receive['principal_credit']['cred_depositante'] ?> 
                                mencionado/s mas abajo entregan la cantidad de <?php echo '$ ' . $receive['total'] . ' ' . $receive['total_letters']
                                ?> en concepto de pago de <?php echo $receive['principal_credit']['cred_concepto'] ?> correspondiente alquiler mes 
                                de <?php echo $receive['principal_credit']['cred_mes_alq'] ?>. 
                                La cobranza de los importes recibidos en concepto de <?php echo $receive['principal_credit']['cred_concepto'] ?> se realiza al solo
                                efecto de ser entregada al propietario del inmueble y en un todo de acuerdo al articulo 19 de R.G N° 3803/94
                                La presente ahora tendra una validez de cinco (5) dias habiles a partir de la fecha.-
                            </td>
                        </tr>

                        <tr>
                            <td class="receive_cel">Locador/es: <?php echo $propietary['client_name'] ?></td>
                            <td class="receive_cel">CUIT: <?php echo $propietary['client_cuit'] ?></td>
                        </tr>
                    <?php } ?>

                    <tr>
                        <td colspan="7"></td>
                    </tr>

                    <tr>
                        <th class="receive_cel"></th>
                        <th class="receive_cel" colspan="2">Monto</th>
                        <th class="receive_cel" colspan="2">Punitorios</th>
                        <th class="receive_cel" colspan="2">IVA</th>
                    </tr>

                    <tr>
                        <th class="receive_cel">Concepto</th>    
                        <th class="receive_cel">Efectivo</th>    
                        <th class="receive_cel">Cheque</th>    
                        <th class="receive_cel">Efectivo</th>    
                        <th class="receive_cel">Cheque</th>    
                        <th class="receive_cel">Efectivo</th>    
                        <th class="receive_cel">Cheque</th>    
                    </tr>

                    <tr>
                        <?php
                        if ($receive['principal_credit']['cred_forma'] == 'Efectivo') {
                            $form = 'Efectivo';
                        } else {
                            $form = 'Cheque';
                        }

                        if ($receive['principal_credit']['cred_tipo_trans'] == 'Bancaria') {
                            $deposit = ' (Deposito Banco)';
                        } else {
                            $deposit = '';
                        }
                        ?>
                        <td class="receive_cel"><?php echo $receive['principal_credit']['cred_concepto'] . ' ' . $receive['principal_credit']['cred_mes_alq']; ?></td>

                        <td class="receive_cel"><?php echo $form == 'Efectivo' ? '$ ' . $receive['principal_credit']['cred_monto'] . $deposit : ''; ?></td>
                        <td class="receive_cel"><?php echo $form == 'Cheque' ? '$ ' . $receive['principal_credit']['cred_monto'] . ' N°' . $receive['principal_credit']['cred_nro_cheque'] . $deposit : ''; ?></td>

                        <td class="receive_cel"><?php echo $form == 'Efectivo' && $receive['principal_credit']['cred_interes_calculado'] ? '$ ' . $receive['principal_credit']['cred_interes_calculado'] . $deposit : ''; ?></td>
                        <td class="receive_cel"><?php echo $form == 'Cheque' && $receive['principal_credit']['cred_interes_calculado'] ? '$ ' . $receive['principal_credit']['cred_interes_calculado'] . ' N°' . $receive['principal_credit']['cred_nro_cheque'] . $deposit : ''; ?></td>

                        <td class="receive_cel"><?php echo $form == 'Efectivo' && $receive['principal_credit']['cred_iva_calculado'] ? '$ ' . $receive['principal_credit']['cred_iva_calculado'] . $deposit : ''; ?></td>
                        <td class="receive_cel"><?php echo $form == 'Cheque' && $receive['principal_credit']['cred_iva_calculado'] ? '$ ' . $receive['principal_credit']['cred_iva_calculado'] . ' N°' . $receive['principal_credit']['cred_nro_cheque'] . $deposit : ''; ?></td>
                    </tr>

                    <?php if (!empty($receive['principal_credit']['other_principal'])) { ?>
                        <?php foreach ($receive['principal_credit']['other_principal'] as $other_principal) { ?>
                            <?php
                            if ($other_principal['cred_forma'] == 'Efectivo') {
                                $form = 'Efectivo';
                            } else {
                                $form = 'Cheque';
                            }

                            if ($other_principal['cred_tipo_trans'] == 'Bancaria') {
                                $deposit = ' (Deposito Banco)';
                            } else {
                                $deposit = '';
                            }
                            ?>        
                            <tr>
                                <td class="receive_cel"><?php echo $other_principal['cred_concepto'] . ' ' . $other_principal['cred_mes_alq']; ?></td>

                                <td class="receive_cel"><?php echo $form == 'Efectivo' ? '$ ' . $other_principal['cred_monto'] . $deposit : ''; ?></td>
                                <td class="receive_cel"><?php echo $form == 'Cheque' ? '$ ' . $other_principal['cred_monto'] . ' N°' . $other_principal['cred_nro_cheque'] . $deposit : ''; ?></td>

                                <td class="receive_cel"><?php echo $form == 'Efectivo' && $other_principal['cred_interes_calculado'] ? '$ ' . $other_principal['cred_interes_calculado'] . $deposit : ''; ?></td>
                                <td class="receive_cel"><?php echo $form == 'Cheque' && $other_principal['cred_interes_calculado'] ? '$ ' . $other_principal['cred_interes_calculado'] . ' N°' . $other_principal['cred_nro_cheque'] . $deposit : ''; ?></td>

                                <td class="receive_cel"><?php echo $form == 'Efectivo' && $other_principal['cred_iva_calculado'] ? '$ ' . $other_principal['cred_iva_calculado'] . $deposit : ''; ?></td>
                                <td class="receive_cel"><?php echo $form == 'Cheque' && $other_principal['cred_iva_calculado'] ? '$ ' . $other_principal['cred_iva_calculado'] . ' N°' . $other_principal['cred_nro_cheque'] . $deposit : ''; ?></td>
                            </tr>
                        <?php } ?>
                    <?php } ?>

                    <?php if (!empty($receive['secondary_credits'])) { ?>
                        <?php foreach ($receive['secondary_credits'] as $secondary) { ?>
                            <?php
                            if ($secondary['cred_forma'] == 'Efectivo') {
                                $form = 'Efectivo';
                            } else {
                                $form = 'Cheque';
                            }

                            if ($secondary['cred_tipo_trans'] == 'Bancaria') {
                                $deposit = ' (Deposito Banco)';
                            } else {
                                $deposit = '';
                            }
                            ?>
                            <tr>
                                <td class="receive_cel"><?php echo $secondary['cred_concepto'] . ' ' . $secondary['cred_mes_alq'] ?></td>

                                <td class="receive_cel"><?php echo $secondary['cred_forma'] == 'Efectivo' ? '$ ' . $secondary['cred_monto'] . $deposit : ''; ?></td>
                                <td class="receive_cel"><?php echo $secondary['cred_forma'] == 'Cheque' ? '$ ' . $secondary['cred_monto'] . $deposit : ''; ?></td>

                                <td class="receive_cel"><?php echo $form == 'Efectivo' && $secondary['cred_interes_calculado'] ? '$ ' . $secondary['cred_interes_calculado'] . $deposit : ''; ?></td>
                                <td class="receive_cel"><?php echo $form == 'Cheque' && $secondary['cred_interes_calculado'] ? '$ ' . $secondary['cred_interes_calculado'] . $deposit : ''; ?></td>

                                <td class="receive_cel"></td>
                                <td class="receive_cel"></td>
                            </tr>                        
                        <?php } ?>
                    <?php } ?>

                    <?php if (!empty($receive['services_control'])) { ?>
                        <?php $presented_bills = ''; ?>
                        <tr>
                            <?php
                            foreach ($receive['services_control'] as $key => $service_control) {
                                if (count($receive['services_control']) - 1 == $key || count($receive['services_control']) == 1) {
                                    // ultimo o unico elemento
                                    $presented_bills .= $service_control['service'] . ' ' . $service_control['month_checked'];
                                } else {
                                    $presented_bills .= $service_control['service'] . ' ' . $service_control['month_checked'] . ', ';
                                }
                            }
                            ?>
                            <th class="receive_cel">Boletas presentadas</th>
                            <td colspan="6" class="receive_cel"><?php echo $presented_bills; ?></td>
                        </tr>  
                    <?php } ?>
                    <?php if (!empty($receive['services_no_control'])) { ?>
                        <?php $pending_bills = ''; ?>
                        <tr>
                            <?php
                            foreach ($receive['services_no_control'] as $key => $service_control) {
                                if (count($receive['services_no_control']) - 1 == $key || count($receive['services_no_control']) == 1) {
                                    // ultimo o unico elemento
                                    $pending_bills .= $service_control['service'] . ' ' . $service_control['month_checked'];
                                } else {
                                    $pending_bills .= $service_control['service'] . ' ' . $service_control['month_checked'] . ', ';
                                }
                            }
                            ?>
                            <th class="receive_cel">Boletas pendientes</th>
                            <td colspan="6" class="receive_cel"><?php echo $pending_bills; ?></td>
                        </tr>  
                    <?php } ?>

                    <?php if ($receive['debt'] > 0) { ?>
                        <tr>
                            <th class="receive_cel">Adeuda</th>
                            <td class="receive_cel"><?php echo '$ ' . $receive['debt']; ?></td>
                        </tr>                       
                    <?php } ?>
                    
                    <tr>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                    </tr>
                    
                    <tr>
                        <td colspan="7" rowspan="5" class="alignRight total_receive">$ <?php echo $receive['total'] ?></td>
                    </tr>

                </table>
            </div>
        <?php } ?>
       <?php if($i == 0){ ?>
            </div>
       <?php } ?>
    <?php } ?>
</div>

</div>

<div id="non-printable" class="report_actions">
    <a class="btn btn-primary button_report" href="javascript:;" onclick="window.print();return false;">Imprimir</a>
    <a class="btn btn-primary button_report" href="<?php echo site_url('credits') ?>">Volver</a>
</div>

<script type="text/javascript">
    $(function(){
        // solo estara TRUE al momento de la creacion del credito
        var mail_receive = <?php echo isset($mail_receive) ? json_decode($mail_receive) : 0; ?>;

        if(mail_receive && email_receive_renter){
            $('.original').css('background', 'white');

            var div_content = document.querySelectorAll(".original");

            html2canvas(div_content).then(function(canvas) {
                //change the canvas to jpeg image
                var receive_image = canvas.toDataURL('image/jpeg');
                
                $.post(email_receive_renter_url, {data: receive_image});
            });
        }
    });
</script>
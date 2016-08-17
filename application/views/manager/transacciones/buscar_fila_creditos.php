<style>
    #ccnav a:hover{
        color:black;
    }
</style>    
<h2><?= t('Creditos') ?></h2>
<ul id="ccnav" class="nav nav-tabs">
    <li><a href="#agregar" data-toggle="tab">Nuevo Credito</a></li>
    <li class="active"><a href="#lista" data-toggle="tab">Lista de Creditos</a></li>
</ul>
<div class="tab-content">
    <div class="tab-pane fade" id="agregar">

        <form id="form_cred" class="<?= isset($row) ? 'edit_form' : 'add_form' ?>" action="javascript:;" onsubmit="saveCredits('<?= site_url('manager/save_creditos') ?>','.contenedor_centro');" enctype="multipart/form-data"> 
            <div class="bloque">
                <input id="auto_cc_id" name="auto_cc_id" type="hidden"/>
                <input id="auto_depo_id" name="auto_depo_id" type="hidden"/>

                <input onkeyup="validar_cliente('depositante')" onblur="validar_cliente('depositante')" type="text" id="depositante" name="depositante" style="margin-right: 5px;font-size: 16px;width: 403px;float: left;" class="form-control ui-autocomplete-input" placeholder="Depositante/Inquilino">
                <input onkeyup="validar_cc('cuentas')" onblur="validar_cc('cuentas')" type="text" id="cuentas" name="cuenta_corriente" style="margin-right: 5px;font-size: 16px;width: 403px;float: left;" class="form-control ui-autocomplete-input" placeholder="Cta. Cte./Propietario">                

                <div class="medio_pago">
                    Medio de pago
                    <select onchange="toggle_element($('select option:selected').val())" class="form-control" id="cred_forma" name="cred_forma">
                        <option value="Efectivo">Efectivo</option>
                        <option value="Cheque">Cheque</option>
                    </select>

                </div>
                <div class="tipo_transac">
                    Tipo Transac.
                    <select class="form-control" id="cred_tipo_trans" name="cred_tipo_trans">
                        <option value="Caja">Caja</option>
                        <option value="Bancaria">Bancaria</option>
                    </select>
                </div>
            </div>

            <div class="info_contrato _info_contrato"></div>

            <div class="alert_message alert alert-danger _alert_message"></div>

            <div class="bloque _first_bloque _bloque">

                <input type="text" name="credito[concepto][]" style="margin-right: 5px;font-size: 16px;width: 17%;float: left;" class="form-control ui-autocomplete-input _buscar_concepto" placeholder="Buscar Concepto">

                <span title="Presiona para agregar un concepto nuevo" style="padding:8;font-size: 13px;" onclick="request('<?= site_url('load_concept') ?>',$(this).serialize(),'#popup');popup()" type="button" class="btn btn-default btn-lg">
                    <a style="text-decoration: none;"class="glyphicon glyphicon-plus-sign"></a> Nuevo concepto
                </span>

            </div>

            <div class="_dinamic_credits"></div>

            <div id="montos" style="display: none;width: 100%;float: left">
                <label type="text" style="margin-top: 0px;text-align: center;background: none;margin-right: 5px;font-size: 16px;width: 402px;float: left;" class="form-control ui-autocomplete-input"> SUBTOTALES </label>
                <input title="Suma de todos los montos netos" type="text" id="monto_total" style="margin-right: 5px;font-size: 16px;width: 160px;float: left;" class="form-control ui-autocomplete-input" readonly="enabled">
                <label type="text" style="clear: both;margin-top: 0px;text-align: center;background: none;margin-right: 5px;font-size: 16px;width: 402px;float: left;" class="form-control ui-autocomplete-input"> INTERESES </label>                
                <input title="Suma de todos los intereses netos" type="text" id="total_intereses" style="margin-right: 5px;font-size: 16px;width: 160px;float: left;" class="form-control ui-autocomplete-input" readonly="enabled">
                <label type="text" style="clear: both;margin-top: 0px;text-align: center;background: none;margin-right: 5px;font-size: 16px;width: 402px;float: left;" class="form-control ui-autocomplete-input"> IVA </label>                
                <input title="Suma de todo el IVA neto" type="text" id="total_iva" style="margin-right: 5px;font-size: 16px;width: 160px;float: left;" class="form-control ui-autocomplete-input" readonly="enabled">
                <label type="text" style="clear: both;margin-top: 0px;text-align: center;background: none;margin-right: 5px;font-size: 16px;width: 402px;float: left;" class="form-control ui-autocomplete-input"> TOTAL </label>                
                <input type="text" id="total_todo" style="margin-right: 5px;font-size: 16px;width: 160px;float: left;" class="form-control ui-autocomplete-input" readonly="enabled">                
            </div>

            <button onclick=""class="btn btn-primary" type="submit" id="buttons_cli" style="margin-bottom: 50px;margin-top: 13px;float: left; line-height: 0;"><?= isset($row) ? 'Guardar' : 'Crear' ?></button>

        </form>

        <div id="com_display">
            <span></span>
        </div>
    </div>
    <div class="tab-pane fade in active" id="lista"> 
        <div class="actions_container">
            <div class="field span3 buscar" id="mio">
                <div class="alert alert-info">Filtre por Cta Cte. rangos de fecha o la combinacion de ambas</div>
                <input name="cliente" style="width: 255px;height: 26px;" placeholder="Cta. Cte." type="text" value="" id="auto_personas" class="ui-autocomplete-input">
                <input type="hidden" id="cliente"/>
                <input name="desde" placeholder="Desde" type="text" id="desde">
                <input name="hasta" placeholder="Hasta" type="text" id="hasta">    
                <input placeholder="Concepto" type="text" id="auto_concepto" style="width: 205px;height: 26px;margin-right: 12px"name="concepto" > 
                <input type="hidden" id="concepto"/>
                <button id="asd" class="btn btn-primary">Filtrar</button>
                <a id="refresh" href="javascript:;" class="glyphicon glyphicon-refresh" onclick="refrescar()"></a>
            </div>
        </div>
        <table class="table table-hover">
            <tr>    
                <th>Depositante o Impositor</th>
                <th>Cta. Cte.</th>
                <th>Concepto</th>
                <th>Monto</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
            <?
            if ($creditos->num_rows() > 0) {
                foreach ($creditos->result() as $credito) {
                    echo '<tr class="reg_' . $credito->cred_id . '">';
                    echo '<td>' . $credito->cred_depositante . '</td>';
                    echo '<td>' . $credito->cred_cc . '</td>';
                    echo '<td>' . $credito->cred_concepto . ' (' . $credito->cred_mes_alq . ')' . '</td>';
                    echo '<td>$ ' . $credito->cred_monto . '</td>';
                    echo '<td>' . $credito->cred_fecha . '</td>';
                    echo '<td>';
                    echo '<a href="javascript:;" class="glyphicon glyphicon-print" onclick="request_post(\'' . site_url('manager/imprimir_recibo/' . $credito->trans) . '\')"></a> | ';
                    echo '<a href="javascript:;" id="' . $credito->trans . '" class="glyphicon glyphicon-trash" onclick="del_transact(this)"></a>  ';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="100%">' . t('No records found.') . '</td></tr>';
            }
            ?>
        </table>



    </div>
</div>
<div id="back_fader">
    <div id="popup">
    </div>
</div>

<script>  
   
    function del_transact(a){
        var id = $(a).attr('id');
        request('<?= site_url() . 'del_transact/creditos/' ?>'+id,'','#popup1');
        popup1();
    }
    function refrescar(){
        request('<?= site_url() . 'refresh/' ?>creditos/cred_id','','.contenedor_centro');
    }
    $("#asd").click(function() {      
        var desde = $('#desde').val();
        var hasta = $('#hasta').val();
        var cc = $('#cliente').val();
        var concepto = $('#concepto').val();
        var cc = cc.replace(',','.');
        var exe = 0;
        if(cc == '' && hasta != '' && desde != '' && concepto == ''){
            request('<?= site_url() . 'filtrar_pago/' ?>/'+desde+'/'+hasta,'','.contenedor_centro');
            exe = 1;
        }
        if(cc != '' && hasta == '' && desde == '' && concepto == ''){
            request('<?= site_url() . 'filtrar_pago2_aca/' ?>'+cc,'','.contenedor_centro');
            exe = 1;
        }
        if(cc != '' && concepto == ''){
            if(hasta != '' && desde != '' && concepto == ''){
                request('<?= site_url() . 'filtrar_pago3/' ?>'+cc+'/'+desde+'/'+hasta,'','.contenedor_centro');
                exe = 1;
            }
        }  
        if(exe == 0){
            if(concepto != ''){
                if(hasta != '' && desde != '' && cc != ''){
                    request('<?= site_url() . 'filtrar_cred_4/' ?>'+cc+'/'+desde+'/'+hasta+'/'+concepto,'','.contenedor_centro');   
                }else if(hasta == '' && desde == '' && cc != ''){
                    request('<?= site_url() . 'filtrar_cred_2/' ?>'+cc+'/'+concepto,'','.contenedor_centro');    
                }else if(hasta != '' && desde != '' && cc == ''){
                    request('<?= site_url() . 'filtrar_cred_3/' ?>'+desde+'/'+hasta+'/'+concepto,'','.contenedor_centro');    
                }else{
                    request('<?= site_url() . 'filtrar_cred_1/' ?>'+concepto,'','.contenedor_centro');        
                } 
            }
        }
    });
    $( "#desde" ).datepicker({
        monthNames: [ "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre" ],
        monthNamesShort: [ "Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic" ],
        dateFormat: "dd-mm-yy",
        gotoCurrent: true,
        changeMonth: true,
        changeYear: true,
        yearRange: '1940:2050',
        duration: "slow",
        maxDate: "+15y",
        minDate: new Date(1940, 1 - 1, 1)});
    $( "#hasta" ).datepicker({
        monthNames: [ "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre" ],
        monthNamesShort: [ "Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic" ],
        dateFormat: "dd-mm-yy",
        gotoCurrent: true,
        changeMonth: true,
        changeYear: true,
        yearRange: '1940:2050',
        duration: "slow",
        maxDate: "+15y",
        minDate: new Date(1940, 1 - 1, 1)});
   
    
    $("[id^=auto_personas]").each(function(){
        /* Por cada input #auto_player, ya sea auto_player1, 
         * auto_player2, etc, existe este script universal 
         * que maneja todos y cada uno de los autocomplete
         * de dichos inputs
         * ES UN AUTOCOMPLETE DE MULTIPLES INPUTS */
        
        /* El operador ^ hace las veces de comodin
         * señala que lo que esta a la derecha sea una
         * subcadena del input, para tenerlo en cuenta 
         * en el autocomplete*/
        $(this).autocomplete({
            source: "<?php echo site_url('manager/autocomplete') . '/cuentas_corrientes' ?>",
            select: function(event, ui) {
                $.ajax({
                    url : BASE_URL + "manager/buscar_cc/cuentas_corrientes/"+ui.item.id,
                    type:'POST',
                    dataType: 'json',
                    success:function(R){
                        eval(R.js);
                        if(R.html != ''){    
                            $(this).val(R.html);
                            $('#cliente').val(R.html);
                        }
                    }
                });
            }
        });
        $(this).keydown(function(){
            if ($(this).val().length == 1){
                $.ajax({
                    url : BASE_URL + "manager/buscar_cc/cuentas_corrientes/",
                    type:'POST',
                    dataType: 'json',
                    success:function(R){
                        eval(R.js);
                        if(R.html != ''){                          
                            $(this).val(R.html);
                            $('#cliente').val(R.html);
                        }
                    }
                });
            }
        });
        
    });
      
    $("[id^=auto_concepto]").each(function(){
        /* Por cada input #auto_player, ya sea auto_player1, 
         * auto_player2, etc, existe este script universal 
         * que maneja todos y cada uno de los autocomplete
         * de dichos inputs
         * ES UN AUTOCOMPLETE DE MULTIPLES INPUTS */
        
        /* El operador ^ hace las veces de comodin
         * señala que lo que esta a la derecha sea una
         * subcadena del input, para tenerlo en cuenta 
         * en el autocomplete*/
        $(this).autocomplete({
            source: "<?php echo site_url('manager/autocomplete') . '/conceptos/Entrada' ?>",
            select: function(event, ui) {
                $.ajax({
                    url : BASE_URL + "manager/buscar_concepto_c/conceptos/Entrada/"+ui.item.id,
                    type:'POST',
                    dataType: 'json',
                    success:function(R){
                        eval(R.js);
                        if(R.html != ''){    
                            $(this).val(R.html);
                            $('#concepto').val(R.html);
                        }
                    }
                });
            }
        });
        $(this).keydown(function(){
            if ($(this).val().length == 1){
                $.ajax({
                    url : BASE_URL + "manager/buscar_concepto_c/conceptos/Entrada/",
                    type:'POST',
                    dataType: 'json',
                    success:function(R){
                        eval(R.js);
                        if(R.html != ''){                          
                            $(this).val(R.html);
                            $('#concepto').val(R.html);
                        }
                    }
                });
            }
        });
        
    });
</script>

<script>
  
    var date_opt = {
        monthNames: [ "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre" ],
        monthNamesShort: [ "Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic" ],
        dateFormat: "dd-mm-yy",
        gotoCurrent: true,
        changeMonth: true,
        changeYear: true,
        yearRange: '1940:2050',
        duration: "slow",
        maxDate: "+15y",
        minDate: new Date(1940, 1 - 1, 1)
    }    
    
        
    function unlock($mora){
        $dias_mora = $mora;
        if($mora.attr('readonly') == 'readonly'){
            $('#unlockerMora').modal('show');
        }
    }
    
    function authUnlock (url,form,show){
        get_texts_ckeditor(form);
        $.ajax({
            url:url,
            type:'POST',
            data: '&'+$(form).serialize(),
            dataType: 'json',
            beforeSend:function(){
                loading.show();
            },
            success:function(R){
                if(R.js != '') eval(R.js);
                if(R.error==1){
                    $('#com_display1').html(R.html).addClass('alert alert-danger');
                }else{
                    if (R.error==5){   
                        $('#com_display1 span').html(R.mensaje_error);
                    }else{
                        $(show).html(R.html);    
                        $dias_mora.removeAttr('readonly');
                        $dias_mora.css('cursor', 'inherit');
                        $('#codigo').val('');
                        $('#unlockerMora').modal('hide');
                        $dias_mora = null;
                        $('#com_display1').html(R.success);
                        $('#com_display1').addClass('alert alert-success').removeClass('alert alert-danger');   
                    }
                
                }  
                loading.hide();
            }
        });
        return false;
    }
    
    function recalcular(){
        var monto = 0;
        var total_todo = 0;
        var total_intereses = 0;
        var total_iva = 0;
        
        //recalculo montos
        $('._monto').each(function () {
            monto += $(this).val() * 1;
        });
        
        // recalculo interes si hay
        $('._interes_calculado').each(function () {
            var $bloque = $(this).parents('._bloque');
            var $monto = $bloque.find('._monto');
            var $dias_mora = $bloque.find('._dias_mora');
            
            var mora = $dias_mora.val();
            mora = mora.replace(/\D/g,'');
            var interes = $monto.val() * mora * $('#con_punitorio').val();
            var original=parseFloat(interes);
            interes = Math.round(original*100)/100;
            
            $(this).val(interes);
            if($monto.val() == ''){
                $(this).val('');
            }
            total_intereses += interes;
        });
        
        //recalculo iva
        $('._iva_calculado').each(function () {
            var $bloque = $(this).parents('._bloque');
            var $monto = $bloque.find('._monto');
            
            var iva = $monto.val() * 0.21;
            var original = parseFloat(iva);
            iva = Math.round(original*100)/100;
            
            $(this).val(iva);
            if($monto.val() == ''){
                $(this).val('');
            }
            
            total_iva += iva;
        });
        
        var original_iva = parseFloat(total_iva);
        var result_iva = Math.round(original_iva*100)/100 ;
        $('#total_iva').val(result_iva);   
        
        var original_intereses = parseFloat(total_intereses);
        var result_interes = Math.round(original_intereses*100)/100 ;
        $('#total_intereses').val(result_interes);  
        
        var original_monto = parseFloat(monto);
        var result_monto = Math.round(original_monto*100)/100 ;
        $('#monto_total').val(result_monto);
        
        total_todo = monto + total_intereses + total_iva;
        var original_todo = parseFloat(total_todo);
        var result_total_todo = Math.round(original_todo*100)/100 ;
        $('#total_todo').val(result_total_todo);
    }
    
    function agregar(domicilio, concepto, monto, dias_mora, intereses, mes_ano, saldo_cuenta, iva_alquiler, con_tipo, iva_comision){   
        if(!domicilio){
            domicilio = '';
        }
        if(!concepto){
            concepto = '';
        }
        if(!monto){
            monto = '';
        }
        if(!dias_mora){
            dias_mora = 0;
        }
        if(!intereses){
            intereses = 0;
        }
        if(!mes_ano){
            mes_ano = '';
        }
        //Creo el Bloque
        var $bloque = $('<div/>', {
            'class' : 'bloque _bloque'
        });
        $('._dinamic_credits').append($bloque);
        
        //Creo los inputs Concepto, Monto y Mes Alquiler
        $('<input/>', {
            name: 'credito[concepto][]',
            type: 'text',
            value: concepto,
            readonly: true,
            style : 'margin-right: 5px;font-size: 16px;width: 17%;float: left;',
            'class': 'form-control ui-autocomplete-input _concepto',
            placeholder: 'Concepto'
        }).appendTo($bloque);
        
        $('<input/>', {
            name: 'credito[monto][]',
            value: monto,
            onkeyup:'recalcular()',
            onblur:'recalcular()',
            type: 'text',
            title:'Monto de '+concepto,
            autocomplete: 'off',
            style : 'margin-right: 5px;font-size: 16px;width: 8%;float: left;',
            'class': 'form-control ui-autocomplete-input _monto',
            placeholder: 'Monto'                 
        }).appendTo($bloque);
        if(concepto == 'Alquiler' || concepto == 'Alquiler Comercial' || concepto == 'Comision'){          
            var $div_select_container = $('<div class="forma_pago_select">');
        
            var $select_forma_pago = $('<select class="form-control _select_tipo_pago" name="credito[forma_pago][]">');
        
            var $select_option_total = $('<option value="Total">Total</option>');
            var $select_option_cuenta = $('<option value="A Cuenta">A Cuenta</option>');
            var $select_option_saldo = $('<option value="Saldo">Saldo</option>');
        
            $select_forma_pago.append($select_option_total);
            $select_forma_pago.append($select_option_cuenta);
            $select_forma_pago.append($select_option_saldo);
        
            $div_select_container.append($select_forma_pago);
            $div_select_container.appendTo($bloque);
            if(saldo_cuenta){
                $select_forma_pago.val('Saldo');
            }else{
                $select_forma_pago.val('Total');
            }
        }
        
        var $mes = $('<input/>', {
            name: 'credito[mes][]',
            type: 'text',
            value: mes_ano,
            style : 'margin-right: 5px;font-size: 16px;width: 10%;float: left;',
            'class': 'form-control ui-autocomplete-input _mes',
            placeholder: 'Mes'                 
        }).appendTo($bloque);
        $mes.autocomplete({
            source: meses
        }); 
        
        var $domicilio = $('<input/>', {
            name: 'credito[domicilio][]',
            value: domicilio,
            title: 'Inmueble al que este relacionado este crédito o comentario que se desee agregar',
            type: 'text',
            style : 'margin-right: 5px;font-size: 16px;width: 16%;float: left;',
            'class': 'form-control ui-autocomplete-input _domicilio',
            placeholder: 'Domicilio Inmueble'                 
        }).appendTo($bloque);
        $domicilio.autocomplete(auto_opt_dom);
        
        var iva = iva_comision == "Si" ? true : false;
        if(concepto == 'Comision' && iva){
            $('<input/>', { 
                name: 'credito[iva_calculado][]',
                type: 'text',
                autocomplete: 'off',
                value: '',
                readonly:true,
                title: 'Iva calculado sobre el monto de Comision',
                style : 'margin-right: 5px;font-size: 16px;width: 8%;float: left;',
                'class': 'form-control ui-autocomplete-input _iva_calculado',
                placeholder: 'IVA/Comision'                 
            }).appendTo($bloque);
        }
        
        if(iva_alquiler && con_tipo == concepto){
            $("<input/>", {
                name: "credito[iva_calculado][]",
                type: "text",
                readonly: true,
                autocomplete: "off",
                value: monto * 0.21,     
                title: 'Iva calculado sobre el monto de '+concepto,
                style : "margin-right: 5px;font-size: 16px;width: 7%;float: left;",
                "class": "form-control ui-autocomplete-input _iva_calculado",
                placeholder: "IVA/Alquiler"                 
            }).appendTo($bloque);
        }
        
        if(dias_mora != 0){
            $("<input/>", {
                value:dias_mora + ' dias de mora',
                readonly: true,
                onkeyup:"recalcular()",
                onblur:"recalcular()",
                onclick:"unlock($(this))",
                name: "credito[dias_mora][]",
                type: "text",
                title:'Para modificar los dias de mora haz click aqui',
                autocomplete: "off",
                style : "cursor:pointer;margin-right: 5px;font-size: 16px;width: 12%;float: left;",
                "class": "form-control ui-autocomplete-input _dias_mora",
                placeholder: "Dias de Interes"                 
            }).appendTo($bloque);
            
            $("<input/>", {
                name: "credito[interes_calculado][]",
                type: "text",
                autocomplete: "off",
                title:'Interes calculado sobre el monto de '+concepto+' segun los dias de mora y el % punitorio',
                readonly: true,
                value: intereses,  
                style : "margin-right: 5px;font-size: 16px;width: 6%;float: left;",
                "class": "form-control ui-autocomplete-input _interes_calculado",
                placeholder: "Interes"                 
            }).appendTo($bloque);
            
            var $cont_pay_int = $('<div class="porc_data">');
            var $select_pay_int = $('<select title="Si: el inquilino pagara intereses devengados, No: el inquilino tiene intereses devengados pero No pagara hoy" class="form-control _paga_intereses" name="credito[paga_intereses][]">');
            var $select_paga = $('<option value="Si">Si</option>');        
            var $select_nopaga = $('<option value="No">No</option>');        
            $select_pay_int.append($select_paga).append($select_nopaga);
            $cont_pay_int.append($select_pay_int);
            $cont_pay_int.appendTo($bloque);
        }
        
        var $span = $('<span/>', {
            onclick: 'removeElement(this)',
            style: 'height: 34px;',
            'class' : 'btn btn-default btn-lg _remove'
        }).appendTo($bloque);
        $('<a/>', {
            'class' : 'glyphicon glyphicon-minus-sign',
            style : 'text-decoration: none; margin-top: -3px;'
        }).appendTo($span);
            
    }
    
    function strpos (haystack, needle, offset) {
        var i = (haystack + '')
        .indexOf(needle, (offset || 0));
        return i === -1 ? false : i;
    }

    function validar_cc(id_input){       
        setTimeout(function(){
            var value = $('#'+id_input).val();
            request('<?= site_url('validate') ?>/cc_id/cc_prop/cuentas_corrientes/auto_cc_id/'+id_input+'/'+value,'','#auto_cc_id')          
        },200)
    }
    
    function validar_cliente(id_input){
        setTimeout(function(){
            var value = $('#'+id_input).val();
            request('<?= site_url('validate') ?>/client_id/client_name/clientes/auto_depo_id/'+id_input+'/'+value,'','#auto_depo_id')          
        },200)
    }
    
    function removeElement(that) {
        var $this_remove_span = $(that);
        $this_remove_span.parents("._bloque").remove();
        recalcular();
    }
    
    //Variable que almacena las Options del autocomplete. De manera que pueda llamarlo luego de crear el input
    //Para cada input creado dinamicamente
    var auto_opt_dom = {
        source: "<?php echo site_url('manager/autocomplete') . '/propiedades/' ?>",
        select: function(event, ui) {
            $.ajax({
                url : BASE_URL + "manager/buscar_propiedad_prop/propiedades/"+ui.item.id,
                type:'POST',
                dataType: 'json',
                success:function(R){
                    eval(R.js);
                    if(R.html != ''){
                        $(this).val(R.html);
                    }
                }
            });
        } 
    };
    var key_opt_dom = function(){
        if ($(this).val().length == 1){
            $.ajax({
                url : BASE_URL + "manager/buscar_propiedad_prop/propiedades/",
                type:'POST',
                dataType: 'json',
                success:function(R){
                    eval(R.js);
                    if(R.html != ''){
                        $(this).html(R.html);
                    }
                }
            });
        }
    }

    $("._buscar_concepto").autocomplete({
        source: "<?php echo site_url('manager/autocomplete') . '/conceptos/Entrada' ?>",
        select: function(event, ui) {
            $.ajax({
                url : BASE_URL + "manager/buscar_concepto/conceptos/Entrada/"+$('#depositante').val()+'/'+$('#cuentas').val()+'/'+ui.item.id,
                type:'POST',
                dataType: 'json',
                beforeSend:function(){
                    loading.show();
                    if(!$('#depositante').val().length && !$('#cuentas').val().length){
                        $('._alert_message').html('Por favor, primero complete Depositante y Cuenta');
                        $('._alert_message').show();
                        loading.hide();
                    }else{
                        $('._alert_message').hide();
                    }
                },
                success:function(response){
                    if(response.status){
                        $('._alert_message').hide();
                            
                        // bloqueamos cuenta y depositante
                        $('#cuentas').attr('readonly', true);
                        $('#depositante').attr('readonly', true);
                            
                        // seteamos montos totales
                        $("#monto_total").val(response.data.monto);
                        $("#total_intereses").val(response.data.interes);
                        $("#total_iva").val(response.data.iva);
                        $("#total_todo").val(response.data.total);
                        $("#montos").show();
                                
                        // armo estructura deudas alquiler / loteo
                        var deudas_contrato = response.data.deudas_contrato;
                        var concepto = response.data.concepto;
                        if(deudas_contrato){
                            var contrato = response.data.contrato;
                            if(concepto == contrato['con_tipo']){
                                var iva_alquiler = contrato['con_iva_alq'] == "Si" ? true : false;
                            }
                            if(deudas_contrato.length){
                                for (var x = 0; x < deudas_contrato.length; x++){
                                    // chequeamos que una entrada no se repita en concepto y mes
                                    if(canAddRows(deudas_contrato[x])){
                                        agregar(contrato['con_domi'], deudas_contrato[x]['concepto'], deudas_contrato[x]['monto'], deudas_contrato[x]['dias_mora'], deudas_contrato[x]['intereses'], deudas_contrato[x]['mes'] + ' ' + deudas_contrato[x]['ano'], deudas_contrato[x]['saldo_cuenta'], iva_alquiler, contrato['con_tipo'], contrato['con_iva']);
                                    } 
                                }
                            }else{
                                // Es un contrato pero no tiene deudas, crear un campo con concepto
                                // unicamente por si desea pagar alquiler adelantado
                                agregar(contrato['con_domi'], concepto, false, false, false, false, false, iva_alquiler,  contrato['con_tipo'], contrato['con_iva']);
                            } 
                        }else{
                            // No hay deudas porque no existe contrato, concepto secundario
                            agregar(false, concepto);
                        }
                                    
                        if(response.exist_contrato){
                            // muestro informacion del contrato
                            $('._info_contrato').html(response.info_contrato);
                            $('._info_contrato').show();    
                        }
                        recalcular();
                        $('._buscar_concepto').val('');
                    }else{
                        if(response.message){
                            $('._alert_message').html(response.message);
                            $('._alert_message').show();
                        }
                    }
                    initTooltips();
                    loading.hide();
                }
            });
        }
    });
    
    
    function canAddRows(deuda){
        // chequea que una entrada no se repita en concepto y mes
        var can_add = true;
        $('._bloque').each(function(){
            var $concepto = $(this).find('._concepto');
            var $mes = $(this).find('._mes');
            var mes_ano = deuda.mes+' '+deuda.ano;
            if($concepto.val() == deuda.concepto && $mes.val() == mes_ano){
                can_add = false;
            }
        }); 
        return can_add;
    }
    
    $("._domicilio").each(function(){
        /* Por cada input #concepto, ya sea concepto1, 
         * concepto2, etc, existe este script universal 
         * que maneja todos y cada uno de los autocomplete
         * de dichos inputs
         * ES UN AUTOCOMPLETE DE MULTIPLES INPUTS */
        
        /* El operador ^ hace las veces de comodin
         * señala que lo que esta a la derecha sea una
         * subcadena del input, para tenerlo en cuenta 
         * en el autocomplete*/
      
        $(this).autocomplete(auto_opt_dom);
        $(this).keydown(key_opt_dom);
        
    });
    var meses = [
        'Enero ' + <?= Date('Y') ?>,
        'Febrero ' +  <?= Date('Y') ?>,
        'Marzo ' + <?= Date('Y') ?> ,
        'Abril ' + <?= Date('Y') ?> ,
        'Mayo ' + <?= Date('Y') ?>,
        'Junio ' + <?= Date('Y') ?>,
        'Julio ' + <?= Date('Y') ?>,
        'Agosto ' + <?= Date('Y') ?>,
        'Septiembre ' + <?= Date('Y') ?>,
        'Octubre ' + <?= Date('Y') ?>,
        'Noviembre ' + <?= Date('Y') ?>,             
        'Diciembre ' + <?= Date('Y') ?>,             
    ];
    
    $(function() {      
        $("._mes").autocomplete({
            source: meses
        });  
    });
    
    $(function(){
        $('#depositante').autocomplete({
            source: "<?php echo site_url('manager/autocomplete') . '/clientes' ?>",
            select: function(event, ui) {
                $.ajax({
                    url : BASE_URL + "manager/buscar_cliente/clientes/"+ui.item.id,
                    type:'POST',
                    dataType: 'json',
                    success:function(response){
                        eval(response.js);
                        if(response.html != ''){
                            if(response.prop == null){
                                if(response.periodos.length){
                                    $('._alert_message').html(response.periodos);
                                    $('._alert_message').show();  
                                }
                            }else{
                                $('#cuentas').val(response.prop);    
                                $('#cuentas').blur();    
                            }
                            $("._buscar_concepto").focus();
                            $('#depositante').html(response.html);
                            $('#depositante').blur();
                        }
                    }
                });
            }
        });
        $("#depositante").keydown(function(){
            if ($("#depositante").val().length == 1){
                $.ajax({
                    url : BASE_URL + "manager/buscar_cliente/clientes/",
                    type:'POST',
                    dataType: 'json',
                    success:function(R){
                        eval(R.js);
                        if(R.html != ''){
                            $("._buscar_concepto").focus();
                            $('#cuentas').val(R.prop);
                            $('#depositante').html(R.html);
                        }
                    }
                });
            }
        });
    });
    $(function(){
        $('#cuentas').autocomplete({
            source: "<?php echo site_url('manager/autocomplete') . '/cuentas_corrientes' ?>",
            select: function(event, ui) {
                $.ajax({
                    url : BASE_URL + "manager/buscar_prop/cuentas_corrientes/"+ui.item.id,
                    type:'POST',
                    dataType: 'json',
                    success:function(R){
                        eval(R.js);
                        if(R.html != ''){
                            $('#cuentas').html(R.html);
                            $('#cuentas').blur();
                        }
                    }
                });
            }
        });
        $("#cuentas").keydown(function(){
            if ($("#cuentas").val().length == 1){
                $.ajax({
                    url : BASE_URL + "manager/buscar_prop/cuentas_corrientes/",
                    type:'POST',
                    dataType: 'json',
                    success:function(R){
                        eval(R.js);
                        if(R.html != ''){
                            $('#cuentas').html(R.html);
                        }
                    }
                });
            }
        });
    });
 

</script>
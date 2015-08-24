
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
        <div id="tooltipInt">
            En caso de mora, ingrese los dias para computar intereses
        </div>
        <div id="tooltipInm">
            Inmueble al que este relacionado este crédito
        </div>

        <div id="tooltipConc">
            Presione para agregar un Concepto nuevo
        </div>
        <form  style="overflow: visible;"class="<?= isset($row) ? 'edit_form' : 'add_form' ?>" action="javascript:;" onsubmit="request_post_cuenta('<?= site_url('manager/save_creditos') ?>',this,'.contenedor_centro');return false;" enctype="multipart/form-data"> 
            <div class="bloque">
                <input id="auto_cc_id" name="auto_cc_id" type="hidden"/>
                <input id="auto_depo_id" name="auto_depo_id" type="hidden"/>
                <input onkeyup="validar_cliente('depositante')" onblur="validar_cliente('depositante')" type="text" id="depositante" name="depositante" style="margin-right: 5px;font-size: 16px;width: 403px;float: left;" class="form-control ui-autocomplete-input" placeholder="Depositante/Inquilino">
                <input onkeyup="validar_cc('cuentas')" onblur="validar_cc('cuentas')" type="text" id="cuentas" name="cc" style="margin-right: 5px;font-size: 16px;width: 403px;float: left;" class="form-control ui-autocomplete-input" placeholder="Cta. Cte./Propietario">                    
                <div class="forma_pago_select">
                    <label style="margin-left: 5px; margin-bottom: 0px;">Forma de Pago</label>
                    <select onchange="toggle_element($('select option:selected').val())" autofocus="1" class="form-control ui-autocomplete-input" name="cred_forma">
                        <option class="form-control ui-autocomplete-input"<?= (isset($row) && $row->cred_forma == 'Efectivo' ? 'selected="selected"' : '' ) ?> value="Efectivo">Efectivo</option>
                        <option class="form-control ui-autocomplete-input"<?= (isset($row) && $row->cred_forma == 'Cheque' ? 'selected="selected"' : '' ) ?>  value="Cheque">Cheque</option>
                    </select>
                </div> 
                <div class="forma_pago_select">
                    <label style="margin-left: 5px; margin-bottom: 0px;">Tipo de Transacción</label>
                    <select onchange="toggle_element($('select option:selected').val())" autofocus="1" class="form-control ui-autocomplete-input" name="cred_tipo_trans">
                        <option class="form-control ui-autocomplete-input"<?= (isset($row) && $row->cred_tipo_trans == 'Caja' ? 'selected="selected"' : '' ) ?>  value="Caja">Caja</option>
                        <option class="form-control ui-autocomplete-input"<?= (isset($row) && $row->cred_tipo_trans == 'Bancaria' ? 'selected="selected"' : '' ) ?> value="Bancaria">Bancaria</option>
                    </select>
                </div>
            </div>
            <div class="bloque" id="bloque1">
                <input onkeyup="validar(1)" onblur="validar(1)" type="text" id="concepto1" name="concepto1" style="margin-right: 5px;font-size: 16px;width: 361px;float: left;" class="form-control ui-autocomplete-input" placeholder="Concepto">
                <input id="auto_conc_id1" name="auto_conc_id1" type="hidden"/>
                <span id="add_conc" style="float: left;margin-top: -1px;padding:8; margin-right: 6px;" onclick="request('<?= site_url('load_concept') ?>',$(this).serialize(),'#popup');popup()" type="button" class="btn btn-default btn-lg">
                    <a style="text-decoration: none"class="glyphicon glyphicon-plus-sign"></a></span>
                <input type="text" id="monto1"onkeyup="recalcular()" onblur="recalcular()" name="monto1" style="margin-right: 5px;font-size: 16px;width: 110px;float: left;" class="form-control ui-autocomplete-input" placeholder="Monto">
                <div style="width: 111px;" class="forma_pago_select">
                    <label style="margin-left: 5px; margin-bottom: 0px;">Tipo pago</label>
                    <select onchange="toggle_element($('select option:selected').val())" autofocus="1" class="form-control ui-autocomplete-input" id="cred_tipo_pago" name="cred_tipo_pago">
                        <option class="form-control ui-autocomplete-input"<?= (isset($row) && $row->cred_tipo_trans == 'Total' ? 'selected="selected"' : '' ) ?> id="total" value="Total">Total</option>
                        <option class="form-control ui-autocomplete-input"<?= (isset($row) && $row->cred_tipo_trans == 'A Cuenta' ? 'selected="selected"' : '' ) ?>  id="cuenta" value="A Cuenta">A Cuenta</option>
                        <option class="form-control ui-autocomplete-input"<?= (isset($row) && $row->cred_tipo_trans == 'Saldo' ? 'selected="selected"' : '' ) ?>  id="saldo" value="Saldo">Saldo</option>
                    </select>
                </div>
                <input type="text" id="mes1" name="mes1" style="margin-right: 5px;font-size: 16px;width: 120px;float: left;" class="form-control ui-autocomplete-input" placeholder="Mes">
                <input type="text" id="domicilio1" name="domicilio1" style="margin-right: 5px;font-size: 16px;width: 230px;float: left;" class="form-control ui-autocomplete-input" placeholder="Domicilio Inmueble">
            </div>
            <div id="periodos" class="periodos"></div>
            <div id="relleno">
            </div>
            <div id="montos" style="display: none;width: 100%;float: left">
                <label type="text" style="margin-top: 0px;text-align: center;background: none;margin-right: 5px;font-size: 16px;width: 402px;float: left;" class="form-control ui-autocomplete-input"> SUBTOTALES </label>
                <input type="text" id="monto" style="margin-right: 5px;font-size: 16px;width: 160px;float: left;" class="form-control ui-autocomplete-input" readonly="enabled">
                <label type="text" style="clear: both;margin-top: 0px;text-align: center;background: none;margin-right: 5px;font-size: 16px;width: 402px;float: left;" class="form-control ui-autocomplete-input"> INTERESES </label>                
                <input type="text" id="intere" style="margin-right: 5px;font-size: 16px;width: 160px;float: left;" class="form-control ui-autocomplete-input" readonly="enabled">
                <label type="text" style="clear: both;margin-top: 0px;text-align: center;background: none;margin-right: 5px;font-size: 16px;width: 402px;float: left;" class="form-control ui-autocomplete-input"> IVA </label>                
                <input type="text" id="iva" style="margin-right: 5px;font-size: 16px;width: 160px;float: left;" class="form-control ui-autocomplete-input" readonly="enabled">
                <label type="text" style="clear: both;margin-top: 0px;text-align: center;background: none;margin-right: 5px;font-size: 16px;width: 402px;float: left;" class="form-control ui-autocomplete-input"> TOTAL </label>                
                <input type="text" id="total_todo" style="margin-right: 5px;font-size: 16px;width: 160px;float: left;" class="form-control ui-autocomplete-input" readonly="enabled">                
            </div>
            <button class="btn btn-primary" type="submit" id="buttons_cli" style="margin-bottom: 50px;margin-top: 13px;float: left; line-height: 0;"><?= isset($row) ? 'Guardar' : 'Crear' ?></button>
            <input type="hidden" id="tableo" value="0">
            <input type="hidden" name="cant_bloques" id="cant_bloques" value="1">
        </form>
        <span style="float: right;" onclick="agregar()" type="button" class="btn btn-default btn-lg">
            <a style="text-decoration: none"class="glyphicon glyphicon-plus-sign"></a> Agregar
        </span>

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
            if (count($creditos) > 0) {
                for ($x = 0; $x < count($creditos); $x++) {
                    echo '<tr class="reg_' . $creditos[$x]['cred_id'] . '">';
                    echo '<td>' . $creditos[$x]['cred_depositante'] . '</td>';
                    echo '<td>' . $creditos[$x]['cred_cc'] . '</td>';
                    echo '<td>' . $creditos[$x]['cred_concepto'] . ' (' . $creditos[$x]['cred_mes_alq'] . ')' . '</td>';
                    echo '<td>$ ' . $creditos[$x]['cred_monto'] . '</td>';
                    echo '<td>' . $creditos[$x]['cred_fecha'] . '</td>';
                    echo '<td>';
                    echo '<a href="javascript:;" class="glyphicon glyphicon-print" onclick="request_post(\'' . site_url('manager/imprimir_recibo/' . $creditos[$x]['trans']) . '\')"></a> | ';
                    echo '<a href="javascript:;" id="' . $creditos[$x]['trans'] . '" class="glyphicon glyphicon-trash" onclick="del_transact(this)"></a>  ';
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
    function unlock(id){
        if($('#interes'+id).attr('readonly')== 'readonly'){
            request('<?= site_url('load_locker') ?>'+'/'+id,$(this).serialize(),'#popup');popup()
        }
    }
    function recalcular(){
        var monto = 0;
        var iva = 0;
        var total_todo = 0;
        var intere = 0;
        //recalculo montos
        for(var q=1; q <= x; q++){
            monto += $('#monto'+q).val() * 1;
        }
        //recalculo intereses
        for(var i=1; i <= x; i++){
            if($('#interes_calculado'+i).length > 0){
                // recalculo interes si hay
                var mora = $('#interes'+i).val();
                mora = mora.replace(/\D/g,'');
                var interes = $('#monto'+i).val() * mora * $('#con_punitorio').val();
                var original=parseFloat(interes);
                var result=Math.round(original*100)/100 ;
                $('#interes_calculado'+i).val(result);
                if($('#monto'+i).val() == ''){
                    $('#interes_calculado'+i).val('');
                }
                intere += interes;
            }
        }
        //recalculo iva
        for(var i=1; i <= x; i++){
            if($('#iva_calculado'+i).length > 0){
                // recalculo iva si hay
                var ivas = $('#monto'+i).val() * 0.21;
                var original=parseFloat(ivas);
                var result=Math.round(original*100)/100 ;
                $('#iva_calculado'+i).val(result);
                if($('#monto'+i).val() == ''){
                    $('#iva_calculado'+i).val('');
                }
                iva += ivas;
            }
        }
        var original=parseFloat(iva);
        var result_iva=Math.round(original*100)/100 ;
        $('#iva').val(result_iva);   
        var original=parseFloat(intere);
        var result_intere=Math.round(original*100)/100 ;
        $('#intere').val(result_intere);   
        var original=parseFloat(monto);
        var result_monto=Math.round(original*100)/100 ;
        $('#monto').val(result_monto);
        total_todo = monto + intere;
        var original=parseFloat(total_todo);
        var result_total_todo=Math.round(original*100)/100 ;
        $('#total_todo').val(result_total_todo);
    }
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
        $( "#mes1" ).autocomplete({
            source: meses
        });  
    });
    function validar(id){       
        var value = $('#concepto'+id).val();
        request('<?= site_url('validate') ?>/conc_id/conc_desc/conceptos/auto_conc_id'+id+'/concepto'+id+'/'+value,'','#auto_conc_id'+id)          
    }
    function validar_cc(id_input){       
        var value = $('#'+id_input).val();
        request('<?= site_url('validate') ?>/cc_id/cc_prop/cuentas_corrientes/auto_cc_id/'+id_input+'/'+value,'','#auto_cc_id')          
    }
    function validar_cliente(id_input){       
        var value = $('#'+id_input).val();
        request('<?= site_url('validate') ?>/client_id/client_name/clientes/auto_depo_id/'+id_input+'/'+value,'','#auto_depo_id')          
    }
    var x = 1;
    var cant = 1;
    function agregar(){   
        x++; 
        //Creo el Bloque
        jQuery('<div/>', {
            id: 'bloque'+x,
            'class' : 'bloque'
        }).appendTo('#relleno').hide().fadeIn(700);
        
        jQuery('<input/>', {
            id: 'auto_conc_id'+x,
            name: 'auto_conc_id'+x,
            type: 'hidden',
            autocomplete: 'off'           
        }).appendTo('#bloque'+x);
        //Creo los inputs Concepto, Monto y Mes Alquiler
        jQuery('<input/>', {
            id: 'concepto'+x,
            name: 'concepto'+x,
            type: 'text',
            onblur:'validar('+x+')',
            onkeyup:'validar('+x+')',
            style : 'margin-right: 5px;font-size: 16px;width: 290px;float: left;',
            'class': 'form-control ui-autocomplete-input',
            placeholder: 'Concepto'                 
        }).appendTo('#bloque'+x);
        //Llamo al autocomplete para este nuevo input!
        $('#concepto'+x).autocomplete(auto_opt);
        
        jQuery('<input/>', {
            id: 'monto'+x,
            name: 'monto'+x,
            onkeyup:'recalcular()',
            onblur:'recalcular()',
            type: 'text',
            autocomplete: 'off',
            style : 'margin-right: 5px;font-size: 16px;width: 110px;float: left;',
            'class': 'form-control ui-autocomplete-input',
            placeholder: 'Monto'                 
        }).appendTo('#bloque'+x);

        jQuery('<input/>', {
            id: 'mes'+x,
            name: 'mes'+x,
            type: 'text',
            style : 'margin-right: 5px;font-size: 16px;width: 120px;float: left;',
            'class': 'form-control ui-autocomplete-input',
            placeholder: 'Mes'                 
        }).appendTo('#bloque'+x);
        $('#mes'+x).autocomplete({
            source: meses
        }); 
        jQuery('<input/>', {
            id: 'domicilio'+x,
            name: 'domicilio'+x,
            type: 'text',
            style : 'margin-right: 5px;font-size: 16px;width: 230px;float: left;',
            'class': 'form-control ui-autocomplete-input',
            placeholder: 'Domicilio Inmueble'                 
        }).appendTo('#bloque'+x);
        $('#domicilio'+x).autocomplete(auto_opt_dom); 
        
        
        $(document).ready(function(){
            $('#domicilio1').hover(function(){
                $('#tooltipInm').css('display','block');
            },function(){
                $('#tooltipInm').css('display','none');
            });
            $(document).mousemove(function(event){
                var mx = event.pageX;
                var my = event.pageY;
                $('#tooltipInm').css('left',mx+'px').css('right',my+'px').css('top',-18);
            })  
        });
        $(document).ready(function(){
            $('#interes1').hover(function(){
                $('#tooltipInt').css('display','block');
            },function(){
                $('#tooltipInt').css('display','none');
            });
            $(document).mousemove(function(event){
                var mx = event.pageX;
                var my = event.pageY;
                $('#tooltipInt').css('left',mx+'px').css('right',my+'px').css('top',-18);
            })   
        });    
        $(document).ready(function(){
            $('#domicilio'+x).hover(function(){
                $('#tooltipInm').css('display','block');
            },function(){
                $('#tooltipInm').css('display','none');
            });
            $(document).mousemove(function(event){
                var mx = event.pageX;
                var my = event.pageY;
                $('#tooltipInm').css('left',mx+'px').css('right',my+'px').css('top',-18);
            })  
        });
        $(document).ready(function(){
            $('#add_conc').hover(function(){
                $('#tooltipConc').css('display','block');
            },function(){
                $('#tooltipConc').css('display','none');
            });
            $(document).mousemove(function(event){
                var mx = event.pageX;
                var my = event.pageY;
                $('#tooltipConc').css('left',mx+'px').css('right',my+'px').css('top',-18);
            })  
        }); 
        $(document).ready(function(){
            $('#interes'+x).hover(function(){
                $('#tooltipInt').css('display','block');
            },function(){
                $('#tooltipInt').css('display','none');
            });
            $(document).mousemove(function(event){
                var mx = event.pageX;
                var my = event.pageY;
                $('#tooltipInt').css('left',mx+'px').css('right',my+'px').css('top',-18);
            })   
        });
        
        //Creo su boton para eliminar            
        jQuery('<span/>', {
            id: 'span'+x,
            onclick: 'removeElement('+x+')',
            style: 'height: 34px;',
            'class' : 'btn btn-default btn-lg'
        }).appendTo('#bloque'+x);
        var anterior = x - 1;
        $('#span'+anterior).attr('disabled',true);
        jQuery('<a/>', {
            'class' : 'glyphicon glyphicon-minus-sign',
            style : 'text-decoration: none; margin-top: -3px;'
        }).appendTo('#span'+x);
        
        //Aumenta el contador
        cant++;
        $("#cant_bloques").val(cant);        
    }
    
    function removeElement(id) {
        var e = id - 1;
        var monto = 0;
        var iva = 0;
        var intereses = 0;
        var iva_total = $('#iva').val();
        var intereses_total = $('#intere').val();
        var monto_total = $('#monto').val();
        var total_todo = $('#total_todo').val();
        if($('#interes_calculado'+id).length > 0){
            intereses = $('#interes_calculado'+id).val();
            intereses_total = intereses_total - intereses; 
            total_todo = total_todo - intereses;
        }
        if($('#iva_calculado'+id).length > 0){
            iva = $('#iva_calculado'+id).val();
            iva_total = iva_total - iva; 
            total_todo = total_todo - iva;
        }
        monto = $('#monto'+id).val();
        monto_total = monto_total - monto;
        total_todo = total_todo - monto;
        $('#monto').val(monto_total);
        $('#intere').val(intereses_total);
        $('#iva').val(iva_total);
        $('#total_todo').val(total_todo);
        $("#bloque"+id).remove();
        $("#span"+e).removeAttr('disabled');
        x--;
        cant--;
        $("#cant_bloques").val(cant);
    }
    $(function(){
        $('#depositante').autocomplete({
            source: "<?php echo site_url('manager/autocomplete') . '/clientes' ?>",
            select: function(event, ui) {
                $.ajax({
                    url : BASE_URL + "manager/buscar_cliente/clientes/"+ui.item.id,
                    type:'POST',
                    dataType: 'json',
                    success:function(R){
                        eval(R.js);
                        if(R.html != ''){
                            if(R.prop == null){
                                $('#periodos').html(R.periodos);
                                $('#periodos').fadeIn('slow');    
                            }else{
                                $('#cuentas').val(R.prop);    
                                $('#cuentas').blur();    
                            }
                            $("#concepto1").focus();
                            $('#depositante').html(R.html);
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
                            $("#concepto1").focus();
                            $('#depositante').html(R.html);
                            //                            $('#auto_depo_id').val(R.id);
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
                            //                            $('#auto_cc_id').val(R.id);
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
                            //                            $('#auto_cc_id').val(R.id);
                        }
                    }
                });
            }
        });
    });
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
   
    //Variable que almacena las Options del autocomplete. De manera que pueda llamarlo luego de crear el input
    //Para cada input creado dinamicamente
    var auto_opt = {
        source: "<?php echo site_url('manager/autocomplete') . '/conceptos/Entrada' ?>",
        select: function(event, ui) {
            $.ajax({
                url : BASE_URL + "manager/buscar_concepto/conceptos/Entrada/"+$('#tableo').val()+'/'+$('#depositante').val()+'/'+$('#cuentas').val()+'/'+ui.item.id+'/'+$(this).attr('id'),
                type:'POST',
                dataType: 'json',
                beforeSend:function(){
                    loading.show();
                },
                success:function(R){
                    eval(R.js);
                    if(R.html != ''){    
                        $(this).val(R.html);
                        $("[id^=concepto]").blur();
                        if(R.alq == 1){
                            $('#cuentas').attr('readonly',true)
                            $('#depositante').attr('readonly',true)
                            $('#periodos').fadeOut('slow', function() {
                                $("#periodos").removeClass("alert alert-danger")
                                $('#periodos').html(R.periodos);
                                $('#periodos').fadeIn('slow');                                                         
                            });
                            if(R.entro == 0){
                                $('#tableo').val(0);
                                $(".periodos").addClass("alert alert-danger")
                            }else{
                                $(".periodos").removeClass("alert alert-danger");
                                $('#tableo').val(1);     
                            }                          
                        }else{
                            if($('#tableo').val()==0){
                                $("#periodos").html("")
                                //                                $("#periodos").addClass("alert alert-danger")
                                $('#periodos').html(R.periodos);
                                $("#periodos").css('margin-top','0px')
                            }
                        }
                    }
                    
                    loading.hide();
                    $("#monto1").focus();
                },
                complete:function(){
//                    setInterval(function () {$("#monto1").blur()}, 1000);
                }
            });
        } 
    };
    var key_opt = function(){
        if ($(this).val().length == 1){
            $.ajax({
                url : BASE_URL + "manager/buscar_concepto/conceptos/Entrada/",
                type:'POST',
                dataType: 'json',
                success:function(R){
                    eval(R.js);
                    if(R.html != ''){       
                        $(this).val(R.html);                       
                        if(R.alq == 1){
                            $('#periodos').fadeOut('slow', function() {
                                $('#periodos').html(R.periodos);                                    
                                $('#periodos').fadeIn('slow');
                                $('#tableo').val(1);                                  
                            });
                            if(R.entro == 0){
                                $(".periodos").addClass("alert alert-danger")
                            }
                        }else{
                            if($('#tableo').val()==0){
                                $("#periodos").html("")
                                $("#periodos").css('margin-top','0px')
                                $("#periodos").removeClass("alert alert-danger")
                            }
                        }
                    }
                }
            });
        }
    }
    $("[id^=concepto]").each(function(){
        /* Por cada input #concepto, ya sea concepto1, 
         * concepto2, etc, existe este script universal 
         * que maneja todos y cada uno de los autocomplete
         * de dichos inputs
         * ES UN AUTOCOMPLETE DE MULTIPLES INPUTS */
        
        /* El operador ^ hace las veces de comodin
         * señala que lo que esta a la derecha sea una
         * subcadena del input, para tenerlo en cuenta 
         * en el autocomplete*/
      
        $(this).autocomplete(auto_opt);
        $(this).keydown(key_opt);
        
    });
    
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
    $("[id^=domicilio]").each(function(){
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
</script>

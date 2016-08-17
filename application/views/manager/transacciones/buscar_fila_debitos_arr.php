<style>
    #ccnav a:hover{
        color:black;
    }
</style>    
<h2><?= t('Debitos') ?></h2>
<ul id="ccnav" class="nav nav-tabs">
    <li><a href="#agregar" data-toggle="tab">Nuevo Debito</a></li>
    <li class="active"><a href="#lista" data-toggle="tab">Lista de Debitos</a></li>
</ul>

<div   class="tab-content">
    <!--  Crear Cuenta  -->
    <div class="tab-pane fade" id="agregar">
        <form  style="overflow: visible;"class="<?= isset($row) ? 'edit_form' : 'add_form' ?>" action="javascript:;" onsubmit="request_post_cuenta('<?= site_url('manager/save_debitos') ?>',this,'.contenedor_centro');return false;" enctype="multipart/form-data"> 
            <div id="tooltipInm">
                Inmueble al que este relacionado este débito
            </div>

            <div class="bloque">
                <input value="<?= isset($prop_id) ? $prop_id : '' ?>" id="auto_cc_id" name="auto_cc_id" type="hidden"/>
                <input onkeyup="validar_persona('cuentas')" onblur="validar_persona('cuentas')" value="<?= isset($prop) ? $prop : '' ?>" type="text" id="cuentas" name="cc" style="margin-right: 5px;font-size: 16px;width: 403px;float: left;" class="form-control ui-autocomplete-input" placeholder="Cta. Cte.">
                <input type="hidden" id="saldo_cuenta" value="0">
            </div>
            <div class="bloque" id="bloque1">
                <input onkeyup="validar(1)" onblur="validar(1)" value="<?= isset($concepto) ? $concepto : '' ?>" type="text" id="concepto1" name="concepto1" style="margin-right: 5px;font-size: 16px;width: 403px;float: left;" class="form-control ui-autocomplete-input" placeholder="Concepto">
                <input value="<?= isset($concepto_id) ? $concepto_id : '' ?>" id="auto_conc_id1" name="auto_conc_id1" type="hidden"/>
                <input value="<?= isset($concepto_control) ? $concepto_control : '' ?>" id="conc_control1" name="conc_control1" type="hidden"/>
                <span id="add_conc" style="float: left;margin-top: -1px;padding:8; margin-right: 6px;" onclick="request('<?= site_url('load_concept') ?>',$(this).serialize(),'#popup2');popup2()" type="button" class="btn btn-default btn-lg">
                    <a style="text-decoration: none"class="glyphicon glyphicon-plus-sign"></a></span>
                <input type="text" onkeyup="validar_rendicion();validar_monto(1)" onblur="validar_rendicion();validar_monto(1)" id="monto1" name="monto1" style="margin-right: 5px;font-size: 16px;width: 100px;float: left;" class="form-control ui-autocomplete-input" placeholder="Monto">
                <input type="text" id="domicilio1" name="domicilio1" style="margin-right: 5px;font-size: 16px;width: 230px;float: left;" class="form-control ui-autocomplete-input" placeholder="Domicilio Inmueble">
                <input type="text" id="mes1" value="" name="mes1" style="margin-right: 5px;font-size: 16px;width: 150px;float: left;" class="form-control ui-autocomplete-input" placeholder="Mes">
                <div class="forma_pago_select">
                    <label style="margin-left: 5px; margin-bottom: 0px;">Forma de Pago</label>
                    <select onchange="toggle_element($('select option:selected').val())" autofocus="1" class="form-control ui-autocomplete-input" name="deb_forma">
                        <option class="form-control ui-autocomplete-input"<?= (isset($row) && $row->deb_forma == 'Efectivo' ? 'selected="selected"' : '' ) ?> value="Efectivo">Efectivo</option>
                        <option class="form-control ui-autocomplete-input"<?= (isset($row) && $row->deb_forma == 'Cheque' ? 'selected="selected"' : '' ) ?>  value="Cheque">Cheque</option>
                    </select>
                </div>
                <div class="forma_pago_select">
                    <label style="margin-left: 5px; margin-bottom: 0px;">Tipo de Transacción</label>
                    <select onchange="toggle_element($('select option:selected').val())" autofocus="1" class="form-control ui-autocomplete-input" name="deb_tipo_trans">
                        <option class="form-control ui-autocomplete-input"<?= (isset($row) && $row->deb_tipo_trans == 'Caja' ? 'selected="selected"' : '' ) ?>  value="Caja">Caja</option>
                        <option class="form-control ui-autocomplete-input"<?= (isset($row) && $row->deb_tipo_trans == 'Bancaria' ? 'selected="selected"' : '' ) ?> value="Bancaria">Bancaria</option>
                    </select>
                </div>
                <div id="periodos" class="periodos"></div>
            </div>

            <div id="relleno">
            </div>
            <span style="float: right;" onclick="agregar()" type="button" class="btn btn-default btn-lg">
                <a style="text-decoration: none"class="glyphicon glyphicon-plus-sign"></a> Agregar
            </span>

            <div class="autocrea">
                <button class="btn btn-primary" type="submit" id="buttons_cli" style="margin-top: 13px;float: left; line-height: 0;"><?= isset($row) ? 'Guardar' : 'Crear' ?></button>                    
            </div>  
            <input type="hidden" name="need_auth" id="need_auth" value="0">  
            <input type="hidden" id="tableo" value="0">
            <input type="hidden" name="supera_saldo" id="supera_saldo" value="0">
            <input type="hidden" name="cant_bloques" id="cant_bloques" value="1">
        </form>

        <div id="com_display">
            <span></span>
        </div>
    </div>
    <div class="tab-pane fade in active" id="lista"> 
        <div class="actions_container">
            <div class="field span3 buscar" id="mio">
                <div class="alert alert-info">Filtre por Cta Cte. rangos de fecha o la combinacion de ambas</div>
                <input name="cliente" style="height: 26px;" placeholder="Cta. Cte." type="text" value="" id="auto_personas" class="ui-autocomplete-input">
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
                <th>Cta. Cte. Debitada</th>
                <th>Concepto</th>
                <th>Monto</th>

                <th>Domicilio Inmueble</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
            <?
            if (count($debitos) > 0) {
                for ($x = 0; $x < count($debitos); $x++) {
                    echo '<tr class="reg_' . $debitos[$x]['deb_id'] . '">';
                    echo '<td>' . $debitos[$x]['deb_cc'] . '</td>';
                    echo '<td>' . $debitos[$x]['deb_concepto'] . ' (' . $debitos[$x]['deb_mes'] . ')</td>';
                    echo '<td>$ ' . $debitos[$x]['deb_monto'] . '</td>';

                    echo '<td>' . $debitos[$x]['deb_domicilio'] . '</td>';
                    echo '<td>' . $debitos[$x]['deb_fecha'] . '</td>';
                    echo '<td>';
                    
                    echo '<a href="javascript:;" id="' . $debitos[$x]['trans'] . '" class="glyphicon glyphicon-trash" onclick="del_transact(this)"></a>  ';
                    if ($debitos[$x]['deb_concepto'] == 'Rendicion') {
                        echo ' | <a href="javascript:;" class="glyphicon glyphicon-print" onclick="request_post(\'' . site_url('manager/imprimir_rendicion/' . $debitos[$x]['trans']) . '\')"></a>';
                    }
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="100%">' . t('No records found.') . '</td></tr>';
            }
            ?>
        </table>

    </div>
</div>

<script>  
    
    var valor_debitos = 0;
    function validar_rendicion(){
        var rendiciones = 0;
        for(var i = 1; i<=x ;i++){
            if($('#concepto'+i).val() == 'Rendicion'){
                rendiciones += parseFloat($('#monto'+i).val())
            }
        }
        for(var i = 1; i<=x ;i++){
            if($('#concepto'+i).val() == 'Rendicion'){     
                if(Number(rendiciones) > Number($('#saldo_cuenta').val())){
                    $('#supera_saldo').val(1);
                    if(!($('#cartelito').length)){
                        jQuery('<div/>', {
                            'class' : 'rendir'
                        }).appendTo('.autocrea').hide().fadeIn(700);
                
                        jQuery('<label/>', {
                            id: 'cartelito',
                            style: 'float: left;font-size: 15.2px;margin: 0 auto 0 346px;text-align: center;width: 449px;',
                            html: 'Las rendiciones no pueden superar el saldo disponible en la Cta. Cte. del propietario'         
                        }).appendTo('.rendir');
                    }
                }else{
                    $('.rendir').remove();
                    $('#supera_saldo').val(0);
                }
            }
        }
    }
    function validar_monto(id){       
        var value = $('#monto'+id).val();
        var control = $('#conc_control'+id).val();  
        if(value >= 1000){    
            var codigos = document.getElementById('codigo');
            if(codigos == null){
                if(control == 1){ 
                    $('#need_auth').val(1);
                    jQuery('<div/>', {
                        'class' : 'auth'
                    }).appendTo('.autocrea').hide().fadeIn(700);
                
                    jQuery('<label/>', {
                        style: 'float: left;font-size: 15.2px;margin: 0 auto 0 346px;text-align: center;width: 449px;',
                        html: 'Los débitos igual o mayores a $ 1000 requieren un código de autorización. Para continuar con la operación solicite el código a su encargado'         
                    }).appendTo('.auth');
         
                    jQuery('<input/>', {
                        id: 'codigo',
                        'class' : 'form-control ui-autocomplete-input',
                        type:'text',
                        name: 'codigo',
                        placeholder: 'Código',
                        style: 'clear: both;float: left;font-size: 16px;margin-left: 500px;text-align: center;width: 129px;'
                    }).appendTo('.auth');
                }
            }
        }else{       
            var mayor = false;
            for( var i=1 ; i<= x ; i++){
                if($('#monto'+i).val() >= 1000){
                    if($('#conc_control'+i).val() == 1){
                        mayor = true;
                    }
                }
            }
            if(!mayor){
                $('#need_auth').val(0);
                $('.auth').remove();
            }
        }
    }
    
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
    function validar(id){       
        var value = $('#concepto'+id).val();
        request('<?= site_url('validate') ?>/conc_id/conc_desc/conceptos/auto_conc_id'+id+'/concepto'+id+'/'+value,'','#auto_conc_id'+id)          
    }
    function validar_persona(id_input){       
        var value = $('#'+id_input).val();
        request('<?= site_url('validate') ?>/cc_id/cc_prop/cuentas_corrientes/auto_cc_id/'+id_input+'/'+value,'','#auto_cc_id')          
    }
    function del_transact(a){
        var id = $(a).attr('id');
        request('<?= site_url() . 'del_transact/debitos/' ?>'+id,'','#popup1');
        popup1();
    }
    $("#asd").click(function() {      
        var desde = $('#desde').val();
        var hasta = $('#hasta').val();
        var cc = $('#cliente').val();
        var concepto = $('#concepto').val();
        var exe = 0;
        var cc = cc.replace(',','.');
        if(cc == '' && hasta != '' && desde != ''&& concepto == ''){
            request('<?= site_url() . 'filtrar_debito/' ?>/'+desde+'/'+hasta,'','.contenedor_centro');
            exe = 1;
        }
        if(cc != '' && hasta == '' && desde == ''&& concepto == ''){
            request('<?= site_url() . 'filtrar_debito_2/' ?>'+cc,'','.contenedor_centro');
            exe = 1;
        }
        if(cc != ''&& concepto == ''){
            if(hasta != '' && desde != ''&& concepto == ''){
                request('<?= site_url() . 'filtrar_debito3/' ?>'+cc+'/'+desde+'/'+hasta,'','.contenedor_centro');
                exe = 1;
            }
        }
        if(exe == 0){
            if(concepto != ''){
                if(hasta != '' && desde != '' && cc != ''){
                    request('<?= site_url() . 'filtrar_deb_4/' ?>'+cc+'/'+desde+'/'+hasta+'/'+concepto,'','.contenedor_centro');   
                }else if(hasta == '' && desde == '' && cc != ''){
                    request('<?= site_url() . 'filtrar_deb_2/' ?>'+cc+'/'+concepto,'','.contenedor_centro');    
                }else if(hasta != '' && desde != '' && cc == ''){
                    request('<?= site_url() . 'filtrar_deb_3/' ?>'+desde+'/'+hasta+'/'+concepto,'','.contenedor_centro');    
                }else{
                    request('<?= site_url() . 'filtrar_deb_1/' ?>'+concepto,'','.contenedor_centro');        
                } 
            }
        }
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
    function refrescar(){
        request('<?= site_url() . 'refresh/' ?>debitos/deb_id','','.contenedor_centro');
    }
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
            source: "<?php echo site_url('manager/autocomplete') . '/conceptos/Salida' ?>",
            select: function(event, ui) {
                $.ajax({
                    url : BASE_URL + "manager/buscar_concepto_c/conceptos/Salida/"+ui.item.id,
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
                    url : BASE_URL + "manager/buscar_concepto_c/conceptos/Salida/",
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
        
        jQuery('<input/>', {
            id: 'conc_control'+x,
            name: 'conc_control'+x,
            type: 'hidden',
            autocomplete: 'off'           
        }).appendTo('#bloque'+x);
        
        //Creo los inputs Concepto y Monto
        jQuery('<input/>', {
            id: 'concepto'+x,
            name: 'concepto'+x,
            type: 'text',
            onblur:'validar('+x+')',
            onkeyup:'validar('+x+')',
            style : 'margin-right: 5px;font-size: 16px;width: 403px;float: left;',
            'class': 'form-control ui-autocomplete-input',
            placeholder: 'Concepto',
            'data-row-id': +x
        }).appendTo('#bloque'+x);
        //Llamo al autocomplete para este nuevo input!
        $('#concepto'+x).autocomplete(auto_opt);
        
        jQuery('<input/>', {
            id: 'monto'+x,
            onblur:'validar_rendicion();validar_monto('+x+')',
            onkeyup:'validar_rendicion();validar_monto('+x+')',
            name: 'monto'+x,
            type: 'text',
            autocomplete: 'off',
            style : 'margin-right: 5px;font-size: 16px;width: 100px;float: left;',
            'class': 'form-control ui-autocomplete-input',
            placeholder: 'Monto'                 
        }).appendTo('#bloque'+x);
        
        jQuery('<input/>', {
            id: 'domicilio'+x,
            name: 'domicilio'+x,
            type: 'text',
            style : 'margin-right: 5px;font-size: 16px;width: 272px;float: left;',
            'class': 'form-control ui-autocomplete-input',
            placeholder: 'Domicilio Inmueble'                 
        }).appendTo('#bloque'+x);
        $('#domicilio'+x).autocomplete(auto_opt_dom); 
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
        //mes
        
        jQuery('<input/>', {
            id: 'mes'+x,
            name: 'mes'+x,
            type: 'text',
            style : 'margin-right: 5px;font-size: 16px;width: 150px;float: left;',
            'class': 'form-control ui-autocomplete-input',
            placeholder: 'Mes'                 
        }).appendTo('#bloque'+x);
        $('#mes'+x).autocomplete({
            source: meses
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
        $("#span"+e).removeAttr('disabled');
        $("#bloque"+id).remove();
        x--;
        cant--;
        $("#cant_bloques").val(cant);
    }
    
    
    //Variable que almacena las Options del autocomplete. De manera que pueda llamarlo luego de crear el input
    //Para cada input creado dinamicamente
    var auto_opt = {
        source: "<?php echo site_url('manager/autocomplete') . '/conceptos/Salida' ?>",
        select: function(event, ui) {
            $.ajax({
                url : BASE_URL + "manager/buscar_concepto/conceptos/Salida//"+$('#cuentas').val()+'/'+ui.item.id,
                type:'POST',
                dataType: 'json',
                success:function(R){
                    eval(R.js);
                    if(R.html != ''){    
                        $(this).val(R.html);
                        $("[id^=concepto]").blur();
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
    };
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
        $(this).keydown(function(){
            if ($(this).val().length == 1){
                $.ajax({
                    url : BASE_URL + "manager/buscar_concepto/conceptos/Salida/",
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
        });
        
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

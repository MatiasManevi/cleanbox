<style>
    #ccnav a:hover{
        color:black;
    }
</style>    
<h2><?= t('Migrar') ?></h2>
<ul id="ccnav" class="nav nav-tabs">
    <li class="active"><a href="#agregar" data-toggle="tab">Migrar entre Ctas.</a></li>
<!--    <li><a href="#lista" data-toggle="tab">Migrar en Cta</a></li>-->
</ul>

<div class="tab-content">

    <!--  Migracion entre cuentas  -->
    <div class="tab-pane fade in active" id="agregar">
        <form  style="overflow: visible;" action="javascript:;" onsubmit="request_post_cuenta('<?= site_url('manager/save_migracion') ?>',this,'.contenedor_centro');return false;" enctype="multipart/form-data"> 
            <div id="tooltipConc">
                Presione para agregar un Concepto nuevo
            </div>
            <div class="porcentajes">
                <div class="porc_data">
                    <label style="font-size: 15.2px;">Aqui se puede migrar de la Cuenta Cte. "A" hacia la Cuenta Cte. "B" un Monto determinado bajo un concepto.</label>
                </div>
            </div>
            <div class="porcentajes">
                <div class="porc_data">
                    <label style="margin-bottom: 15px;font-size: 15.2px;">El Concepto debe existir tanto de "Entrada" como de "Salida", con el mismo Nombre</label>
                </div>
            </div>
            <div class="bloque">
                <input id="auto_dest_id" name="auto_dest_id" type="hidden"/>
                <input id="auto_orig_id" name="auto_orig_id" type="hidden"/>
                <input onkeyup="validar_cc('depositante','auto_orig_id')" onblur="validar_cc('depositante','auto_orig_id')" type="text" id="depositante" name="origen" style="margin-right: 5px;font-size: 16px;width: 403px;float: left;" class="form-control ui-autocomplete-input" placeholder="Cta. Cte. Origen">                
                <input onkeyup="validar_cc('cuentas','auto_dest_id')" onblur="validar_cc('cuentas','auto_dest_id')"type="text" id="cuentas" name="destino" style="margin-right: 5px;font-size: 16px;width: 403px;float: left;" class="form-control ui-autocomplete-input" placeholder="Cta. Cte. Destino">                
                <div class="forma_pago_select">
                    <label style="margin-left: 5px; margin-bottom: 0px;">Forma de Pago</label>
                    <select onchange="toggle_element($('select option:selected').val())" autofocus="1" class="form-control ui-autocomplete-input" name="cred_forma">
                        <option class="form-control ui-autocomplete-input"<?= (isset($row) && $row->cred_forma == 'Efectivo' ? 'selected="selected"' : '' ) ?> value="Efectivo">Efectivo</option>
                        <option class="form-control ui-autocomplete-input"<?= (isset($row) && $row->cred_forma == 'Cheque' ? 'selected="selected"' : '' ) ?>  value="Cheque">Cheque</option>
                    </select>
                </div> 
                <div class="forma_pago_select">
                    <label style="margin-left: 5px; margin-bottom: 0px;">Tipo de Transacción</label>
                    <select onchange="toggle_element($('select option:selected').val())" autofocus="0" class="form-control ui-autocomplete-input" name="cred_tipo_trans">
                        <option class="form-control ui-autocomplete-input"<?= (isset($row) && $row->cred_tipo_trans == 'Caja' ? 'selected="selected"' : '' ) ?>  value="Caja">Caja</option>
                        <option class="form-control ui-autocomplete-input"<?= (isset($row) && $row->cred_tipo_trans == 'Bancaria' ? 'selected="selected"' : '' ) ?> value="Bancaria">Bancaria</option>
                    </select>
                </div>
            </div>
            <div class="bloque" id="bloque1">
                <input id="auto_conc_id" name="auto_conc_id" type="hidden"/>
                <input onkeyup="validar(this,'auto_conc_id')" onblur="validar(this,'auto_conc_id')" type="text" id="concepto" name="concepto" style="margin-right: 5px;font-size: 16px;width: 403px;float: left;" class="form-control ui-autocomplete-input" placeholder="Concepto">
                <div style="display: hidden"id="tooltipInt">
                    El Concepto debe existir tanto de Entrada como de Salida
                </div>
                <span id="add_conc" style="float: left;margin-top: -1px;padding:8; margin-right: 6px;" onclick="request('<?= site_url('load_concept') ?>',$(this).serialize(),'#popup');popup()" type="button" class="btn btn-default btn-lg">
                    <a style="text-decoration: none"class="glyphicon glyphicon-plus-sign"></a></span>
                <input type="text" id="monto" name="monto" style="margin-right: 5px;font-size: 16px;width: 176px;float: left;" class="form-control ui-autocomplete-input" placeholder="Monto">
                <input type="text" id="mes" name="mes" style="margin-right: 5px;font-size: 16px;width: 180px;float: left;" class="form-control ui-autocomplete-input" placeholder="Mes">
                <input type="text" id="domicilio" name="domicilio" style="margin-right: 5px;font-size: 16px;width: 180px;float: left;" class="form-control ui-autocomplete-input" placeholder="Domicilio">
                <div id="periodos" class="periodos"></div>
            </div>
            <div id="relleno">
            </div>
            <button class="btn btn-primary" type="submit" id="buttons_cli" style="margin-bottom: 50px;margin-top: 13px;float: left; line-height: 0;"><?= isset($row) ? 'Guardar' : 'Crear' ?></button>
            <input type="hidden" id="tableo" value="0">
            <input type="hidden" name="cant_bloques" id="cant_bloques" value="1">

        </form>

    </div>
    <!--  Migracion en cuenta  -->
<!--    <div class="tab-pane fade" id="lista"> 

        <form  style="overflow: visible;margin-top: 25px;" action="javascript:;" onsubmit="request_post_cuenta('<?= site_url('manager/save_migracion_inside') ?>',this,'.contenedor_centro');return false;" enctype="multipart/form-data"> 
            <div class="porcentajes">
                <div class="porc_data">
                    <label style="font-size: 15.2px;">Aqui se puede migrar internamente de la Cuenta Cte. Principal de "A" hacia la Cuenta Cte. Secundaria de "A", o viceversa, un Monto determinado bajo un concepto.</label>
                </div>
            </div>
            <div class="porcentajes">
                <div class="porc_data">
                    <label style="margin-bottom: 20px;font-size: 15.2px;">Concepto debe existir como "Salida" de la Cuenta a la cual se debitara (Principal o Secundaria), y como  "Entrada" de la Cuenta a la cual se acreditara, con el mismo Nombre</label>
                </div>
            </div>
            <div class="bloque">
                <input onkeyup="validar_cc('cc','auto_id_interno')" onblur="validar_cc('cc','auto_id_interno')"type="text" id="cc" name="origen" style="margin-right: 5px;font-size: 16px;width: 403px;float: left;" class="form-control ui-autocomplete-input" placeholder="Cta. Cte.">                               
                <input id="auto_id_interno" name="auto_id_interno" type="hidden"/>
                <div class="forma_pago_select">
                    <label style="margin-left: 5px; margin-bottom: 0px;">Direccion del traspaso</label>
                    <select style="width: 175px;"onchange="toggle_element($('select option:selected').val())" autofocus="1" class="form-control ui-autocomplete-input" name="direccion">
                        <option class="form-control ui-autocomplete-input" selected="selected" value="cc_varios">Principal a Secundaria</option>
                        <option class="form-control ui-autocomplete-input" value="cc_saldo">Secundaria a Principal</option>                       
                    </select>
                </div> 
            </div>
            <div class="bloque" id="bloque1">
                <input onkeyup="validar(this,'auto_conc_id_int')" onblur="validar(this,'auto_conc_id_int')" type="text" id="concepto1" name="concepto1" style="margin-right: 5px;font-size: 16px;width: 403px;float: left;" class="form-control ui-autocomplete-input" placeholder="Concepto">
                <span id="add_conc" style="float: left;margin-top: -1px;padding:8; margin-right: 6px;" onclick="request('<?= site_url('load_concept') ?>',$(this).serialize(),'#popup');popup()" type="button" class="btn btn-default btn-lg">
                    <a style="text-decoration: none"class="glyphicon glyphicon-plus-sign"></a></span>
                <input id="auto_conc_id_int" name="auto_conc_id_int" type="hidden"/>
                <input type="text" id="monto1" name="monto1" style="margin-right: 5px;font-size: 16px;width: 176px;float: left;" class="form-control ui-autocomplete-input" placeholder="Monto">
                <input type="text" id="mes1" name="mes1" style="margin-right: 5px;font-size: 16px;width: 180px;float: left;" class="form-control ui-autocomplete-input" placeholder="Mes">
                <div id="periodos" class="periodos"></div>
            </div>
            <div id="relleno">
            </div>
            <button class="btn btn-primary" type="submit" id="buttons_cli" style="margin-bottom: 50px;margin-top: 13px;float: left; line-height: 0;"><?= isset($row) ? 'Guardar' : 'Crear' ?></button>
        </form>
    </div>-->
    <div id="com_display">
        <span></span>
    </div>
</div>
<div id="back_fader">
    <div id="popup">
    </div>
</div>


<script>
    function validar(concepto,id_inp){       
        var value = $(concepto).val();
        var id = $(concepto).attr('id');
        request('<?= site_url('validate_double') ?>/conc_id/conc_desc/conceptos/'+id_inp+'/'+id+'/'+value,'','#'+id_inp)          
    }
    function validar_cc(id_input, id_comprob){       
        var value = $('#'+id_input).val();
        request('<?= site_url('validate') ?>/cc_id/cc_prop/cuentas_corrientes/'+id_comprob+'/'+id_input+'/'+value,'','#'+id_comprob)
    }
    $(document).ready(function(){
        $('#concepto').hover(function(){
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
 
    //Variable que almacena las Options del autocomplete. De manera que pueda llamarlo luego de crear el input
    //Para cada input creado dinamicamente
    var auto_opt = {
        source: "<?php echo site_url('manager/autocomplete') . '/conceptos/Entrada' ?>",
        select: function(event, ui) {
            $.ajax({
                url : BASE_URL + "manager/buscar_concepto/conceptos/Entrada/"+$('#depositante').val()+'/'+$('#cuentas').val()+'/'+ui.item.id,
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
        });
        
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
        $( "#mes1" ).autocomplete({
            source: meses
        });  
        $( "#mes" ).autocomplete({
            source: meses
        }); 
    });
    
    $(function(){
        $('#depositante').autocomplete({
            source: "<?php echo site_url('manager/autocomplete') . '/cuentas_corrientes' ?>",
            select: function(event, ui) {
                $.ajax({
                    url : BASE_URL + "manager/buscar_prop/cuentas_corrientes/"+ui.item.id,
                    type:'POST',
                    dataType: 'json',
                    success:function(R){
                        eval(R.js);
                        if(R.html != ''){
                            $('#depositante').html(R.html);
                            $("#depositante").blur();
                            //                            $('#auto_orig_id').val(R.id);
                        }
                    }
                });
            }
        });
        $("#depositante").keydown(function(){
            if ($("#depositante").val().length == 1){
                $.ajax({
                    url : BASE_URL + "manager/buscar_prop/cuentas_corrientes/",
                    type:'POST',
                    dataType: 'json',
                    success:function(R){
                        eval(R.js);
                        if(R.html != ''){
                            $('#depositante').html(R.html);
                            $("#depositante").blur();
                            //                            $('#auto_orig_id').val(R.id);
                        }
                    }
                });
            }
        });
    });
    $(function(){
        $('#cc').autocomplete({
            source: "<?php echo site_url('manager/autocomplete') . '/cuentas_corrientes' ?>",
            select: function(event, ui) {
                $.ajax({
                    url : BASE_URL + "manager/buscar_prop/cuentas_corrientes/"+ui.item.id,
                    type:'POST',
                    dataType: 'json',
                    success:function(R){
                        eval(R.js);
                        if(R.html != ''){
                            $('#cc').html(R.html);
                            $('#cc').blur();
                        }
                    }
                });
            }
        });
        $("#cc").keydown(function(){
            if ($("#cc").val().length == 1){
                $.ajax({
                    url : BASE_URL + "manager/buscar_prop/cuentas_corrientes/",
                    type:'POST',
                    dataType: 'json',
                    success:function(R){
                        eval(R.js);
                        if(R.html != ''){
                            $('#cc').html(R.html);
                            $('#cc').blur();
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
                            //                            $('#auto_dest_id').val(R.id);
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
                            $('#cuentas').blur();
                            //                            $('#auto_dest_id').val(R.id);
                        }
                    }
                });
            }
        });
    });
</script>

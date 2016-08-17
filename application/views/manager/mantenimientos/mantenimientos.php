<style>
    #ccnav a:hover{
        color:black;
    }
</style>    
<h2><?= t('Mantenimientos') ?></h2>
<ul id="ccnav" class="nav nav-tabs">
    <li class="active"><a href="#agregar" data-toggle="tab">Agregar Nuevo</a></li>
    <li><a href="#lista" data-toggle="tab">Lista de Mantenimientos</a></li>
</ul>

<div class="tab-content">
    <!--  Crear Mantenimiento  -->
    <div class="tab-pane fade in active" id="agregar">
        <div style="width:100%;margin-bottom: 10px;"class="porcentajes">
            <div class="porc_data">
                <label style="font-size: 15.2px;">En este formulario se registran mantenimientos y refacciones a inmuebles</label>
            </div>
        </div>
        <form style="overflow: visible;"class="<?= isset($row) ? 'edit_form' : 'add_form' ?>" action="javascript:;" onsubmit="request_post_cuenta('<?= site_url('manager/save_mantenimientos') ?>',this,'.contenedor_centro');return false;" enctype="multipart/form-data"> 
            <div class="domicilio">
                <label style="color: gray;float: left;font-size: 14px;">Partes intervinientes</label><span style="border: 1px solid;color: gray;float: right;margin-top: 13px;width: 64%;"></span>
                <input name="mant_id" type="hidden" value="<?= (isset($row) && $row->mant_id ) ? $row->mant_id : '' ?>"/>
                <input value="<?= (isset($row) && $row->mant_domicilio ) ? $row->mant_domicilio : '' ?>" type="text" id="mant_domicilio" name="mant_domicilio" class="form-control ui-autocomplete-input"  placeholder="Domicilio" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="clear: both;margin-bottom: 5px;margin-right: 5px;font-size: 16px;width: 403px;float: left;">            
                <input value="<?= (isset($row) && $row->mant_prop ) ? $row->mant_prop : '' ?>" type="text" id="mant_prop" name="mant_prop" class="form-control ui-autocomplete-input" placeholder="Propietario" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="clear: both;margin-bottom: 5px;margin-right: 5px;font-size: 16px;width: 403px;float: left;">
                <input value="<?= (isset($row) && $row->mant_inq ) ? $row->mant_inq : '' ?>" type="text" id="mant_inq" name="mant_inq" class="form-control ui-autocomplete-input" placeholder="Inquilino" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="clear: both;margin-bottom: 5px;margin-right: 5px;font-size: 16px;width: 403px;float: left;">
                <input value="<?= (isset($row) && $row->mant_prov_1 ) ? $row->mant_prov_1 : '' ?>" type="text" id="mant_prov_1" name="mant_prov_1" class="form-control ui-autocomplete-input" placeholder="Proveedor 1" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="clear: both;margin-bottom: 5px;margin-right: 5px;font-size: 16px;width: 403px;float: left;">
                <input value="<?= (isset($row) && $row->mant_prov_2 ) ? $row->mant_prov_2 : '' ?>" type="text" id="mant_prov_2" name="mant_prov_2" class="form-control ui-autocomplete-input" placeholder="Proveedor 2" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="clear: both;margin-bottom: 5px;margin-right: 5px;font-size: 16px;width: 403px;float: left;">
                <input value="<?= (isset($row) && $row->mant_prov_3 ) ? $row->mant_prov_3 : '' ?>" type="text" id="mant_prov_3" name="mant_prov_3" class="form-control ui-autocomplete-input" placeholder="Proveedor 3" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="clear: both;margin-bottom: 5px;margin-right: 5px;font-size: 16px;width: 403px;float: left;">
            </div>
            <div class="domicilio">
                <label style="color: gray;float: left;font-size: 14px;">Datos del mantenimiento</label><span style="border: 1px solid;color: gray;float: right;margin-top: 13px;width: 57%;"></span>
                <textarea class="desc_mant" name="mant_desc" type="text" placeholder="Descripción de la tarea" ><?= (isset($row) && $row->mant_desc ) ? $row->mant_desc : '' ?></textarea>     
                <input value="<?= (isset($row) && $row->mant_monto ) ? $row->mant_monto : '' ?>" type="text" name="mant_monto" class="form-control ui-autocomplete-input" placeholder="Presupuesto ($)" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="clear: both;margin-bottom: 5px;margin-right: 5px;font-size: 16px;width: 403px;float: left;">
            </div>
            <div class="domicilio">
                <label style="color: gray;float: left;font-size: 14px;">Fecha límite</label><span style="border: 1px solid;color: gray;float: right;margin-top: 13px;width: 78%;"></span>
                <input value="<?= (isset($row) && $row->mant_date_deadline ) ? $row->mant_date_deadline : '' ?>" name="mant_date_deadline" id="mant_date_deadline" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" class="form-control ui-autocomplete-input" placeholder="Fecha limite" type="text"  style="margin-bottom: 5px;margin-right: 5px;font-size: 16px;width: 400px;float: left;">
            </div>   
            <div class="domicilio">
                <label style="color: gray;float: left;font-size: 14px;">Fecha de terminación</label><span style="border: 1px solid;color: gray;float: right;margin-top: 13px;width: 63%;"></span>
                <input value="<?= (isset($row) && $row->mant_date_end ) ? $row->mant_date_end : '' ?>"name="mant_date_end" id="mant_date_end"  class="form-control ui-autocomplete-input" placeholder="Fecha de fin" type="text" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true"  style="margin-bottom: 5px;margin-right: 5px;font-size: 16px;width: 400px;float: left;">
            </div>
            <div class="domicilio">
                <label style="color: gray;float: left;font-size: 14px;clear:both">Prioridad</label><span style="border: 1px solid;color: gray;float: right;margin-top: 16px;width: 83%;"></span>
                <select name="mant_prioridad" style="clear: both;float: left;margin-top: 5px;">
                    <option <?= (isset($row) && $row->mant_prioridad == 1 ) ? 'selected="selected"' : '' ?> value="1">Alta</option>
                    <option <?= (isset($row) && $row->mant_prioridad == 2 ) ? 'selected="selected"' : '' ?> value="2">Media</option>
                    <option <?= (isset($row) && $row->mant_prioridad == 3 ) ? 'selected="selected"' : '' ?> value="3">Baja</option>
                </select>
            </div>
            <div class="domicilio">
                <label style="color: gray;float: left;font-size: 14px;clear:both">Status</label><span style="border: 1px solid;color: gray;float: right;margin-top: 16px;width: 88%;"></span>
                <select name="mant_status" style="clear: both;float: left;margin-top: 5px;">
                    <option <?= (isset($row) && $row->mant_status == 1 ) ? 'selected="selected"' : '' ?> value="1">Creada</option>
                    <option <?= (isset($row) && $row->mant_status == 2 ) ? 'selected="selected"' : '' ?> value="2">Asignada y en marcha</option>
                    <option <?= (isset($row) && $row->mant_status == 3 ) ? 'selected="selected"' : '' ?> value="3">Terminada</option>
                </select>
            </div>
            <div class="domicilio">
                <label style="clear: both;margin-top: 10px;color: gray;float: left;font-size: 14px;">Calificación de tarea</label><span style="border: 1px solid;color: gray;float: right;margin-top: 21px;width: 63%;"></span>
                <input value="<?= (isset($row) && $row->mant_calif ) ? $row->mant_calif : '' ?>"name="mant_calif" id="mant_calif"  class="form-control ui-autocomplete-input"  placeholder="Calificación de tarea" type="text" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true"  style="margin-bottom: 5px;margin-right: 5px;font-size: 16px;width: 400px;float: left;">
            </div>
            <div id="com_display">
                <span></span>
            </div>
            <button class="btn btn-primary" type="submit" id="buttons_cli" style="margin-bottom: 150px;clear: both;float: left;line-height: 0;margin-top: 15px;"><?= isset($row) ? 'Guardar' : 'Crear' ?></button>
            <div id="back_fader">
                <div style="width: auto;margin-left: 15px;margin-right: 15px" id="popup">
                </div>
            </div>
        </form>

    </div>
    <!--  Lista de Mantenimientos  -->
    <div class="tab-pane fade" id="lista">
        <?php echo isset($lista) ? $lista : '' ?>
    </div>
</div>
<span hidden="true" class="_prov_to_choose" data-id=""></span>




<script>
    function choose_prov(){
        request('<?= site_url() . 'choose_prov/' ?>','','#popup');      
        popup();
    }
    /*
     * RESTRINGE EL INPUT A MAYUS, MINUS, ESPACIO
     */      
    $('#mant_prop, #mant_inq, #mant_prov_1, #mant_prov_2, #mant_prov_3').keypress(function(key) {
        if((key.charCode < 97 || key.charCode > 122) && key.charCode != 0 && key.charCode != 32 && key.charCode != 8 && key.charCode != 9  && (key.charCode < 65 || key.charCode > 90)) return false;
    });
    $('#mant_prov_1, #mant_prov_2, #mant_prov_3').on('keypress keyup focus click',function(){
        $('._prov_to_choose').attr('data-id', $(this).attr('id'));
        choose_prov(); 
    });
    $('#ccnav a').click(function (e) {
        e.preventDefault()
        $(this).tab('show')
    }); 
    $( "#mant_date_deadline" ).datepicker({
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
    $( "#mant_date_end" ).datepicker({
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
    $("[id^=mant_domicilio]").each(function(){
        $(this).autocomplete(auto_opt_dom);
        $(this).keydown(key_opt_dom);   
    });
    $(function(){
        $('#mant_inq').autocomplete({
            source: "<?php echo site_url('manager/autocomplete') . '/clientes' ?>",
            select: function(event, ui) {
                $.ajax({
                    url : BASE_URL + "manager/buscar_cliente/clientes/"+ui.item.id,
                    type:'POST',
                    dataType: 'json',
                    success:function(R){
                        eval(R.js);
                        if(R.html != ''){
                            $('#mant_inq').html(R.html);
                        }
                    }
                });
            }
        });
        $("#mant_inq").keydown(function(){
            if ($("#mant_inq").val().length == 1){
                $.ajax({
                    url : BASE_URL + "manager/buscar_cliente/clientes/",
                    type:'POST',
                    dataType: 'json',
                    success:function(R){
                        eval(R.js);
                        if(R.html != ''){
                            $('#mant_inq').html(R.html);
                        }
                    }
                });
            }
        });
    });
    
    $(function(){
        $('#mant_prop').autocomplete({
            source: "<?php echo site_url('manager/autocomplete') . '/cuentas_corrientes' ?>",
            select: function(event, ui) {
                $.ajax({
                    url : BASE_URL + "manager/buscar_prop/cuentas_corrientes/"+ui.item.id,
                    type:'POST',
                    dataType: 'json',
                    success:function(R){
                        eval(R.js);
                        if(R.html != ''){
                            $('#mant_prop').html(R.html);
                            $('#mant_prop').blur();
                        }
                    }
                });
            }
        });
        $("#mant_prop").keydown(function(){
            if ($("#mant_prop").val().length == 1){
                $.ajax({
                    url : BASE_URL + "manager/buscar_prop/cuentas_corrientes/",
                    type:'POST',
                    dataType: 'json',
                    success:function(R){
                        eval(R.js);
                        if(R.html != ''){
                            $('#mant_prop').html(R.html);
                        }
                    }
                });
            }
        });
    });
</script>
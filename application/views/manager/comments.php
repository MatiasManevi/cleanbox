<div class="comments" style="width: 50%;">
    <div style="width:100%;margin-bottom: 10px;"class="porcentajes">
        <div class="porc_data">
            <label style="font-size: 15.2px;">Formulario para guardar comentarios sobre alquileres y demas, acerca de un Propietario en particular</label>
        </div>
    </div><input id="auto_cc_id" name="auto_cc_id" type="hidden"/>
    <input onkeyup="validar_persona('con_prop')" onblur="validar_persona('con_prop')" id="con_prop" value="<?= (isset($row) && $row->con_prop ) ? $row->con_prop : '' ?>" type="text"name="con_prop" class="form-control ui-autocomplete-input"  placeholder="Propietario" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="clear: both;margin-bottom: 5px;margin-right: 5px;font-size: 16px;width: 400px;float: left;">
    <input id="auto_inm_id" name="auto_inm_id" type="hidden"/>
    <input onkeyup="validar_inm('prop_domi')" onblur="validar_inm('prop_domi')" id="prop_domi" type="text" name="com_dom" value="<?= (isset($row) && $row->com_dom ) ? $row->com_dom : '' ?>"class="form-control ui-autocomplete-input"  placeholder="Domicilio de propiedad" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="margin-top: 9px;clear: both;margin-bottom: 15px;margin-right: 5px;font-size: 16px;width: 400px;float: left;">    
    <textarea id="coment" placeholder="Comentarios"></textarea>
    <button class="btn btn-primary" id="save_coment" style="margin-top: 10px;float: left; line-height: 0;">Guardar</button>
    <div id="com_display"></div>
</div>
<script>
    $('#coment').keypress(function(key) {
        if((key.charCode < 48 || key.charCode > 57) && (key.charCode < 97 || key.charCode > 122) && key.charCode != 0 && key.charCode != 46 && key.charCode != 32 && key.charCode != 8 && key.charCode != 9  && (key.charCode < 65 || key.charCode > 90)) return false;
    });
    $("#save_coment").click(function() {
        var id = 0;
        var prop = $('#con_prop').val();    
        var domicilio = $('#prop_domi').val();    
        var id = $('#auto_cc_id').val();    
        var id_inm = $('#auto_inm_id').val();    
        var coment = $('#coment').val();    
        request('<?= site_url() . 'save_coment/' ?>'+id_inm+'/'+id+'/'+prop+'/'+coment+'/'+domicilio,'','.contenedor_centro');
        
    });
    function validar_inm(id_input){       
        var value = $('#'+id_input).val();
        request('<?= site_url('validate') ?>/prop_id/prop_dom/propiedades/auto_inm_id/'+id_input+'/'+value,'','#auto_inm_id');          
    }
    function validar_persona(id_input){       
        var value = $('#'+id_input).val();
        request('<?= site_url('validate') ?>/cc_id/cc_prop/cuentas_corrientes/auto_cc_id/'+id_input+'/'+value,'','#auto_cc_id');          
    }
    $(function(){
        $('#con_prop').autocomplete({
            source: "<?php echo site_url('manager/autocomplete') . '/cuentas_corrientes' ?>",
            select: function(event, ui) {
                $.ajax({
                    url : BASE_URL + "manager/buscar_prop/cuentas_corrientes/"+ui.item.id,
                    type:'POST',
                    dataType: 'json',
                    success:function(R){
                        eval(R.js);
                        if(R.html != ''){
                            $('#con_prop').html(R.html);
                            $('#con_prop').blur();
                        }
                    }
                });
            }
        });
        $("#con_prop").keydown(function(){
            if ($("#con_prop").val().length == 1){
                $.ajax({
                    url : BASE_URL + "manager/buscar_prop/cuentas_corrientes/",
                    type:'POST',
                    dataType: 'json',
                    success:function(R){
                        eval(R.js);
                        if(R.html != ''){
                            $('#con_prop').html(R.html);
                        }
                    }
                });
            }
        });
    }); 
   
    $('#prop_domi').autocomplete({
        source: "<?php echo site_url('manager/autocomplete') . '/propiedades/' ?>" ,
        select: function(event, ui) {
            $.ajax({
                url : BASE_URL + "manager/buscar_propiedad_prop/propiedades/"+ui.item.id,
                type:'POST',
                dataType: 'json',
                success:function(R){
                    eval(R.js);
                    if(R.html != ''){
                        $('#prop_domi').html(R.html);
                        $('#prop_domi').blur();
                    }
                }
            });
        }
    });
    $("#prop_domi").keydown(function(){
        if ($("#prop_domi").val().length == 1){
            $.ajax({
                url : BASE_URL + "manager/buscar_propiedad_prop/propiedades/",
                type:'POST',
                dataType: 'json',
                success:function(R){
                    eval(R.js);
                    if(R.html != ''){
                        $('#prop_domi').html(R.html);
                    }
                }
            });
        }
    });
 
</script>
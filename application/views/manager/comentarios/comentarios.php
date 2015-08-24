<style>
    #ccnav a:hover{
        color:black;
    }
</style>    
<h2><?= t('Comentarios') ?></h2>
<ul id="ccnav" class="nav nav-tabs">
    <li class="active"><a href="#agregar" data-toggle="tab">Agregar Nuevo</a></li>
    <li><a href="#lista" data-toggle="tab">Lista de Comentarios</a></li>
</ul>

<div class="tab-content">
    <!--  Crear Cliente  -->
    <div class="tab-pane fade in active" id="agregar">
        <form style="overflow: visible;"class="<?= isset($row) ? 'edit_form' : 'add_form' ?>" action="javascript:;" onsubmit="request_post_cuenta('<?= site_url('manager/save_comentarios') ?>',this,'.contenedor_centro');return false;" enctype="multipart/form-data"> 
            <div style="margin-top:0px;" class="comments">
                <div style="width:100%;margin-bottom: 10px;"class="porcentajes">
                    <div class="porc_data">
                        <label style="font-size: 15.2px;">Formulario para guardar comentarios sobre alquileres y demas, acerca de un Propietario en particular</label>
                    </div>
                </div>
                <input onkeyup="validar_persona('con_prop')" onblur="validar_persona('con_prop')" id="con_prop" value="<?= (isset($row) && $row->com_prop ) ? $row->com_prop : '' ?>" type="text" name="com_prop" class="form-control ui-autocomplete-input"  placeholder="Propietario" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="clear: both;margin-bottom: 5px;margin-right: 5px;font-size: 16px;width: 400px;float: left;">              
                <input id="auto_cc_id" name="auto_cc_id" type="hidden"/>
                <input id="auto_inm_id" name="auto_inm_id" type="hidden"/>
                <input onkeyup="validar_inm('prop_domi')" onblur="validar_inm('prop_domi')" id="prop_domi" type="text" name="com_dom" value="<?= (isset($row) && $row->com_dom ) ? $row->com_dom : '' ?>"class="form-control ui-autocomplete-input"  placeholder="Domicilio de propiedad" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="margin-top: 9px;clear: both;margin-bottom: 15px;margin-right: 5px;font-size: 16px;width: 400px;float: left;">    
                <textarea name="com_com" id="coment" placeholder="Comentarios"><?= (isset($row) && $row->com_com ) ? $row->com_com : '' ?></textarea>

                <input id="com_date" type="hidden" value="<?= (isset($row) && $row->com_date ) ? $row->com_date : date('d-m-Y') ?>" name="com_date"/>
                <input id="com_mes" type="hidden" value="<?= (isset($row) && $row->com_mes ) ? $row->com_mes : date('m') ?>" name="com_mes"/>
                <input id="com_ano" type="hidden" value="<?= (isset($row) && $row->com_ano ) ? $row->com_ano : date('Y') ?>" name="com_ano"/>
                <input id="com_id" type="hidden" value="<?= (isset($row) && $row->com_id ) ? $row->com_id : '' ?>" name="com_id"/>
                <button class="btn btn-primary" id="save_coment" style="float: left; line-height: 0;">Guardar</button>
                <div id="com_display">
                    <span></span>
                </div>
            </div>

        </form>
    </div>
    <!--  Lista de Clientes  -->
    <div class="tab-pane fade" id="lista">
        <?= isset($lista) ? $lista : '' ?>
    </div>
</div>




<script>
    $('#ccnav a').click(function (e) {
        e.preventDefault()
        $(this).tab('show')
    }) 
    $('#coment').keypress(function(key) {
        if((key.charCode < 48 || key.charCode > 57) && (key.charCode < 97 || key.charCode > 122) && key.charCode != 0 && key.charCode != 46 && key.charCode != 32 && key.charCode != 8 && key.charCode != 9  && (key.charCode < 65 || key.charCode > 90)) return false;
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
    $(function(){
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
    }); 
</script>
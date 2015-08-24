<style>
    #ccnav a:hover{
        color:black;
    }
</style>    
<h2><?= t('Propiedades') ?></h2>
<ul id="ccnav" class="nav nav-tabs">
    <li class="active"><a href="#agregar" data-toggle="tab">Agregar Nueva</a></li>
    <li><a href="#lista" data-toggle="tab">Lista de Propiedades</a></li>

</ul>

<div class="tab-content">
    <!--  Crear Cuenta  -->
    <div class="tab-pane fade in active" id="agregar">
        <form style="overflow: visible;"class="<?= isset($row) ? 'edit_form' : 'add_form' ?>" action="javascript:;" onsubmit="request_post_cuenta('<?= site_url('manager/save_propiedad') ?>',this,'.contenedor_centro');return false;" enctype="multipart/form-data"> 
            <input id="auto_cc_id" name="auto_cc_id" value="<?= (isset($id_prop)) ? $id_prop : '' ?>" type="hidden"/>
            <input id="auto_depo_id" name="auto_depo_id" type="hidden" value="<?= (isset($id_inq) ) ? $id_inq : '1' ?>"/>
            <input onkeyup="validar_cc('propietario')" onblur="validar_cc('propietario')" value="<?= (isset($row) && $row->prop_prop ) ? $row->prop_prop : '' ?>"type="text" name="prop_prop" class="form-control ui-autocomplete-input" id="propietario" placeholder="Propietario" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="margin-right: 5px;font-size: 16px;width: 403px;float: left;">
            <input value="<?= (isset($row) && $row->prop_dom ) ? $row->prop_dom : '' ?>"type="text" name="prop_dom" class="form-control ui-autocomplete-input" id="prop_dom" placeholder="Domicilio de propiedad" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="margin-top: 5px;clear:both;margin-right: 5px;font-size: 16px;width: 403px;float: left;">
            <label style="clear: both;float: left;font-size: 14px;margin-right: 6px;margin-top: 13px;">En contrato vigente con el: </label><input onkeyup="validar_cliente('inquilino')" onblur="validar_cliente('inquilino')" value="<?= (isset($row) && $row->prop_contrato_vigente ) ? $row->prop_contrato_vigente : '' ?>"type="text" name="prop_contrato_vigente" class="form-control ui-autocomplete-input" id="inquilino" placeholder="Inquilino" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="clear: both;margin-top: 6px;margin-right: 5px;font-size: 16px;width: 403px;float: left;">
            <input value="<?= (isset($row) && $row->prop_id ) ? $row->prop_id : '0' ?>" name="prop_id" type="hidden" />
            <button class="btn btn-primary" type="submit" id="buttons_cli" style="margin-top: 6px;float: left; line-height: 0;"><?= isset($row) ? 'Guardar' : 'Crear' ?></button>
            <div id="com_display">
                <span></span>
            </div>
        </form>

    </div>

    <!--  Lista de Cuentas  -->
    <div class="tab-pane fade" id="lista">
        <?= isset($lista) ? $lista : '' ?>
    </div>
</div>
<script>
    $('#ccnav a').click(function (e) {
        e.preventDefault()
        $(this).tab('show')
    })  
    function validar_cc(id_input){       
        var value = $('#'+id_input).html();
        var value = $('#'+id_input).val();
        request('<?= site_url('validate') ?>/cc_id/cc_prop/cuentas_corrientes/auto_cc_id/'+id_input+'/'+value,'','#auto_cc_id')          
    }
    function validar_cliente(id_input){       
        var value = $('#'+id_input).html();
        var value = $('#'+id_input).val();
        request('<?= site_url('validate') ?>/client_id/client_name/clientes/auto_depo_id/'+id_input+'/'+value,'','#auto_depo_id')          
    }
    $(function(){
        $('#inquilino').autocomplete({
            source: "<?php echo site_url('manager/autocomplete') . '/clientes' ?>",
            select: function(event, ui) {
                $.ajax({
                    url : BASE_URL + "manager/buscar_cliente/clientes/"+ui.item.id,
                    type:'POST',
                    dataType: 'json',
                    success:function(R){
                        eval(R.js);
                        if(R.html != ''){
                            $('#inquilino').html(R.html);
                            $('#inquilino').blur();
                        }
                    }
                });
            }
        });
        $("#inquilino").keydown(function(){
            if ($("#inquilino").val().length == 1){
                $.ajax({
                    url : BASE_URL + "manager/buscar_cliente/clientes/",
                    type:'POST',
                    dataType: 'json',
                    success:function(R){
                        eval(R.js);
                        if(R.html != ''){
                            $('#inquilino').html(R.html);
                        }
                    }
                });
            }
        });
    });
    $(function(){
        $('#propietario').autocomplete({
            source: "<?php echo site_url('manager/autocomplete') . '/cuentas_corrientes' ?>",
            select: function(event, ui) {
                $.ajax({
                    url : BASE_URL + "manager/buscar_prop/cuentas_corrientes/"+ui.item.id,
                    type:'POST',
                    dataType: 'json',
                    success:function(R){
                        eval(R.js);
                        if(R.html != ''){
                            $('#propietario').html(R.html);
                            $('#propietario').blur();
                        }
                    }
                });
            }
        });
        $("#propietario").keydown(function(){
            if ($("#propietario").val().length == 1){
                $.ajax({
                    url : BASE_URL + "manager/buscar_prop/cuentas_corrientes/",
                    type:'POST',
                    dataType: 'json',
                    success:function(R){
                        eval(R.js);
                        if(R.html != ''){
                            $('#propietario').html(R.html);

                        }
                    }
                });
            }
        });
    });
    
</script>

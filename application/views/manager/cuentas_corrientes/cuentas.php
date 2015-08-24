<style>
    #ccnav a:hover{
        color:black;
    }
</style>    
<h2><?= t('Cuentas Corrientes') ?></h2>
<ul id="ccnav" class="nav nav-tabs">
    <li class="active"><a href="#agregar" data-toggle="tab">Agregar Nueva</a></li>
    <li><a href="#lista" data-toggle="tab">Lista de Cuentas Corrientes</a></li>
</ul>

<div class="tab-content">
    <!--  Crear Cuenta  -->
    <div class="tab-pane fade in active" id="agregar">
        <div style="width:100%;margin-bottom: 10px;"class="porcentajes">
            <div class="porc_data">
                <label style="font-size: 15.2px;">En este formulario se registran las ctas. ctes. de Propietarios</label>
            </div>
        </div>
        <div id="tooltipProp">
            El campo se autocompleta con los Clientes cargados hasta el momento, de lo contrario se creara un Cliente nuevo con el nombre ingresado
        </div>
        <form style="overflow: visible;"class="<?= isset($row) ? 'edit_form' : 'add_form' ?>" action="javascript:;" onsubmit="request_post_cuenta('<?= site_url('manager/save_cuenta') ?>',this,'.contenedor_centro');return false;" enctype="multipart/form-data"> 
            <input value="<?= (isset($row) && $row->cc_prop ) ? $row->cc_prop : '' ?>"type="text" name="cc_prop" class="form-control ui-autocomplete-input" id="propietario" placeholder="Propietario" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="margin-right: 5px;font-size: 16px;width: 403px;float: left;">
            <input value="<?= (isset($row) && $row->cc_saldo ) ? $row->cc_saldo : '' ?>"type="text" name="cc_saldo" class="form-control ui-autocomplete-input" id="saldo" placeholder="Saldo Cta. Principal" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="margin-top: 5px;clear:both;margin-right: 5px;font-size: 16px;width: 403px;float: left;">
            <input value="<?= (isset($row) && $row->cc_varios ) ? $row->cc_varios : '' ?>"type="text" name="cc_varios" class="form-control ui-autocomplete-input" id="varios" placeholder="Saldo Cta. Secundaria" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="margin-top: 5px;clear:both;margin-right: 5px;font-size: 16px;width: 403px;float: left;">
            <input value="<?= (isset($row) && $row->cc_id ) ? $row->cc_id : '0' ?>" name="cc_id" type="hidden" />
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
    $(document).ready(function(){
        $('#propietario').hover(function(){
            $('#tooltipProp').css('display','block');
        },function(){
            $('#tooltipProp').css('display','none');
        });
        $(document).mousemove(function(event){
            var mx = event.pageX;
            var my = event.pageY;
            $('#tooltipProp').css('left',mx+'px').css('right',my+'px').css('top',-20);
        })  
    });
    $('#ccnav a').click(function (e) {
        e.preventDefault()
        $(this).tab('show')
    })  
    $(function(){
        $('#propietario').autocomplete({
            source: "<?php echo site_url('manager/autocomplete') . '/clientes' ?>",
            select: function(event, ui) {
                $.ajax({
                    url : BASE_URL + "manager/buscar_cliente/clientes/"+ui.item.id,
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
        $("#propietario").keydown(function(){
            if ($("#propietario").val().length == 1){
                $.ajax({
                    url : BASE_URL + "manager/buscar_cliente/clientes/",
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

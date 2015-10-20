<style>
    #ccnav a:hover{
        color:black;
    }
</style>    
<h2><?= t('Clientes') ?></h2>
<ul id="ccnav" class="nav nav-tabs">
    <li><a href="#agregar" data-toggle="tab">Agregar Nuevo</a></li>
    <li class="active"><a href="#lista" data-toggle="tab">Lista de Clientes</a></li>
</ul>

<div class="tab-content">
    <!--  Crear Cliente  -->
    <div class="tab-pane fade" id="agregar">
        <div style="width:100%;margin-bottom: 10px;"class="porcentajes">
            <div class="porc_data">
                <label style="font-size: 15.2px;">En este formulario se registran clientes (Propietarios | Inquilinos)</label>
            </div>
        </div><form style="overflow: visible;"class="<?= isset($row) ? 'edit_form' : 'add_form' ?>" action="javascript:;" onsubmit="request_post_cuenta('<?= site_url('manager/save_clientes') ?>',this,'.contenedor_centro');return false;" enctype="multipart/form-data"> 
            <input name="client_id" type="hidden" value="<?= (isset($row) && $row->client_id ) ? $row->client_id : '' ?>"/>
            <input value="<?= (isset($row) && $row->client_name ) ? $row->client_name : '' ?>" type="text" id="client_name" name="client_name" class="form-control ui-autocomplete-input"  placeholder="Nombre" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="clear: both;margin-bottom: 5px;margin-right: 5px;font-size: 16px;width: 403px;float: left;">
            <input value="<?= (isset($row) && $row->client_cuit ) ? $row->client_cuit : '' ?>" type="text" name="client_cuit" class="form-control ui-autocomplete-input" placeholder="C.U.I.T / DNI" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="clear: both;margin-bottom: 5px;margin-right: 5px;font-size: 16px;width: 403px;float: left;">
            <input value="<?= (isset($row) && $row->client_razon_vinculo ) ? $row->client_razon_vinculo : '' ?>" type="text" name="client_razon_vinculo" class="form-control ui-autocomplete-input" placeholder="Razón de vínculo" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="clear: both;margin-bottom: 5px;margin-right: 5px;font-size: 16px;width: 403px;float: left;">
            <div class="domicilio">
                <label style="color: gray;float: left;font-size: 14px;">Contacto</label><span style="border: 1px solid;color: gray;float: right;margin-top: 13px;width: 83%;"></span>
                <input value="<?= (isset($row) && $row->client_tel ) ? $row->client_tel : '' ?>" type="text" name="client_tel" class="form-control ui-autocomplete-input" placeholder="Telefono Fijo" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="clear: both;margin-bottom: 5px;margin-right: 5px;font-size: 16px;width: 403px;float: left;">
                <input value="<?= (isset($row) && $row->client_celular ) ? $row->client_celular : '' ?>" type="text" name="client_celular" class="form-control ui-autocomplete-input" placeholder="Celular" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="clear: both;margin-bottom: 5px;margin-right: 5px;font-size: 16px;width: 403px;float: left;">
                <input id="client_email" value="<?= (isset($row) && $row->client_email ) ? $row->client_email : '' ?>" type="text" name="client_email" class="form-control ui-autocomplete-input"  placeholder="Email" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="clear: both;margin-bottom: 5px;margin-right: 5px;font-size: 16px;width: 403px;float: left;">
            </div>
            <div class="domicilio">
                <label style="color: gray;float: left;font-size: 14px;">Domicilio</label><span style="border: 1px solid;color: gray;float: right;margin-top: 13px;width: 83%;"></span>
                <input value="<?= (isset($row) && $row->client_calle ) ? $row->client_calle : '' ?>" type="text" name="client_calle" class="form-control ui-autocomplete-input" placeholder="Calle" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="clear: both;margin-bottom: 5px;margin-right: 5px;font-size: 16px;width: 403px;float: left;">
                <input value="<?= (isset($row) && $row->client_nro_calle ) ? $row->client_nro_calle : '' ?>" type="text" name="client_nro_calle" class="form-control ui-autocomplete-input" placeholder="Nro. Calle" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="clear: both;margin-bottom: 5px;margin-right: 5px;font-size: 16px;width: 403px;float: left;">
                <input value="<?= (isset($row) && $row->client_piso ) ? $row->client_piso : '' ?>" type="text" name="client_piso" class="form-control ui-autocomplete-input" placeholder="Piso" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="clear: both;margin-bottom: 5px;margin-right: 5px;font-size: 16px;width: 403px;float: left;">
                <input value="<?= (isset($row) && $row->client_dto ) ? $row->client_dto : '' ?>" type="text" name="client_dto" class="form-control ui-autocomplete-input" placeholder="Depto." autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="clear: both;margin-bottom: 5px;margin-right: 5px;font-size: 16px;width: 403px;float: left;">
                <input value="<?= (isset($row) && $row->client_postal ) ? $row->client_postal : '' ?>" type="text" name="client_postal" class="form-control ui-autocomplete-input" placeholder="Código Postal" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="clear: both;margin-bottom: 5px;margin-right: 5px;font-size: 16px;width: 403px;float: left;">
                <input value="<?= (isset($row) && $row->client_localidad ) ? $row->client_localidad : '' ?>" type="text" name="client_localidad" class="form-control ui-autocomplete-input" placeholder="Localidad" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="clear: both;margin-bottom: 5px;margin-right: 5px;font-size: 16px;width: 403px;float: left;">
                <input value="<?= (isset($row) && $row->client_provincia ) ? $row->client_provincia : '' ?>" type="text" name="client_provincia" class="form-control ui-autocomplete-input" placeholder="Provincia" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="clear: both;margin-bottom: 5px;margin-right: 5px;font-size: 16px;width: 403px;float: left;">
                <span style="margin-top: 13px;margin-bottom: 13px;clear: both;float: left;width: 100%;border: 1px solid;color: gray;"></span>
            </div>
            <textarea style="clear:both;width: 35%;float: left;" type="text" placeholder="Comentarios" name="client_comentario"><?= (isset($row) && $row->client_comentario ) ? $row->client_comentario : '' ?></textarea>
            <div style="clear: both;float: left; margin-top: 6px;">
                <span>Categoria</span>
                <select class="form-control ui-autocomplete-input" name="client_categoria">
                    <option class="form-control ui-autocomplete-input" <?= (isset($row) && $row->client_categoria == 'Propietario' ? 'selected="selected"' : '' ) ?> value="Propietario">Propietario</option>
                    <option class="form-control ui-autocomplete-input" <?= (isset($row) && $row->client_categoria == 'Inquilino' ? 'selected="selected"' : '' ) ?> value="Inquilino">Inquilino</option>
                    <option class="form-control ui-autocomplete-input" <?= (isset($row) && $row->client_categoria == 'Garante' ? 'selected="selected"' : '' ) ?> value="Garante">Garante</option>
                </select>
            </div> 
            <div id="com_display">
                <span></span>
            </div>
            <button class="btn btn-primary" type="submit" id="buttons_cli" style="margin-bottom: 15px;clear: both;float: left;line-height: 0;margin-top: 15px;"><?= isset($row) ? 'Guardar' : 'Crear' ?></button>
        </form>

    </div>

    <div class="tab-pane fade in active" id="lista">
        <div style="margin-top:20px;margin-bottom: 2px;" class="actions_container">
            <input type="text" style="margin-right: 5px; font-size: 16px; width: 403px; float: left; " aria-haspopup="true" aria-autocomplete="list" role="textbox" autocomplete="off" placeholder="Buscar Cliente" id="auto_personas1" class="form-control ui-autocomplete-input" name="cc_prop">
            <a style=" margin-top: 8px;" id="refresh" href="javascript:;" class="glyphicon glyphicon-refresh" onclick="refrescar()"></a>
        </div>
        <table class="table table-hover">
            <tr>    
                <th>Nombre</th>
                <th>Email</th>
                <th>Telefono Fijo</th>
                <th>Celular</th>
                <th>C.U.I.T</th>
                <th>Domicilio</th>
                <th>Acciones</th>
            </tr>
            <?
            if ($clientes->num_rows() > 0) {
                foreach ($clientes->result() as $client) {
                    echo '<tr class="reg_' . $client->client_id . '">';
                    echo '<td>' . $client->client_name . '</td>';
                    echo '<td>' . $client->client_email . '</td>';
                    echo '<td>' . $client->client_tel . '</td>';
                    echo '<td>' . $client->client_celular . '</td>';
                    echo '<td>' . $client->client_cuit . '</td>';
                    echo '<td>' . $client->client_calle . ' ' . $client->client_nro_calle . '</td>';
                    echo '<td>';

                    echo '<a href="javascript:;" class="glyphicon glyphicon-edit" onclick="load_edit(\'' . site_url('manager/load_edit_clientes/' . $client->client_id) . '\')"></a> | ';
                    echo '<a href="javascript:;" class="glyphicon glyphicon-trash" onclick="del(\'' . $client->client_id . '\',\'' . site_url('manager/del_clientes/' . $client->client_id) . '\')"></a>  ';
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
    function refrescar(){
        request('<?= site_url() . 'refresh/' ?>clientes/client_id/client_name','','.contenedor_centro');
    }
    $('#ccnav a').click(function (e) {
        e.preventDefault()
        $(this).tab('show')
    }) 
    $(function(){
        $('#auto_personas1').autocomplete({
            source: "<?php echo site_url('manager/autocomplete') . '/clientes' ?>",
            select: function(event, ui) {
                $.ajax({
                    url : BASE_URL + "manager/buscar_fila/clientes/"+ui.item.id,
                    type:'POST',
                    dataType: 'json',
                    success:function(R){
                        eval(R.js);
                        if(R.html != ''){
                            $('.contenedor_centro').html(R.html);
                        }
                    }
                });
            }
        });
        $("#auto_personas1").keydown(function(){
            if ($("#auto_personas1").val().length == 1){
                $.ajax({
                    url : BASE_URL + "manager/buscar_fila/clientes/",
                    type:'POST',
                    dataType: 'json',
                    success:function(R){
                        eval(R.js);
                        if(R.html != ''){
                            $('').html(R.html);
                        }
                    }
                });
            }
        });
    });   
    /*
     * RESTRINGE EL INPUT A MAYUS, MINUS, ESPACIO
     */      
    $('#client_name').keypress(function(key) {
        if((key.charCode < 97 || key.charCode > 122) && key.charCode != 0 && key.charCode != 32 && key.charCode != 8 && key.charCode != 9  && (key.charCode < 65 || key.charCode > 90)) return false;
    });
    $('#client_celular').keypress(function(key) {
        if((key.charCode < 48 || key.charCode > 57) && key.charCode != 0 && key.charCode != 8 && key.charCode != 9) return false;
    });
    $('#client_tel').keypress(function(key) {
        if((key.charCode < 48 || key.charCode > 57) && key.charCode != 0 && key.charCode != 8 && key.charCode != 9) return false;
    });
    $('#client_postal').keypress(function(key) {
        if((key.charCode < 48 || key.charCode > 57) && key.charCode != 0 && key.charCode != 8 && key.charCode != 9) return false;
    });
    $('#client_nro_calle').keypress(function(key) {
        if((key.charCode < 48 || key.charCode > 57) && key.charCode != 0 && key.charCode != 8 && key.charCode != 9) return false;
    });
</script>
<style>
    #ccnav a:hover{
        color:black;
    }
</style>    
<h2><?= t('Contratos') ?></h2>
<ul id="ccnav" class="nav nav-tabs">
    <li><a href="#agregar" data-toggle="tab">Agregar Nuevo</a></li>
    <li class="active"><a href="#lista" data-toggle="tab">Lista de Contratos</a></li>
</ul>

<div class="tab-content">
    <!--  Crear Cliente  -->
    <div class="tab-pane fade" id="agregar">
        <div id="tooltipInt">
            Ej: 0.07 para indicar 7%
        </div>
        <div id="tooltipInq">
            Los campos Inquilino y Garantes se autocompletan con los Clientes cargados hasta el momento, de lo contrario se creara uno nuevo con el nombre ingresado
        </div>
        <div id="tooltipProp">
            El campo se autocompleta con las Ctas. Ctes. cargadas hasta el momento, de lo contrario se creara una nueva con el nombre ingresado
        </div>
        <form style="overflow: visible;"class="<?= isset($row) ? 'edit_form' : 'add_form' ?>" action="javascript:;" onsubmit="request_post_cuenta('<?= site_url('manager/save_contratos') ?>',this,'.contenedor_centro');return false;" enctype="multipart/form-data"> 
            <div style="width:100%;margin-bottom: 13px;margin-top: -11px;"class="porcentajes">
                <div class="porc_data">
                    <label style="font-size: 15.2px;">En este formulario se registran los contratos a gestionar. Aviso: los contratos pierden su vigencia en el sistema una vez alcanzada la Fecha de Vencimiento</label>
                </div>
                <div class="porc_data">
                    <label style="font-size: 15.2px;">El campo Propietario se autocompleta con las Ctas. Ctes. cargadas hasta el momento, de lo contrario se creara una nueva con el nombre ingresado
                    </label>
                </div>
                <div class="porc_data">
                    <label style="font-size: 15.2px;">Los campos Inquilino y Garantes se autocompletan con los Clientes cargados hasta el momento, de lo contrario se creara uno nuevo con el nombre ingresado
                    </label>
                </div>
                <div class="porc_data">
                    <label style="font-size: 15.2px;">El campo Domicilio Inmueble se autocompleta con las Propiedades cargadas hasta el momento, de lo contrario se creara una nueva con la info. ingresada</label>
                </div>
            </div>
            <input type="hidden" name="con_id" value="<?= isset($row) ? $row->con_id : '' ?>">   
            <input id="con_prop" value="<?= (isset($row) && $row->con_prop ) ? $row->con_prop : '' ?>" type="text"name="con_prop" class="form-control ui-autocomplete-input"  placeholder="Propietario" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="clear: both;margin-bottom: 5px;margin-right: 5px;font-size: 16px;width: 400px;float: left;">
            <input id="con_inq" value="<?= (isset($row) && $row->con_inq ) ? $row->con_inq : '' ?>" type="text" id="con_inq" name="con_inq" class="form-control ui-autocomplete-input"  placeholder="Inquilino" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="margin-bottom: 5px;margin-right: 5px;font-size: 16px;width: 400px;float: left;">                     
            <input id="con_gar1" value="<?= (isset($row) && $row->con_gar1 ) ? $row->con_gar1 : '' ?>" type="text" name="con_gar1" class="form-control ui-autocomplete-input"  placeholder="Garante 1" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="clear:both;margin-bottom: 5px;margin-right: 5px;font-size: 16px;width: 400px;float: left;">                     
            <input id="con_gar2" value="<?= (isset($row) && $row->con_gar2 ) ? $row->con_gar2 : '' ?>" type="text" name="con_gar2" class="form-control ui-autocomplete-input"  placeholder="Garante 2" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="margin-bottom: 5px;margin-right: 5px;font-size: 16px;width: 400px;float: left;">                     
            <input id="con_venc" value="<?= (isset($row) && $row->con_venc ) ? $row->con_venc : '' ?>" type="text" name="con_venc" class="form-control ui-autocomplete-input" placeholder="Fecha Vencimiento" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="clear: both;float: left;font-size: 16px;margin-bottom: 19px;margin-right: 5px;margin-top: 19px;width: 196px;">
            <input id="con_domi" value="<?= (isset($row) && $row->con_domi ) ? $row->con_domi : '' ?>" type="text" name="con_domi" class="form-control ui-autocomplete-input" placeholder="Domicilio Inmueble" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="float: left;font-size: 16px;margin-bottom: 19px;margin-right: 5px;margin-top: 19px;width: 250px;">
            <div style="width: 144px;float: left; margin-left: 7px;">
                <span>Tolerancia Mora (Días)</span>
                <input id="con_tolerancia" value="<?= (isset($row) && $row->con_tolerancia ) ? $row->con_tolerancia : '' ?>" type="text" name="con_tolerancia" class="form-control ui-autocomplete-input" placeholder="Tolerancia" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="float: left;font-size: 16px;margin-left: 28px;width: 83px;">
            </div>
            <div style="float: left; margin-left: 7px;">
                <span>Activo</span>
                <select class="form-control ui-autocomplete-input" name="con_enabled">
                    <option class="form-control ui-autocomplete-input" <?= (isset($row) && $row->con_enabled == '1' ? 'selected="selected"' : '' ) ?> value="1">Si</option>
                    <option class="form-control ui-autocomplete-input" <?= (isset($row) && $row->con_enabled == '0' ? 'selected="selected"' : '' ) ?> value="0">No</option>
                </select>
            </div> 
            <div style="float: left; margin-left: 7px;">
                <span>Motivo de Vencimiento o Extensión</span>
                <select class="form-control ui-autocomplete-input" name="con_motivo">
                    <option class="form-control ui-autocomplete-input" <?= (isset($row) && $row->con_motivo == '' ? 'selected="selected"' : '' ) ?> value=""></option>
                    <option class="form-control ui-autocomplete-input" <?= (isset($row) && $row->con_motivo == 'Vencido' ? 'selected="selected"' : '' ) ?> value="Vencido">Vencido</option>
                    <option class="form-control ui-autocomplete-input" <?= (isset($row) && $row->con_motivo == 'Recindido' ? 'selected="selected"' : '' ) ?> value="Recindido">Recindido</option>
                    <option class="form-control ui-autocomplete-input" <?= (isset($row) && $row->con_motivo == 'Prorrogado' ? 'selected="selected"' : '' ) ?> value="Prorrogado">Prorrogado</option>
                </select>
            </div> 
            <div style="float: left;clear: both;margin-bottom: 10px;">
                <span>Tipo Contrato</span>
                <select class="form-control ui-autocomplete-input" name="con_tipo">
                    <option onclick="$(this).parent().find('#selected').removeAttr('id');$(this).attr('id','selected')"class="form-control ui-autocomplete-input" <?= (isset($row) && $row->con_tipo == 'Alquiler' ? 'selected="selected"' : '' ) ?> value="Alquiler">Alquiler</option>
                    <option onclick="$(this).parent().find('#selected').removeAttr('id');$(this).attr('id','selected')"class="form-control ui-autocomplete-input" <?= (isset($row) && $row->con_tipo == 'Loteo' ? 'selected="selected"' : '' ) ?>  value="Loteo">Loteo</option>
                    <option onclick="$(this).parent().find('#selected').removeAttr('id');$(this).attr('id','selected')"class="form-control ui-autocomplete-input" <?= (isset($row) && $row->con_tipo == 'Alquiler Comercial' ? 'selected="selected"' : '' ) ?>  value="Alquiler Comercial">Alquiler Comercial</option>
                </select>
            </div>  
            <div style="float: left; margin-left: 7px;">
                <span>Incluye IVA/Comision</span>
                <select class="form-control ui-autocomplete-input" name="con_iva">
                    <option class="form-control ui-autocomplete-input" <?= (isset($row) && $row->con_iva == 'Si' ? 'selected="selected"' : '' ) ?> value="Si">Si</option>
                    <option class="form-control ui-autocomplete-input" <?= (isset($row) && $row->con_iva == 'No' ? 'selected="selected"' : '' ) ?> value="No">No</option>
                </select>
            </div> 
            <div style="float: left; margin-left: 7px;">
                <span>Incluye IVA/Alquiler</span>
                <select class="form-control ui-autocomplete-input" name="con_iva_alq">
                    <option class="form-control ui-autocomplete-input" <?= (isset($row) && $row->con_iva_alq == 'Si' ? 'selected="selected"' : '' ) ?> value="Si">Si</option>
                    <option class="form-control ui-autocomplete-input" <?= (isset($row) && $row->con_iva_alq == 'No' ? 'selected="selected"' : '' ) ?> value="No">No</option>
                </select>
            </div> 
            <input id="con_porc" value="<?= (isset($row)) ? $row->con_porc : '' ?>" type="text" name="con_porc" class="form-control ui-autocomplete-input" placeholder="Porc. Gestion de Cobro" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="clear: both;margin-bottom: 5px;margin-right: 5px;font-size: 16px;width: 181px;float: left;">
            <input id="con_punitorio" value="<?= (isset($row)) ? $row->con_punitorio : '' ?>" type="text" name="con_punitorio" class="form-control ui-autocomplete-input" placeholder="Porc. Int. Punitorio" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="clear: both;margin-bottom: 5px;margin-right: 5px;font-size: 16px;width: 181px;float: left;">

            <div style="float: left;width: 100%;">
                <label style="font-size: 16px; margin-left: 4px;">Periodos</label>
                <div id="relleno_periodo">
                    <?= isset($periodos_loader) ? $periodos_loader : '' ?>
                </div>
                <span style="float: left;" onclick="agregar_periodo()" type="button" class="btn btn-default btn-lg">
                    <a style="text-decoration: none"class="glyphicon glyphicon-plus-sign"></a> Agregar
                </span>
            </div>

            <div style="float: left;width: 100%;">
                <label style="font-size: 16px; margin-left: 4px;">Servicios</label>
                <div id="relleno">
                    <?= isset($servicios_loader) ? $servicios_loader : '' ?>
                </div>
                <span style="float: left;" onclick="agregar()" type="button" class="btn btn-default btn-lg">
                    <a style="text-decoration: none"class="glyphicon glyphicon-plus-sign"></a> Agregar
                </span>
            </div>


            <button class="btn btn-primary" type="submit" id="buttons_cli" style="margin-top: 35px;float: left; line-height: 0;"><?= isset($row) ? 'Guardar' : 'Crear' ?></button>           
            <div id="com_display">
                <span></span>
            </div>

            <input type="hidden" name="cant_bloques" id="cant_bloques" value="<?= isset($num_servicios) ? $num_servicios : '0' ?>">
            <input type="hidden" name="cant_bloques_periodo" id="cant_bloques_periodo" value="<?= isset($num_periodos) ? $num_periodos : '0' ?>">
        </form>

    </div>
    <div class="tab-pane fade in active" id="lista">
        <div class="actions_container">
            <input type="text" style="margin-right: 5px; font-size: 16px; width: 403px; float: left; margin-top: 5px;" aria-haspopup="true" aria-autocomplete="list" role="textbox" autocomplete="off" placeholder="Propietario" id="auto_personas1" class="form-control ui-autocomplete-input" name="cc_prop">
            <a style=" margin-top: 12px;"id="refresh" href="javascript:;" class="glyphicon glyphicon-refresh" onclick="refrescar()"></a>
        </div>
        <label style="margin-right: 5px; font-size: 16px; width: 403px; float: left;">Contratos Vigentes: <?= $contratos_vigentes ?></label>

        <table class="table table-hover">
            <tr>    
                <th>Propietario</th>
                <th>Inquilino</th>
                <th>Tipo</th>
<!--                <th>Inmueble</th>-->
                <th>IVA/Alquiler</th>
                <th>IVA/Comision</th>
                <th>Activo</th>
                <th>Acciones</th>
            </tr>
            <?
            if ($contratos->num_rows() > 0) {
                foreach ($contratos->result() as $con) {
                    echo '<tr class="reg_' . $con->con_id . '">';
                    echo '<td>' . $con->con_prop . '</td>';
                    echo '<td>' . $con->con_inq . '</td>';
                    echo '<td>' . $con->con_tipo . '</td>';
//                    echo '<td>' . $con->con_domi . '</td>';
                    echo '<td>' . $con->con_iva_alq . '</td>';
                    echo '<td>' . $con->con_iva . '</td>';
                    echo '<td>' . ($con->con_enabled == 1 ? 'Si' : 'No') . '</td>';
                    echo '<td>';

                    echo '<a href="javascript:;" class="glyphicon glyphicon-edit" onclick="load_edit(\'' . site_url('manager/load_edit_contratos/' . $con->con_id) . '\')"></a> | ';
                    echo '<a href="javascript:;" class="glyphicon glyphicon-trash" onclick="modalDelete(' . $con->con_id . ')"></a>';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="100%">' . t('No records found.') . '</td></tr>';
            }
            ?>
        </table>
    </div>
</div>


<div id="deleteContrato1" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Esta seguro de eliminar este contrato?</h4>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-default _delete" data-dismiss="modal">Eliminar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->   
<script>
    function modalDelete(id){
        var url = '<?php echo site_url('manager/del_contratos/') ?>'+ '/' + id;
        var action = 'del('+id+',"'+url+'")';
        $('#deleteContrato1').find('._delete').attr('onclick',action);
        $('#deleteContrato1').modal('show');
    }
    $('#con_tolerancia').keypress(function(key) {
        if((key.charCode < 48 || key.charCode > 57) && key.charCode != 0 && key.charCode != 8 && key.charCode != 9) return false;
    });
    function refrescar(){
        request('<?= site_url() . 'refresh/' ?>contratos/con_id/con_prop','','.contenedor_centro');
    }
    $(function(){
        $('#auto_personas1').autocomplete({
            source: "<?php echo site_url('manager/autocomplete') . '/cuentas_corrientes' ?>",
            select: function(event, ui) {
                $.ajax({
                    url : BASE_URL + "manager/buscar_contrato/cuentas_corrientes/"+ui.item.id,
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
                    url : BASE_URL + "manager/buscar_contrato/cuentas_corrientes/",
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
    };
    $("[id^=periodo_i]").each(function(){
        $(this).datepicker(date_opt);
    });
    $("[id^=periodo_f]").each(function(){     
        $(this).datepicker(date_opt);
    });
    $(document).ready(function(){
        $('[id^=span_p]').each(function(){     
            $(this).attr('disabled',true);
        });
        var i = $('#cant_bloques_periodo').val();
        $('#span_p'+i).removeAttr('disabled');
        
      
        
        
        $('#con_punitorio').hover(function(){
            $('#tooltipInt').css('display','block');
        },function(){
            $('#tooltipInt').css('display','none');
        });
        $(document).mousemove(function(event){
            var mx = event.pageX;
            var my = event.pageY;
            $('#tooltipInt').css('left',mx+'px').css('right',my+'px').css('top',153);
        })  
    });
    $(document).ready(function(){
        $('#con_porc').hover(function(){
            $('#tooltipInt').css('display','block');
        },function(){
            $('#tooltipInt').css('display','none');
        });
        $(document).mousemove(function(event){
            var mx = event.pageX;
            var my = event.pageY;
            $('#tooltipInt').css('left',mx+'px').css('right',my+'px').css('top',153);
        })  
    });
    var xx = $('#cant_bloques').val();
    var canti = $('#cant_bloques').val();
    function agregar(){   
         
        xx++;
        //Creo el Bloque
        jQuery('<div/>', {
            id: 'bloque'+xx,
            'class' : 'bloque'
        }).appendTo('#relleno').hide().fadeIn(700);
        
        //Creo los inputs Servicio y Accion
        jQuery('<input/>', {
            id: 'servicio'+xx,
            name: 'servicio'+xx,
            type: 'text',
            style : 'margin-right: 5px;font-size: 16px;width: 403px;float: left;',
            'class': 'form-control ui-autocomplete-input',
            placeholder: 'Servicio'                 
        }).appendTo('#bloque'+xx);
        //Llamo al autocomplete para este nuevo input!
        $('#servicio'+xx).autocomplete({
            source: "<?php echo site_url('manager/autocomplete') . '/conceptos/cc_varios' ?>",
            select: function(event, ui) {
                $.ajax({
                    url : BASE_URL + "manager/buscar_concepto_serv/conceptos/cc_varios/"+ui.item.id,
                    type:'POST',
                    dataType: 'json',
                    success:function(R){
                        eval(R.js);
                        if(R.html != ''){
                            $('#servicio'+xx).html(R.html);
                            $('#conc_id_'+xx).val(R.id);
                        }
                    }
                });
            }
        });
        
        /* PARTE NUEVA ACCION */
        jQuery('<div/>', {
            id: 'cont_accion'+xx,
            style : 'float: left;margin-right: 19px;margin-top: 0;width:120px;'                 
        }).appendTo('#bloque'+xx);
        
        if(xx == 1){
            jQuery('<label/>', {
                id: 'cont_accion'+xx,
                html: 'Acción',
                style : 'margin-top: -26px;float:left;'                 
            }).appendTo('#cont_accion'+xx);
        }
        
        jQuery('<select/>', {
            id: 'sel_accion'+xx,
            'class':'form-control ui-autocomplete-input',
            name : 'accion'+xx,
            style : 'float:left;'                 
        }).appendTo('#cont_accion'+xx);
        
        
        jQuery('<option/>', {
            onclick: "$(this).parent().find('#selected').removeAttr('id');$(this).attr('id','selected')",
            'class':'form-control ui-autocomplete-input',
            value : 'Pagar',    
            html : 'Pagar'
        }).appendTo('#sel_accion'+xx);
        
         
        jQuery('<option/>', {
            onclick: "$(this).parent().find('#selected').removeAttr('id');$(this).attr('id','selected')",
            'class':'form-control ui-autocomplete-input',
            value : 'Controlar',    
            html : 'Controlar'
        }).appendTo('#sel_accion'+xx);
        /* PARTE NUEVA ACCION */        
        
        jQuery('<input/>', {
            name: 'serv_id_'+xx,
            id: 'serv_id_'+xx,
            type: 'hidden',
            value : '0'              
        }).appendTo('#bloque'+xx);
        
        //Creo su boton para eliminar            
        jQuery('<span/>', {
            id: 'span'+xx,
            onclick: 'removeElement('+xx+')',
            style: 'height: 34px;',
            'class' : 'btn btn-default btn-lg'
        }).appendTo('#bloque'+xx);
        
        jQuery('<a/>', {
            'class' : 'glyphicon glyphicon-minus-sign',
            style : 'text-decoration: none; margin-top: -3px;'
        }).appendTo('#span'+xx);
        
        //Aumenta el contador de servicios
        canti++;
        $("#cant_bloques").val(canti);        
    }
    
    
    var xxx = $('#cant_bloques_periodo').val(); 
    var cantii = $('#cant_bloques_periodo').val(); 
    function agregar_periodo(){   
        
        xxx++;
        //Creo el Bloque
        jQuery('<div/>', {
            id: 'bloque_p'+xxx,
            'class' : 'bloque_p'
        }).appendTo('#relleno_periodo').hide().fadeIn(700);
        
        //Creo los inputs Periodos y Montos
        jQuery('<input/>', {
            id: 'periodo_i'+xxx,
            name: 'periodo_i'+xxx,
            type: 'text',
            style : 'margin-right: 5px;font-size: 16px;width: 247px;float: left;',
            'class': 'form-control ui-autocomplete-input',
            placeholder: 'Fecha Inicio'                 
        }).appendTo('#bloque_p'+xxx);
        $('#periodo_i'+xxx).datepicker(date_opt);
        
        jQuery('<input/>', {
            id: 'periodo_f'+xxx,
            name: 'periodo_f'+xxx,
            type: 'text',
            style : 'margin-right: 5px;font-size: 16px;width: 247px;float: left;',
            'class': 'form-control ui-autocomplete-input',
            placeholder: 'Fecha Fin'                 
        }).appendTo('#bloque_p'+xxx);
        $('#periodo_f'+xxx).datepicker(date_opt);
        
        jQuery('<input/>', {
            id: 'monto'+xxx,
            name: 'monto'+xxx,
            type: 'text',
            autocomplete: 'off',
            style : 'margin-right: 5px;font-size: 16px;width: 247px;float: left;',
            'class': 'form-control ui-autocomplete-input',
            placeholder: 'Monto'                 
        }).appendTo('#bloque_p'+xxx);
        
        if($('select #selected').val()=='Alquiler Comercial N'){
            jQuery('<input/>', {
                id: 'iva'+xxx,
                name: 'iva'+xxx,
                type: 'text',
                autocomplete: 'off',
                style : 'margin-right: 5px;font-size: 16px;width: 100px;float: left;',
                'class': 'form-control ui-autocomplete-input',
                placeholder: 'IVA/Alquiler'                 
            }).appendTo('#bloque_p'+xxx);
        }  
        
        jQuery('<input/>', {
            name: 'per_id_'+xxx,
            id: 'per_id_'+xxx,
            type: 'hidden',
            value : '0'              
        }).appendTo('#bloque_p'+xxx);
        
        //Creo su boton para eliminar            
        jQuery('<span/>', {
            id: 'span_p'+xxx,
            onclick: 'removeElement_period('+xxx+')',
            style: 'height: 34px;',
            'class' : 'btn btn-default btn-lg'
        }).appendTo('#bloque_p'+xxx);
        var anterior = xxx - 1;
        $('#span_p'+anterior).attr('disabled',true);
        
        jQuery('<a/>', {
            'class' : 'glyphicon glyphicon-minus-sign',
            style : 'text-decoration: none; margin-top: -3px;'
        }).appendTo('#span_p'+xxx);
        
        //Aumenta el contador de periodos
        cantii++;
        $("#cant_bloques_periodo").val(cantii);        
    }
    function removeElement(id) {
        var idr = $('#serv_id_'+id).val();
        $("#bloque"+id).remove();     
        request('<?= site_url() . 'deleteservper/servicios/serv_id/' ?>'+idr,'','.contenedor_centro');
        xx--;
        canti--;
        $("#cant_bloques").val(canti);
    }
    function removeElement_period(id) {
        var idr = $('#per_id_'+id).val();
        $("#bloque_p"+id).remove();      
        e = id - 1;
        $("#span_p"+e).removeAttr('disabled');
        request('<?= site_url() . 'deleteservper/periodos/per_id/' ?>'+idr,'','.contenedor_centro');
        xxx--;
        cantii--;
        $("#cant_bloques_periodo").val(cantii);
    }
    /*
     * RESTRINGE EL INPUT A MAYUS, MINUS, ESPACIO
     */      
    $('#con_prop').keypress(function(key) {
        if((key.charCode < 97 || key.charCode > 122) && key.charCode != 0 && key.charCode != 32 && key.charCode != 8 && key.charCode != 9  && (key.charCode < 65 || key.charCode > 90)) return false;
    });
    $('#con_inq').keypress(function(key) {
        if((key.charCode < 97 || key.charCode > 122) && key.charCode != 0 && key.charCode != 32 && key.charCode != 8 && key.charCode != 9  && (key.charCode < 65 || key.charCode > 90)) return false;
    });

    $( "#con_venc" ).datepicker({
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
);

    
    $(function(){
        $('#con_inq').autocomplete({
            source: "<?php echo site_url('manager/autocomplete') . '/clientes' ?>",
            select: function(event, ui) {
                $.ajax({
                    url : BASE_URL + "manager/buscar_cliente/clientes/"+ui.item.id,
                    type:'POST',
                    dataType: 'json',
                    success:function(R){
                        eval(R.js);
                        if(R.html != ''){
                            $('#con_inq').html(R.html);
                        }
                    }
                });
            }
        });
        $("#con_inq").keydown(function(){
            if ($("#con_inq").val().length == 1){
                $.ajax({
                    url : BASE_URL + "manager/buscar_cliente/clientes/",
                    type:'POST',
                    dataType: 'json',
                    success:function(R){
                        eval(R.js);
                        if(R.html != ''){
                            $('#con_inq').html(R.html);
                        }
                    }
                });
            }
        });
    });
    $(function(){
        $('#con_prop').autocomplete({
            source: "<?php echo site_url('manager/autocomplete') . '/clientes' ?>",
            select: function(event, ui) {
                $.ajax({
                    url : BASE_URL + "manager/buscar_cc/cuentas_corrientes/"+ui.item.id,
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
        $("#con_prop").keydown(function(){
            if ($("#con_prop").val().length == 1){
                $.ajax({
                    url : BASE_URL + "manager/buscar_cc/cuentas_corrientes/",
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
    $('#con_domi').autocomplete({
        source: "<?php echo site_url('manager/autocomplete') . '/propiedades/' ?>" ,
        select: function(event, ui) {
            $.ajax({
                url : BASE_URL + "manager/buscar_propiedad_prop/propiedades/"+ui.item.id,
                type:'POST',
                dataType: 'json',
                success:function(R){
                    eval(R.js);
                    if(R.html != ''){
                        $('#con_domi').html(R.html);
                        $('#con_domi').blur();
                    }
                }
            });
        }
    });
    $("#con_domi").keydown(function(){
        if ($("#con_domi").val().length == 1){
            $.ajax({
                url : BASE_URL + "manager/buscar_propiedad_prop/propiedades/",
                type:'POST',
                dataType: 'json',
                success:function(R){
                    eval(R.js);
                    if(R.html != ''){
                        $('#con_domi').html(R.html);
                    }
                }
            });
        }
    });
    $(function(){
        $('#con_gar1').autocomplete({
            source: "<?php echo site_url('manager/autocomplete') . '/clientes' ?>",
            select: function(event, ui) {
                $.ajax({
                    url : BASE_URL + "manager/buscar_cliente/clientes/"+ui.item.id,
                    type:'POST',
                    dataType: 'json',
                    success:function(R){
                        eval(R.js);
                        if(R.html != ''){
                            $('#con_gar1').html(R.html);
                        }
                    }
                });
            }
        });
        $("#con_gar1").keydown(function(){
            if ($("#con_gar1").val().length == 1){
                $.ajax({
                    url : BASE_URL + "manager/buscar_cliente/clientes/",
                    type:'POST',
                    dataType: 'json',
                    success:function(R){
                        eval(R.js);
                        if(R.html != ''){
                            $('#con_gar1').html(R.html);
                        }
                    }
                });
            }
        });
    });
    $(function(){
        $('#con_gar2').autocomplete({
            source: "<?php echo site_url('manager/autocomplete') . '/clientes' ?>",
            select: function(event, ui) {
                $.ajax({
                    url : BASE_URL + "manager/buscar_cliente/clientes/"+ui.item.id,
                    type:'POST',
                    dataType: 'json',
                    success:function(R){
                        eval(R.js);
                        if(R.html != ''){
                            $('#con_gar2').html(R.html);
                        }
                    }
                });
            }
        });
        $("#con_gar2").keydown(function(){
            if ($("#con_gar2").val().length == 1){
                $.ajax({
                    url : BASE_URL + "manager/buscar_cliente/clientes/",
                    type:'POST',
                    dataType: 'json',
                    success:function(R){
                        eval(R.js);
                        if(R.html != ''){
                            $('#con_gar2').html(R.html);
                        }
                    }
                });
            }
        });
    });
</script>

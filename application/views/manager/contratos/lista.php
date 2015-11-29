
<div class="actions_container">
    <input type="text" style="margin-right: 5px; font-size: 16px; width: 403px; float: left; margin-top: 5px;" aria-haspopup="true" aria-autocomplete="list" role="textbox" autocomplete="off" placeholder="Propietario" id="auto_personas1" class="form-control ui-autocomplete-input" name="cc_prop">
    <a style=" margin-top: 12px;"id="refresh" href="javascript:;" class="glyphicon glyphicon-refresh" onclick="refrescar()"></a>

</div>
<label style="margin-right: 5px; font-size: 16px; width: 403px; float: left;">Contratos Vigentes: <?= $contratos_vigentes ?></label>
<div id="deleteContrato" class="modal fade" tabindex="-1" role="dialog">
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
<table class="table table-hover">
    <tr>    
        <th>Propietario</th>
        <th>Inquilino</th>
        <th>Tipo</th>
<!--        <th>Inmueble</th>-->
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
//            echo '<td>' . $con->con_domi . '</td>';
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

    <script>
        function modalDelete(id){
            var url = '<?php echo site_url('manager/del_contratos/') ?>'+ '/' + id;
            var action = 'del('+id+',"'+url+'")';
            $('#deleteContrato').find('._delete').attr('onclick',action);
            $('#deleteContrato').modal('show');
        }
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

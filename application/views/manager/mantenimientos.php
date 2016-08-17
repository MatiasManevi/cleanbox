<div class="comments" style="border-left:1px solid #999;padding-left: 13px;width: 40%;margin-left: 15px;clear:none">
    <div style="width:100%;margin-bottom: 10px;"class="porcentajes">
        <div class="porc_data">
            <label style="font-size: 15.2px;">Listado de mantenimientos en proceso, ordenados por fecha y resaltados por prioridad</label>
            <?php foreach ($mantenciones as $key => $value) { ?>
                <?php
                if ($value['prioridad'] == 1) {
                    $class = 'danger';
                } elseif ($value['prioridad'] == 2) {
                    $class = 'warning';
                } elseif ($value['prioridad'] == 3) {
                    $class = 'info';
                }
                ?>
                <div class="alert alert-<?php echo $class ?>">
                    <a onclick="pop_description($(this))" data-proveedor_tel="<?php // echo $value['proveedor_tel'] ?>" data-deadline="<?php echo $value['deadline'] ?>" data-deadline-date="<?php echo Date('d-m-Y', $value['fecha_deadline']) ?>" data-domicilio="<?php echo $value['domicilio'] ?>" data-prov="<?php echo $value['proveedor'] ?>" data-inq="<?php echo $value['inquilino'] ?>" data-prop="<?php echo $value['propietario'] ?>"data-desc="<?php echo $value['descripcion'] ?>" title="<?php echo $value['deadline'] ?>" style="cursor:pointer"ref="javascript:;" class="alert-link"><?php echo $value['domicilio'] . ' Fecha limite:' . Date('d-m-Y', $value['fecha_deadline']) . ' Encargado: ' . $value['proveedor'] ?></a>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
<div id="desc" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Descripcion del mantenimiento</h4>
            </div>
            <div class="modal-body">
                <textarea style="min-height: 100px;height: auto;border: none;width: 100%;resize: none;"readonly="true"></textarea>
                <h5 class="_inq"></h5>
                <h5 class="_prop"></h5>
                <h5 class="_prov"></h5>
                <h5 class="_domicilio"></h5>
                <h5 class="_deadline"></h5>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script>
    function pop_description($button){
        $('#desc').modal('show')
        var desc = $button.attr('data-desc');
        var domicilio = $button.attr('data-domicilio');
        var prop = $button.attr('data-prop');
        var prov = $button.attr('data-prov');
        var inq = $button.attr('data-inq');
//        var proveedor_tel = $button.attr('data-proveedor_tel');
        var deadline_date = $button.attr('data-deadline-date');
        var deadline = $button.attr('data-deadline');
//        var tel = '- ';
//        if(proveedor_tel!=''){
//            tel = proveedor_tel;
//        }
        $('#desc').find('.modal-body textarea').text('');
        $('#desc').find('._inq').text('');
        $('#desc').find('._prop').text('');
        $('#desc').find('._prov').text('');
        $('#desc').find('._domicilio').text('');
        $('#desc').find('._deadline').text('');
        
        $('#desc').find('.modal-body textarea').text('Descripcion: '+desc);
        $('#desc').find('._inq').text('Inquilino: '+inq);
        $('#desc').find('._prop').text('Propietario: '+prop);
//        $('#desc').find('._prov').text('Proveedor: '+prov+' Tel: '+tel);
        $('#desc').find('._domicilio').text('Domicilio: '+domicilio);
        $('#desc').find('._deadline').text('Fecha Limite: '+deadline_date+' ..'+deadline);
    }
</script>


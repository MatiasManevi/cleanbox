<div id="unlockerMora" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Desbloquear dias de mora</h4>
            </div>
            <div class="modal-body">
                <label style="margin: 0 auto;margin-bottom: 25px;">
                    Para modificar los días de mora es necesario ingresar un código de autorización, solicitelo a su encargado</label>
                <input type="text" id="codigo" class="form-control" name="codigo" placeholder="Código" 
                       style="text-align: center;width: 129px;">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button onclick="authUnlock('<?= site_url('manager/autorizar') ?>'+'/'+$('#codigo').val(),this,'#unlockerMora');" type="button" class="btn btn-primary">Aceptar</button>
                <div id="com_display1"></div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

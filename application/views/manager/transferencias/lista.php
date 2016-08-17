<style>
    #ccnav a:hover{
        color:black;
    }
</style>    
<div id="deleteTransferencia" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Esta seguro de eliminar esta transferencia?</h4>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-default _delete" data-dismiss="modal">Eliminar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->  
<h2><?= t('Transferencias') ?></h2>
<div class="actions_container">
    <input name="desde" placeholder="Desde" type="text" id="desde">
    <input name="hasta" placeholder="Hasta" type="text" id="hasta"> 
    <button id="filtro" class="btn btn-primary">Filtrar</button>
    <a style=" margin-top: 12px;"id="refresh" href="javascript:;" class="glyphicon glyphicon-refresh" onclick="refrescar()"></a>
</div>
<table class="table table-hover">
    <tr>    
        <th>Fecha</th>
        <th>Monto</th>
        <th>Tipo</th>
        <th>Acciones</th>
    </tr>
    <?
    if (count($transferencias)) {
        foreach ($transferencias as $row) {
            echo '<tr class="reg_' . $row->transf_id . '">';
            echo '<td>' . $row->transf_fecha . '</td>';
            echo '<td>$ ' . $row->transf_monto . '</td>';
            echo '<td>' . $row->transf_tipo . '</td>';
            echo '<td><a href="javascript:;" class="glyphicon glyphicon-trash" onclick="modalDelete(' . $row->transf_id . ')"></a></td>';
            echo '<td>';
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="100%">' . t('No records found.') . '</td></tr>';
    }
    ?>

    <script>
        
        function modalDelete(id){
            var url = '<?php echo site_url('manager/del_transferencia/') ?>'+ '/' + id;
            var action = 'del('+id+',"'+url+'")';
            $('#deleteTransferencia').find('._delete').attr('onclick',action);
            $('#deleteTransferencia').modal('show');
        }
        
        $("#filtro").click(function() {      
            var desde = $('#desde').val();
            var hasta = $('#hasta').val();
            if(hasta != '' && desde != ''){
                request('<?= site_url() . 'filtrar_transf/' ?>/'+desde+'/'+hasta,'','.contenedor_centro');
            }
        });
        function refrescar(){
            request('<?= site_url() . 'refresh/' ?>transferencias/transf_id/transf_id','','.contenedor_centro');
        }  
        $( "#desde" ).datepicker({
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
        $( "#hasta" ).datepicker({
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
  
    </script>

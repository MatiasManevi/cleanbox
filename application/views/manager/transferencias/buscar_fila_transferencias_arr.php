  
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
    </tr>
    <?
    if (count($transferencias) > 0) {
        for ($x = 0; $x < count($transferencias); $x++) {
            echo '<tr class="reg_' . $transferencias[$x]['transf_id'] . '">';
            echo '<td>' . $transferencias[$x]['transf_fecha'] . '</td>';
            echo '<td>$ ' . $transferencias[$x]['transf_monto'] . '</td>';
            echo '<td>' . $transferencias[$x]['transf_tipo'] . '</td>';
            echo '<td>';
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="100%">' . t('No records found.') . '</td></tr>';
    }
    ?>

    <script>
        $("#filtro").click(function() {      
            var desde = $('#desde').val();
            var hasta = $('#hasta').val();
            if(hasta != '' && desde != ''){
                request('<?= site_url() . 'filtrar_transf/' ?>/'+desde+'/'+hasta,'','.contenedor_centro');
            }
        });
        function refrescar(){
            request('<?= site_url() . 'refresh/' ?>transferencias/transf_id','','.contenedor_centro');
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

<div class="actions_container">
    <div class="field span3 buscar" id="mio">
        <div class="alert alert-info">Filtre por Cta Cte. rangos de fecha o la combinacion de ambas</div>
        <input name="cliente" style="height: 26px;" placeholder="Cta. Cte." type="text" value="" id="auto_personas" class="ui-autocomplete-input">
        <input type="hidden" id="cliente"/>
        <input name="desde" placeholder="Desde" type="text" id="desde">
        <input name="hasta" placeholder="Hasta" type="text" id="hasta">      
        <button id="asd1" class="btn btn-primary">Filtrar</button>
        <a id="refresh" href="javascript:;" class="glyphicon glyphicon-refresh" onclick="refrescar()"></a>
    </div>
</div>
<table class="table table-hover">
    <tr>    
        <th style="text-align: center !important;">Propietario</th>
        <th style="text-align: center !important;">Fecha del Comentario</th>
        <th style="text-align: center !important;">Comentario</th>
        <th style="text-align: center !important;">Domicilio</th>
        <th style="text-align: center !important;">Acciones</th>
    </tr>
    <?
    if ($comentarios->num_rows() > 0) {
        foreach ($comentarios->result() as $row) {
            echo '<tr class="reg_' . $row->com_id . '">';
            echo '<td style="text-align: center !important;">' . $row->com_prop . '</td>';
            echo '<td style="text-align: center !important;">' . $row->com_date . '</td>';
            echo '<td style="text-align: center !important;">' . substr($row->com_com, 0, 90) . ' [...]</td>';
            echo '<td style="text-align: center !important;">' . $row->com_dom . '</td>';
            echo '<td>';

            echo '<a style="text-align: center !important;" href="javascript:;" class="glyphicon glyphicon-edit" onclick="load_edit(\'' . site_url('manager/load_edit_comentarios/' . $row->com_id) . '\')"></a> | ';
            echo '<a style="text-align: center !important;" href="javascript:;" class="glyphicon glyphicon-trash" onclick="del(\'' . $row->com_id . '\',\'' . site_url('manager/del_comentarios/' . $row->com_id) . '\')"></a>  ';
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="100%">' . t('No records found.') . '</td></tr>';
    }
    ?>
</table>

<script>
    $("#asd1").click(function() {      
        var desde = $('#desde').val();
        var hasta = $('#hasta').val();
        var cc = $('#cliente').val();
        var cc = cc.replace(',','.');
        if(cc == '' && hasta != '' && desde != ''){
            request('<?= site_url() . 'filtrar_comentario/' ?>/'+desde+'/'+hasta,'','.contenedor_centro');
        }
        if(cc != '' && hasta == '' && desde == ''){
            request('<?= site_url() . 'filtrar_comentario_2/' ?>'+cc,'','.contenedor_centro');
        }
        if(cc != ''){
            if(hasta != '' && desde != ''){
                request('<?= site_url() . 'filtrar_comentario3/' ?>'+cc+'/'+desde+'/'+hasta,'','.contenedor_centro');
            }
        }  
    });
    function refrescar(){
        request('<?= site_url() . 'refresh/' ?>comentarios/com_id','','.contenedor_centro');
    }   
    
    $("[id^=auto_personas]").each(function(){
        /* Por cada input #auto_player, ya sea auto_player1, 
         * auto_player2, etc, existe este script universal 
         * que maneja todos y cada uno de los autocomplete
         * de dichos inputs
         * ES UN AUTOCOMPLETE DE MULTIPLES INPUTS */
        
        /* El operador ^ hace las veces de comodin
         * señala que lo que esta a la derecha sea una
         * subcadena del input, para tenerlo en cuenta 
         * en el autocomplete*/
        $(this).autocomplete({
            source: "<?php echo site_url('manager/autocomplete') . '/cuentas_corrientes' ?>",
            select: function(event, ui) {
                $.ajax({
                    url : BASE_URL + "manager/buscar_cc/cuentas_corrientes/"+ui.item.id,
                    type:'POST',
                    dataType: 'json',
                    success:function(R){
                        eval(R.js);
                        if(R.html != ''){    
                            $(this).val(R.html);
                            $('#cliente').val(R.html);
                        }
                    }
                });
            }
        });
        $(this).keydown(function(){
            if ($(this).val().length == 1){
                $.ajax({
                    url : BASE_URL + "manager/buscar_cc/cuentas_corrientes/",
                    type:'POST',
                    dataType: 'json',
                    success:function(R){
                        eval(R.js);
                        if(R.html != ''){                          
                            $(this).val(R.html);
                            $('#cliente').val(R.html);
                        }
                    }
                });
            }
        });
        
    });    
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
<div class="actions_container">
    <div class="field span3 buscar" id="mio">
        <div class="alert alert-info">Filtre por Cta Cte. rangos de fecha o la combinacion de ambas</div>
        <input name="cliente" style="height: 26px;" placeholder="Cta. Cte." type="text" value="" id="auto_personas" class="ui-autocomplete-input">
        <input type="hidden" id="cliente"/>
        <input name="desde" placeholder="Desde" type="text" id="desde">
        <input name="hasta" placeholder="Hasta" type="text" id="hasta">   
        <input placeholder="Concepto" type="text" id="auto_concepto" style="width: 205px;height: 26px;margin-right: 12px"name="concepto" > 
        <input type="hidden" id="concepto"/>
        <button id="asd" class="btn btn-primary">Filtrar</button>
        <a id="refresh" href="javascript:;" class="glyphicon glyphicon-refresh" onclick="refrescar()"></a>
    </div>
</div>
<table class="table table-hover">
    <tr>    
        <th>Cta. Cte. Debitada</th>
        <th>Concepto</th>
        <th>Monto</th>

        <th>Domicilio Inmueble</th>
        <th>Fecha</th>
        <th>Acciones</th>
    </tr>
    <?
    if ($debitos->num_rows() > 0) {
        foreach ($debitos->result() as $debito) {
            echo '<tr class="reg_' . $debito->deb_id . '">';
            echo '<td>' . $debito->deb_cc . '</td>';
            echo '<td>' . $debito->deb_concepto . ' (' . $debito->deb_mes . ')</td>';
            echo '<td>$ ' . $debito->deb_monto . '</td>';

            echo '<td>' . $debito->deb_domicilio . '</td>';
            echo '<td>' . $debito->deb_fecha . '</td>';

            echo '<td>';

//            echo '<a href="javascript:;" class="glyphicon glyphicon-edit" onclick="load_edit(\'' . site_url('manager/load_edit_clientes/' . $debito->pago_id) . '\')"></a> | ';
            echo '<a style="margin: 0 auto 0 22px;" href="javascript:;" id="' . $debito->trans . '" class="glyphicon glyphicon-trash" onclick="del_transact(this)"></a>  ';
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="100%">' . t('No records found.') . '</td></tr>';
    }
    ?>
</table>
<div id="back_fader">
    <div id="popup">
    </div>
</div>


<script>
    function del_transact(a){
        var id = $(a).attr('id');
        request('<?= site_url() . 'del_transact/debitos/' ?>'+id,'','#popup1');
        popup1();
    }
    $("#asd").click(function() {      
        var desde = $('#desde').val();
        var hasta = $('#hasta').val();
        var cc = $('#cliente').val();
        var concepto = $('#concepto').val();
        var exe = 0;
        var cc = cc.replace(',','.');
        if(cc == '' && hasta != '' && desde != ''&& concepto == ''){
            request('<?= site_url() . 'filtrar_debito/' ?>/'+desde+'/'+hasta,'','.contenedor_centro');
            exe = 1;
        }
        if(cc != '' && hasta == '' && desde == ''&& concepto == ''){
            request('<?= site_url() . 'filtrar_debito_2/' ?>'+cc,'','.contenedor_centro');
            exe = 1;
        }
        if(cc != ''&& concepto == ''){
            if(hasta != '' && desde != ''&& concepto == ''){
                request('<?= site_url() . 'filtrar_debito3/' ?>'+cc+'/'+desde+'/'+hasta,'','.contenedor_centro');
                exe = 1;
            }
        }
        if(exe == 0){
            if(concepto != ''){
                if(hasta != '' && desde != '' && cc != ''){
                    request('<?= site_url() . 'filtrar_deb_4/' ?>'+cc+'/'+desde+'/'+hasta+'/'+concepto,'','.contenedor_centro');   
                }else if(hasta == '' && desde == '' && cc != ''){
                    request('<?= site_url() . 'filtrar_deb_2/' ?>'+cc+'/'+concepto,'','.contenedor_centro');    
                }else if(hasta != '' && desde != '' && cc == ''){
                    request('<?= site_url() . 'filtrar_deb_3/' ?>'+desde+'/'+hasta+'/'+concepto,'','.contenedor_centro');    
                }else{
                    request('<?= site_url() . 'filtrar_deb_1/' ?>'+concepto,'','.contenedor_centro');        
                } 
            }
        }
    });
    function refrescar(){
        request('<?= site_url() . 'refresh/' ?>debitos/deb_id','','.contenedor_centro');
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
    
    $("[id^=auto_concepto]").each(function(){
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
            source: "<?php echo site_url('manager/autocomplete') . '/conceptos/Salida' ?>",
            select: function(event, ui) {
                $.ajax({
                    url : BASE_URL + "manager/buscar_concepto_c/conceptos/Salida/"+ui.item.id,
                    type:'POST',
                    dataType: 'json',
                    success:function(R){
                        eval(R.js);
                        if(R.html != ''){    
                            $(this).val(R.html);
                            $('#concepto').val(R.html);
                        }
                    }
                });
            }
        });
        $(this).keydown(function(){
            if ($(this).val().length == 1){
                $.ajax({
                    url : BASE_URL + "manager/buscar_concepto_c/conceptos/Salida/",
                    type:'POST',
                    dataType: 'json',
                    success:function(R){
                        eval(R.js);
                        if(R.html != ''){                          
                            $(this).val(R.html);
                            $('#concepto').val(R.html);
                        }
                    }
                });
            }
        });
        
    });
</script>



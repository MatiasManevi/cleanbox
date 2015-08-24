<style>
    #ui-datepicker-div{
        top: 165px !important;
    }    
</style>
<h1 style="font-size: 18px;">Informe o historial de pagos de alquiler de un inquilino en particular, respecto a un inmueble, en un año determinado</h1>
<div style="margin-top: 30px">
    <input value="" class="form-control ui-autocomplete-input" name="desde" placeholder="Año" type="text" id="ano" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true"  style="margin-bottom: 5px;margin-right: 5px;font-size: 16px;width: 100px;float: left;">
    <input value="" id="inquilino" type="text" name="inquilino" class="form-control ui-autocomplete-input" placeholder="Inquilino" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="margin-bottom: 5px;margin-right: 5px;font-size: 16px;width: 400px;float: left;">         
    <input id="con_domi" value="<?= (isset($row) && $row->con_domi ) ? $row->con_domi : '' ?>" type="text" name="con_domi" class="form-control ui-autocomplete-input" placeholder="Domicilio Inmueble" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="float: left;font-size: 16px;margin-bottom: 19px;margin-right: 5px;width: 250px;">
    <button style="clear: both;float: left;height: 42px;margin-left: 807px;" id="buttons_cli" class="btn btn-primary">Generar Informe</button>
</div>
<div id="com_display">
    <span></span>
</div>
<script>
    $("#buttons_cli").click(function() {
        var ano = $('#ano').val();
        var inquilino = $('#inquilino').val();    
        var domicilio = $('#con_domi').val();    
        request_informe('<?= site_url() . 'informar_historial_inq/' ?>'+ano+'/'+inquilino+'/'+domicilio,'','.contenedor_centro');
    });
    $('#ano').keypress(function(key) {
        if((key.charCode < 48 || key.charCode > 57) && key.charCode != 0 && key.charCode != 8 && key.charCode != 9) return false;
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
        $('#inquilino').autocomplete({
            source: "<?php echo site_url('manager/autocomplete') . '/clientes' ?>",
            select: function(event, ui) {
                $.ajax({
                    url : BASE_URL + "manager/buscar_cliente/clientes/"+ui.item.id,
                    type:'POST',
                    dataType: 'json',
                    success:function(R){
                        eval(R.js);
                        if(R.html != ''){
                            $('#inquilino').html(R.html);
                            $('#inquilino').blur();
                        }
                    }
                });
            }
        });
        $("#inquilino").keydown(function(){
            if ($("#inquilino").val().length == 1){
                $.ajax({
                    url : BASE_URL + "manager/buscar_cliente/clientes/",
                    type:'POST',
                    dataType: 'json',
                    success:function(R){
                        eval(R.js);
                        if(R.html != ''){
                            $('#inquilino').html(R.html);
                        }
                    }
                });
            }
        });
    });
    $( "#d" ).datepicker({
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
    $( "#h" ).datepicker({
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

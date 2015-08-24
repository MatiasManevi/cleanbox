<div id="bloque<?= $num ?>" class="bloque" style="display: block;">
    <input value="<?= isset($servicio) ? $servicio['serv_concepto'] : '' ?>" type="text" id="servicio<?= $num ?>" name="servicio<?= $num ?>" style="margin-right: 5px;font-size: 16px;width: 403px;float: left;" class="form-control ui-autocomplete-input" placeholder="Servicio" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">

    <div id="cont_accion<?= $num ?>"style="width: 120px;float: left;margin-right: 19px;margin-top: 0">
        <? if ($num == 1) { ?>
            <label style="margin-top: -26px;float:left;">Acción</label>
        <? } ?>
        <select id="sel_accion<?= $num ?>" class="form-control ui-autocomplete-input" name="accion<?= $num ?>">
            <option onclick="$(this).parent().find('#selected').removeAttr('id');$(this).attr('id','selected')" class="form-control ui-autocomplete-input" <?= (isset($servicio) && $servicio['serv_accion'] == 'Pagar' ? 'selected="selected"' : '' ) ?> value="Pagar">Pagar</option>
            <option onclick="$(this).parent().find('#selected').removeAttr('id');$(this).attr('id','selected')"class="form-control ui-autocomplete-input" <?= (isset($servicio) && $servicio['serv_accion'] == 'Controlar' ? 'selected="selected"' : '' ) ?>  value="Controlar">Controlar</option>
        </select>
    </div>  

    <input id="serv_id_<?= $num ?>" type="hidden" name="serv_id_<?= $num ?>" value="<?= isset($servicio) ? $servicio['serv_id'] : '' ?>"> 
    <span  id="span<?= $num ?>" onclick="removeElement(<?= $num ?>)" style="height: 34px;" class="btn btn-default btn-lg">
        <a class="glyphicon glyphicon-minus-sign" style="text-decoration: none; margin-top: -3px;"></a>
    </span>
    <input type="hidden" id="conc_id_<?= $num ?>" name="conc_id_<?= $num ?>" value="<?= isset($concepto['conc_id']) ? $concepto['conc_id'] : '0' ?>">
</div>
<script>
    $('#servicio'+<?= $num ?>).autocomplete({
        source: "<?php echo site_url('manager/autocomplete') . '/conceptos/cc_varios' ?>",
        select: function(event, ui) {
            $.ajax({
                url : BASE_URL + "manager/buscar_concepto_serv/conceptos/cc_varios/"+ui.item.id,
                type:'POST',
                dataType: 'json',
                success:function(R){
                    eval(R.js);
                    if(R.html != ''){
                        $('#servicio'+<?= $num ?>).html(R.html);
                        $('#servicio'+<?= $num ?>).val(R.html);
                        $('#conc_id_'+<?= $num ?>).val(R.id);
                    }
                }
            });
        }
    });
    //    $("#servicio"+<?= $num ?>).keydown(function(){
    //            if ($("#servicio"+<?= $num ?>).val().length == 1){
    //                $.ajax({
    //                    url : BASE_URL + "manager/buscar_concepto_serv/conceptos/cc_varios/",
    //                    type:'POST',
    //                    dataType: 'json',
    //                    success:function(R){
    //                        eval(R.js);
    //                        if(R.html != ''){
    //                            $('#servicio'+<?= $num ?>).html(R.html);
    //                            $('#conc_id_'+<?= $num ?>).val(R.id);
    //                        }
    //                    }
    //                });
    //            }
    //        });
    $('#accion'+<?= $num ?>).autocomplete({
        source: ["Pagar","Controlar"]
    });
</script>


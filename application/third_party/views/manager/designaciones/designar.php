<form style="overflow: visible;"class="<?= isset($row) ? 'edit_form' : 'add_form' ?>" action="javascript:;" onsubmit="request_post('<?= site_url('manager/save_desig') ?>',this,'.contenedor_centro');return false;" enctype="multipart/form-data">
    <input name="des_id" type="hidden" value="<?= (isset($row) && $row->des_id ) ? $row->des_id : '' ?>"/>    
    
    <div class="pasos">
        <div class="pri_title">Segundo paso: </div><span class="expli">Seleccione los equipos, la fecha de disputa y los arbitros que trabajaran en cada categoria</span>
        <div class="select_dest">                 
            <div class="partido">
                <label>Fecha del Encuentro</label>
                <input value="<?= isset($row) ? $row->des_date : '' ?>" class="des_fecha" id="datepicker" type="text" name="des_date">
                <label>Fecha en Campeonato</label>
                <select name="des_fecha">
                    <option <?= isset($row) && $row->des_fecha == '1° Fecha' ? 'selected="selected"' : '' ?> value="1° Fecha">1° Fecha</option>
                    <option <?= isset($row) && $row->des_fecha == '2° Fecha' ? 'selected="selected"' : '' ?> value="2° Fecha">2° Fecha</option>
                    <option <?= isset($row) && $row->des_fecha == '3° Fecha' ? 'selected="selected"' : '' ?> value="3° Fecha">3° Fecha</option>
                    <option <?= isset($row) && $row->des_fecha == '4° Fecha' ? 'selected="selected"' : '' ?> value="4° Fecha">4° Fecha</option>
                    <option <?= isset($row) && $row->des_fecha == '5° Fecha' ? 'selected="selected"' : '' ?> value="5° Fecha">5° Fecha</option>
                    <option <?= isset($row) && $row->des_fecha == '6° Fecha' ? 'selected="selected"' : '' ?> value="6° Fecha">6° Fecha</option>
                    <option <?= isset($row) && $row->des_fecha == '7° Fecha' ? 'selected="selected"' : '' ?> value="7° Fecha">7° Fecha</option>
                    <option <?= isset($row) && $row->des_fecha == '8° Fecha' ? 'selected="selected"' : '' ?> value="8° Fecha">8° Fecha</option>
                    <option <?= isset($row) && $row->des_fecha == '9° Fecha' ? 'selected="selected"' : '' ?> value="9° Fecha">9° Fecha</option>
                    <option <?= isset($row) && $row->des_fecha == '10° Fecha' ? 'selected="selected"' : '' ?> value="10° Fecha">10° Fecha</option>
                    <option <?= isset($row) && $row->des_fecha == '11° Fecha' ? 'selected="selected"' : '' ?> value="11° Fecha">11° Fecha</option>
                    <option <?= isset($row) && $row->des_fecha == '12° Fecha' ? 'selected="selected"' : '' ?> value="12° Fecha">12° Fecha</option>
                    <option <?= isset($row) && $row->des_fecha == '13° Fecha' ? 'selected="selected"' : '' ?> value="13° Fecha">13° Fecha</option>
                    <option <?= isset($row) && $row->des_fecha == '14° Fecha' ? 'selected="selected"' : '' ?> value="14° Fecha">14° Fecha</option>
                    <option <?= isset($row) && $row->des_fecha == '15° Fecha' ? 'selected="selected"' : '' ?> value="15° Fecha">15° Fecha</option>
                    <option <?= isset($row) && $row->des_fecha == '16° Fecha' ? 'selected="selected"' : '' ?> value="16° Fecha">16° Fecha</option>
                    <option <?= isset($row) && $row->des_fecha == '17° Fecha' ? 'selected="selected"' : '' ?> value="17° Fecha">17° Fecha</option>
                    <option <?= isset($row) && $row->des_fecha == '18° Fecha' ? 'selected="selected"' : '' ?> value="18° Fecha">18° Fecha</option>
                    <option <?= isset($row) && $row->des_fecha == '19° Fecha' ? 'selected="selected"' : '' ?> value="19° Fecha">19° Fecha</option>
                    <option <?= isset($row) && $row->des_fecha == '20° Fecha' ? 'selected="selected"' : '' ?> value="20° Fecha">20° Fecha</option>
                </select>
                <label>Local</label>
                <select name="des_local">
                    <option <?= isset($row) && $row->des_local == 'Tokio' ? 'selected="selected"' : '' ?> value="Tokio">Tokio</option>
                    <option <?= isset($row) && $row->des_local == 'Mitre' ? 'selected="selected"' : '' ?>  value="Mitre">Mitre</option>
                    <option <?= isset($row) && $row->des_local == 'Cataratas' ? 'selected="selected"' : '' ?>  value="Cataratas">Cataratas</option>
                    <option <?= isset($row) && $row->des_local == 'Alto Parana' ? 'selected="selected"' : '' ?>  value="Alto Parana">Alto Parana</option>
                    <option <?= isset($row) && $row->des_local == 'Tirica' ? 'selected="selected"' : '' ?>  value="Tirica">Tirica</option>
                    <option <?= isset($row) && $row->des_local == 'Siglo XXI' ? 'selected="selected"' : '' ?>  value="Siglo XXI">Siglo XXI</option>
                    <option <?= isset($row) && $row->des_local == 'Coati' ? 'selected="selected"' : '' ?>  value="Coati">Coati</option>
                    <option <?= isset($row) && $row->des_local == 'Aemo' ? 'selected="selected"' : '' ?>  value="Aemo">Aemo</option>
                    <option <?= isset($row) && $row->des_local == 'OTC' ? 'selected="selected"' : '' ?>  value="OTC">OTC</option>
                    <option <?= isset($row) && $row->des_local == 'Timbo' ? 'selected="selected"' : '' ?>  value="Timbo">Timbo</option>
                    <option <?= isset($row) && $row->des_local == 'Papel Misionero' ? 'selected="selected"' : '' ?>  value="Papel Misionero">Papel Misionero</option>
                    <option <?= isset($row) && $row->des_local == 'Caza y Pezca' ? 'selected="selected"' : '' ?>  value="Caza y Pezca">Caza y Pezca</option>
                    <option <?= isset($row) && $row->des_local == 'Itapua' ? 'selected="selected"' : '' ?>  value="Itapua">Itapua</option>
                    <option <?= isset($row) && $row->des_local == 'Sol Dorado' ? 'selected="selected"' : '' ?>  value="Sol Dorado">Sol Dorado</option>
                    <option <?= isset($row) && $row->des_local == 'Guarani' ? 'selected="selected"' : '' ?>  value="Guarani">Guarani</option>
                    <option <?= isset($row) && $row->des_local == 'Racing' ? 'selected="selected"' : '' ?>  value="Racing">Racing</option>
                    <option <?= isset($row) && $row->des_local == 'Huracan (M)' ? 'selected="selected"' : '' ?>  value="Huracan (M)">Huracan (M)</option>
                </select>
                <label>Visita</label>
                <select name="des_visita">
                    <option <?= isset($row) && $row->des_visita == 'Tokio' ? 'selected="selected"' : '' ?> value="Tokio">Tokio</option>
                    <option <?= isset($row) && $row->des_visita == 'Mitre' ? 'selected="selected"' : '' ?>  value="Mitre">Mitre</option>
                    <option <?= isset($row) && $row->des_visita == 'Cataratas' ? 'selected="selected"' : '' ?>  value="Cataratas">Cataratas</option>
                    <option <?= isset($row) && $row->des_visita == 'Alto Parana' ? 'selected="selected"' : '' ?>  value="Alto Parana">Alto Parana</option>
                    <option <?= isset($row) && $row->des_visita == 'Tirica' ? 'selected="selected"' : '' ?>  value="Tirica">Tirica</option>
                    <option <?= isset($row) && $row->des_visita == 'Siglo XXI' ? 'selected="selected"' : '' ?>  value="Siglo XXI">Siglo XXI</option>
                    <option <?= isset($row) && $row->des_visita == 'Coati' ? 'selected="selected"' : '' ?>  value="Coati">Coati</option>
                    <option <?= isset($row) && $row->des_visita == 'Aemo' ? 'selected="selected"' : '' ?>  value="Aemo">Aemo</option>
                    <option <?= isset($row) && $row->des_visita == 'OTC' ? 'selected="selected"' : '' ?>  value="OTC">OTC</option>
                    <option <?= isset($row) && $row->des_visita == 'Timbo' ? 'selected="selected"' : '' ?>  value="Timbo">Timbo</option>
                    <option <?= isset($row) && $row->des_visita == 'Papel Misionero' ? 'selected="selected"' : '' ?>  value="Papel Misionero">Papel Misionero</option>
                    <option <?= isset($row) && $row->des_visita == 'Caza y Pezca' ? 'selected="selected"' : '' ?>  value="Caza y Pezca">Caza y Pezca</option>
                    <option <?= isset($row) && $row->des_visita == 'Itapua' ? 'selected="selected"' : '' ?>  value="Itapua">Itapua</option>
                    <option <?= isset($row) && $row->des_visita == 'Sol Dorado' ? 'selected="selected"' : '' ?>  value="Sol Dorado">Sol Dorado</option>
                    <option <?= isset($row) && $row->des_visita == 'Guarani' ? 'selected="selected"' : '' ?>  value="Guarani">Guarani</option>
                    <option <?= isset($row) && $row->des_visita == 'Racing' ? 'selected="selected"' : '' ?>  value="Racing">Racing</option>              
                    <option <?= isset($row) && $row->des_local == 'Huracan (M)' ? 'selected="selected"' : '' ?>  value="Huracan (M)">Huracan (M)</option>
                </select>
            </div>
            <?
            if (!(isset($partidos))) {
                $i = 1;
                foreach ($_POST as $variable => $valor) {
                    ?>
                    <div class="partido">
                        <? $categoria = str_replace('_', ' ', $variable); ?>
                        <label class="cate"><?= $categoria ?></label>
                        <input type="hidden" name="<?= $i ?>" value="<?= $categoria ?>"> 
                        <label>Primer Juez</label>
                        <select name="jor_pri_juez<?= $i ?>">
                            <? foreach ($arbitros->result() as $row) { ?>
                                <option value="<?= $row->arb_name ?>"><?= $row->arb_name ?></option>
                            <? } ?>
                        </select>
                        <label>Segundo Juez</label>
                        <select name="jor_sec_juez<?= $i ?>">
                            <? foreach ($arbitros->result() as $row) { ?>
                                <option value="<?= $row->arb_name ?>"><?= $row->arb_name ?></option>
                            <? } ?>
                        </select>
                    </div>  
                    <?
                    $i++;
                }
            } else {
                $i = 1;
                foreach ($partidos->result() as $partido) {
                    ?>
                    <input name="jor_id<?= $i ?>" type="hidden" value="<?= (isset($partido) && $partido->jor_id ) ? $partido->jor_id : '' ?>"/>
                    <div class="partido">
                        <label class="cate"><?= $partido->jor_cate ?></label>
                        <input type="hidden" name="<?= $i ?>" value="<?= $partido->jor_cate ?>"> 
                        <label>Primer Juez</label>
                        <select name="jor_pri_juez<?= $i ?>">
                            <? foreach ($arbitros->result() as $rowe) { ?>
                                <option <?= isset($partido) && $partido->jor_pri_juez == $rowe->arb_name ? 'selected="selected"' : '' ?>value="<?= $rowe->arb_name ?>"><?= $rowe->arb_name ?></option>
                            <? } ?>
                        </select>
                        <label>Segundo Juez</label>
                        <select name="jor_sec_juez<?= $i ?>">
                            <? foreach ($arbitros->result() as $roww) { ?>
                                <option <?= isset($partido) && $partido->jor_sec_juez == $roww->arb_name ? 'selected="selected"' : '' ?>value="<?= $roww->arb_name ?>"><?= $roww->arb_name ?></option>
                            <? } ?>
                        </select>
                    </div>  
                    <?
                    $i++;
                }
            }
            ?>
            <input type="hidden" name="cant_partidos" value="<?= $i ?>"> 
            <div class="partido">
                <label>El 10% fue depositado?</label>
                <input <?= isset($row) && $row->des_pagado == '1' ? 'checked="checked"' : '' ?>class="payed" onclick="change(this)" value="<?= isset($row) ? $row->des_pagado : '0' ?>" name="des_pagado" type="checkbox">
                <label style="float: left;margin-right: -18px;margin-top: 0;">Si</label>
            </div>
        </div>    
    </div> 
    <div style="margin-top:23px;width: 522px;"id="row_but" class="row-fluid">
        <button id="buttons_cli" class="btn btn-primary"><?= isset($row) ? 'Guardar' : 'Agregar' ?></button>
        <a id="buttons_cli" class="btn" href="<?= site_url('designaciones') ?>"><?= 'Cancelar' ?></a>
        <input id="buttons_cli" class="btn btn-primary" style="width: 168px;float:left" value="Realizar Informe" onclick="request_post('<?= site_url('manager/informe_designacion') . '/' . (isset($row) ? $row->des_id : '') ?>',this,'.contenedor_centro');return false;" />
        <div style="margin:10px;border-radius: 10px;margin-top: 50px;width: 287px;" class="msg_display"></div>
    </div>
</form>    

<script>
    function change(input){
        if($(input).val()==0){
            $(input).val(1);
        }else{
            $(input).val(0);
        }
    }
    $( "#datepicker" ).datepicker({
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
    });
</script>

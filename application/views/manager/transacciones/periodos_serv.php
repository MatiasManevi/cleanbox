<div class="tablas"> 
    <table style="margin-bottom: 0px"class="table">
        <tr>    
            <th class="centrar">Periodo</th>
            <th class="centrar">Monto</th>
        </tr>
        <?
        if ($periodos->num_rows() > 0) {
            foreach ($periodos->result() as $periodo) {

                echo '<tr class="reg_' . $periodo->per_id . ' ' . ($periodo->per_id == $pintar ? 'pintar' : '') . '">';
                echo '<td class="centrar">' . $periodo->per_inicio . ' | ' . $periodo->per_fin . '</td>';
                echo '<td class="centrar"> $ ' . $periodo->per_monto . '</td>';
            }
        } else {
            echo '<tr><td colspan="100%">' . t('Periodos no Ingresados.') . '</td></tr>';
        }
        ?>

    </table>
</div>
<div class="tablas">
    <table style="margin-bottom: 0px"class="table">
        <tr>    
            <th class="centrar">Servicios</th>
            <th class="centrar">Accion</th>
        </tr>
        <?
        if ($servicios->num_rows() > 0) {
            foreach ($servicios->result() as $servicio) {

                echo '<tr class="reg_' . $servicio->serv_id . '">';
                echo '<td class="centrar">' . $servicio->serv_concepto . '</td>';
                echo '<td class="centrar">' . $servicio->serv_accion . '</td>';
            }
        } else {
            echo '<tr><td colspan="100%">' . t('Servicios no Ingresados.') . '</td></tr>';
        }
        ?>

    </table>
</div>
<div class="porcentajes">
    <div class="porc_data">
        <label>Gestion Cobro:</label><input value="<?= $porc ?>"  type="text" name="con_porc" id="con_porc" style="margin-right: 5px;font-size: 16px;width: 114px;" class="form-control ui-autocomplete-input" placeholder="Gestion Cobro" readonly>
    </div>
    <div class="porc_data">
        <label>Porc. Punitorio:</label><input value="<?= $punitorio ?>"  type="text" name="con_punitorio" id="con_punitorio" style="margin-right: 5px;font-size: 16px;width: 114px;" class="form-control ui-autocomplete-input" placeholder="Punitorios" readonly>
    </div>
    <div class="porc_data">
        <label>Incluye IVA/Comision:</label><input value="<?= $iva ?>"  type="text" name="con_iva" id="con_iva" style="margin-right: 5px;font-size: 16px;width: 114px;" class="form-control ui-autocomplete-input" placeholder="IVA" readonly>
    </div>
    <div class="porc_data">
        <label>Incluye IVA/Alquiler:</label><input value="<?= $iva_alq ?>"  type="text" name="con_iva_alq" id="con_iva_alq" style="margin-right: 5px;font-size: 16px;width: 114px;" class="form-control ui-autocomplete-input" placeholder="IVA" readonly>
    </div>
    <div class="porc_data">
        <label>Notificar al Propietario Via Mail:</label><input onclick="change(this)" value="0" type="checkbox" name="notifica" id="notifica" style="margin-right: 5px;font-size: 16px;width: 114px;" class="form-control ui-autocomplete-input" placeholder="Notifica">
    </div>
    <div class="porc_data">
        <label style="margin-left: 5px; margin-top: 5px;">Paga Intereses?</label>
        <select id="paga_intereses" autofocus="1" class="form-control ui-autocomplete-input" name="paga_intereses">
            <option class="form-control ui-autocomplete-input" value="Si">Si</option>
            <option class="form-control ui-autocomplete-input" value="No">No</option>
        </select>
    </div>
    <div id="paga">
        Si: el inquilino pagara intereses devengados<br>  
        No: el inquilino tiene intereses devengados, No pagara hoy
    </div>
    <div id="tooltip">
        Porc. Gestion de Cobro Ej: 7% = 0.07
    </div>
</div>
<div class="porcentajes">
    <div class="porc_data">
        <label style="font-size: 15.2px;">Los créditos por Gestion de Cobro, IVA e Intereses correspondientes seran calculados y generados automaticamente por el sistema tomando los datos de la presente tabla</label>
    </div>
</div>
<script>
    function change(check){
        var val = $(check).val();
        if(val == 1){
            $(check).val(0);
        }else{
            $(check).val(1);
        }
    }
    $(document).ready(function(){
        $('#con_porc').hover(function(){
            $('#tooltip').css('display','block');
        },function(){
            $('#tooltip').css('display','none');
        });
        $(document).mousemove(function(event){
            var mx = event.pageX;
            var my = event.pageY;
            $('#tooltip').css('left',mx+'px').css('right',my+'px').css('top',170);
        })   
        $('#paga_intereses').hover(function(){
            $('#paga').css('display','block');
        },function(){
            $('#paga').css('display','none');
        });
        $(document).mousemove(function(event){
            var mx = event.pageX;
            var my = event.pageY;
            $('#paga').css('left',mx+'px').css('right',my+'px').css('top',170);
        })   
    });
   
</script>

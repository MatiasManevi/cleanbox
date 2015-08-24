<style>
    #ccnav a:hover{
        color:black;
    }
</style>    
<h2><?= t('Conceptos') ?></h2>
<ul id="ccnav" class="nav nav-tabs">
    <li class="active"><a href="#agregar" data-toggle="tab">Agregar Nuevo</a></li>
    <li><a href="#lista" data-toggle="tab">Lista de Conceptos</a></li>
</ul>

<div class="tab-content">
    <!--  Crear Cliente  -->
    <div class="tab-pane fade in active" id="agregar">
        <form style="overflow: visible;"class="<?= isset($row) ? 'edit_form' : 'add_form' ?>" action="javascript:;" onsubmit="request_post_cuenta('<?= site_url('manager/save_conceptos') ?>',this,'.contenedor_centro');return false;" enctype="multipart/form-data"> 
            <div style="width:100%;margin-bottom: 20px;"class="porcentajes">
                <div class="porc_data">
                    <label style="font-size: 15.2px;">Formulario para registrar los Conceptos que se usaran en Creditos (Entradas) y Debitos (Salidas) </label>
                </div>
            </div>
            <input value="<?= (isset($row) && $row->conc_desc ) ? $row->conc_desc : '' ?>" type="text" id="conc_desc" name="conc_desc" class="form-control ui-autocomplete-input"  placeholder="Concepto" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" style="clear: both;margin-bottom: 5px;margin-right: 5px;font-size: 16px;width: 403px;float: left;">
            <div class="forma_pago_select">
                <label style="margin-left: 5px; margin-bottom: 0px;">Tipo de Concepto</label>
                <select class="form-control ui-autocomplete-input" name="conc_tipo">
                    <option class="form-control ui-autocomplete-input"<?= (isset($row) && $row->conc_tipo == 'Entrada' ? 'selected="selected"' : '' ) ?> value="Entrada">Entrada</option>
                    <option class="form-control ui-autocomplete-input"<?= (isset($row) && $row->conc_tipo == 'Salida' ? 'selected="selected"' : '' ) ?>  value="Salida">Salida</option>
                </select>

            </div> 
            <div id="select" class="forma_pago_select">
                <label style="margin-left: 5px; margin-bottom: 0px;">Cuenta</label>
                <select class="form-control ui-autocomplete-input" name="conc_cc">
                    <option class="form-control ui-autocomplete-input" <?= (isset($row) && $row->conc_cc == 'Principal' ? 'selected="selected"' : '' ) ?> value="cc_saldo">Principal</option>
                    <option class="form-control ui-autocomplete-input" <?= (isset($row) && $row->conc_cc == 'Secundaria' ? 'selected="selected"' : '' ) ?>  value="cc_varios">Secundaria</option>
                </select>
            </div> 
            <div id="select" class="forma_pago_select">
                <label style="margin-left: 5px; margin-bottom: 0px;">Control Autorizacion</label>
                <select class="form-control ui-autocomplete-input" name="conc_control">
                    <option class="form-control ui-autocomplete-input" <?= (isset($row) && $row->conc_control == '1' ? 'selected="selected"' : '' ) ?> value="1">Si</option>
                    <option class="form-control ui-autocomplete-input" <?= (isset($row) && $row->conc_control == '0' ? 'selected="selected"' : '' ) ?>  value="0">No</option>
                </select>
            </div>
            <div id="tooltipInt">
                Cuenta en la cual trabajara este concepto. Ej: Concepto como Expensas en Secundaria, Rendicion en Principal
            </div>
            <input name="conc_id" type="hidden" value="<?= (isset($row) && $row->conc_id ) ? $row->conc_id : '' ?>"/>
            <button class="btn btn-primary" type="submit" id="buttons_cli" style="float: left; line-height: 0;"><?= isset($row) ? 'Guardar' : 'Crear' ?></button>
            <div id="com_display">
                <span></span>
            </div>
        </form>

    </div>
    <!--  Lista de Clientes  -->
    <div class="tab-pane fade" id="lista">
        <?= isset($lista) ? $lista : '' ?>
    </div>
</div>




<script>
    /*
     * RESTRINGE EL INPUT A MAYUS, MINUS, ESPACIO
     */   
    $('#client_name').keypress(function(key) {
        if((key.charCode < 97 || key.charCode > 122) && key.charCode != 0 && key.charCode != 32 && key.charCode != 8 && key.charCode != 9  && (key.charCode < 65 || key.charCode > 90)) return false;
    });

    $('#ccnav a').click(function (e) {
        e.preventDefault()
        $(this).tab('show')
    })  
    $(document).ready(function(){
        $('#select').hover(function(){
            $('#tooltipInt').css('display','block');
        },function(){
            $('#tooltipInt').css('display','none');
        });
        $(document).mousemove(function(event){
            var mx = event.pageX;
            var my = event.pageY;
            $('#tooltipInt').css('left',mx+'px').css('right',my+'px').css('top',-10);
        })  
        
    });
</script>
<h2><?php echo t('Configuraciones') ?></h2>
<ul id="ccnav" class="nav nav-tabs">
    <li class="active _add_tab_button"><a href="#bussines" data-toggle="tab">La empresa</a></li>
    <li><a href="#system" data-toggle="tab">Sistema</a></li>
    <li><a href="#tax" data-toggle="tab">Porcentajes impositivos</a></li>
</ul>

<div class="tab-content section">

    <div class="tab-pane fade in active _add" id="bussines">
        <form class="section_form" action="javascript:;" onsubmit="general_scripts.saveEntity('<?php echo site_url('saveSettings') ?>', this);return false;" enctype="multipart/form-data"> 

            <div class="section_description">
                <label>Registra informacion basica de tu empresa, con estos datos podremos confeccionar los recibos, si asi lo deseas!</label>
            </div>

            <input value="<?php echo $settings['user_id']; ?>" name="user_id" type="hidden"/>
            <input value="<?php echo $settings['id']; ?>" name="id" type="hidden"/>
            <input value="bussines" name="setting_section" type="hidden"/>
            <input required value="<?php echo $settings['name']; ?>" title="Nombre de la empresa" placeholder="Nombre de la empresa" type="text" name="name" class="form-control ui-autocomplete-input section_input _general_letters_input_control" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">
            <input required value="<?php echo $settings['cuit']; ?>" title="CUIT" placeholder="CUIT" type="text" name="cuit" class="form-control ui-autocomplete-input section_input" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">
            <input required value="<?php echo $settings['iibb_number']; ?>" title="CUIT IIBB" placeholder="CUIT IIBB" type="text"  name="iibb_number" class="form-control ui-autocomplete-input section_input" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">

            <textarea required title="Actividad" class="section_area form-control" type="text" placeholder="Actividad" name="activity"><?php echo $settings['activity']; ?></textarea>

            <input value="<?php echo $settings['init_activity_date']; ?>" title="Inicio de actividad" placeholder="Inicio de actividad" type="text" name="init_activity_date" class="form-control ui-autocomplete-input section_input _datepicker_filter _general_number_input_control _general_letters_input_control" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">
            <input required value="<?php echo $settings['fiscal_status']; ?>" title="Status fiscal" placeholder="Status fiscal" type="text" name="fiscal_status" class="form-control ui-autocomplete-input section_input _general_letters_input_control" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">
            <input required value="<?php echo $settings['address']; ?>" title="Domicilio" placeholder="Domicilio" type="text" name="address" class="form-control ui-autocomplete-input section_input" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">
            <input required value="<?php echo $settings['email']; ?>" title="Email" placeholder="Email" type="text" name="email" class="form-control ui-autocomplete-input section_input" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">
            <input value="<?php echo $settings['site_url']; ?>" title="Sitio web" placeholder="Sitio web" type="text" name="site_url" class="form-control ui-autocomplete-input section_input" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">
            <input required value="<?php echo $settings['phone']; ?>" title="Telefono" placeholder="Telefono" type="text" name="phone" class="form-control ui-autocomplete-input section_input _general_number_input_control" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">
            <input required value="<?php echo $settings['cel_phone']; ?>" title="Celular" placeholder="Celular" type="text" name="cel_phone" class="form-control ui-autocomplete-input section_input _general_number_input_control" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">
            <input required value="<?php echo $settings['city']; ?>" title="Ciudad" placeholder="Ciudad" type="text" name="city" class="form-control ui-autocomplete-input section_input _general_letters_input_control" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">
            <input required value="<?php echo $settings['state']; ?>" title="Provincia" placeholder="Provincia" type="text" name="state" class="form-control ui-autocomplete-input section_input _general_letters_input_control" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">
            <input required value="<?php echo $settings['zip_code']; ?>" title="Codigo postal" placeholder="Codigo postal" type="text" name="zip_code" class="form-control ui-autocomplete-input section_input _general_number_input_control" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">

            <div class="logo_container">
                <label>Logo de la empresa</label>
                <div class="logo _logo">
                    <?php if (isset($settings) && $settings['logo'] != '') { ?>
                    <img class="img_shadow _image_logo" src="<?php echo img_url() . 'bussines_logos/' . $settings['logo'] ?>" alt="logo"/>
                    <input type="hidden" name="logo" id="image" value="<?php echo $settings['logo'] ?>"/>
                    <a class="close _remove_image" href="javascript:;" onclick="general_scripts.removeImage('bussines_logos', '._logo');" title="Eliminar">[&times;]</a>
                    <?php } else { ?>
                    <img height="200" width="200" class="img_shadow _no_image" src="<?php echo img_url() . 'no-image.png' ?>" alt="logo"/>
                    <?php } ?>
                </div>
                <div class="button_uploader">
                    <label for="Filedata"></label>
                    <?php echo form_upload(array('name' => 'Filedata', 'id' => 'logo')); ?>
                </div>
            </div>

            <button class="btn btn-primary submit_button" type="submit">Guardar</button>
        </form>
    </div>

    <div class="tab-pane fade" id="system">
        <form class="section_form" action="javascript:;" onsubmit="general_scripts.saveEntity('<?php echo site_url('saveSettings') ?>', this);return false;" enctype="multipart/form-data"> 

            <div class="section_description">
                <label>Registra configuraciones relacionadas al funcionamiento del sistema, adaptalo a tu necesidad!</label>
            </div>

            <input value="<?php echo $settings['user_id']; ?>" name="user_id" type="hidden"/>
            <input value="<?php echo $settings['id']; ?>" name="id" type="hidden"/>
            <input value="system" name="setting_section" type="hidden"/>
            
            <input onclick="general_scripts.changeValueCheckbox($(this));" style="float: left;margin-right: 8px;margin-left: 4px;margin-bottom: 22px;" type="checkbox" id="print_receive" name="print_receive" <?php echo $settings['print_receive'] ? 'checked' : ''?> value="<?php echo $settings['print_receive']; ?>"/><label for="print_receive">Imprimir recibo al cobrar alquileres o servicios a inquilinos</label>

            <input onclick="general_scripts.changeValueCheckbox($(this));" style="float: left;margin-right: 8px;margin-left: 4px;margin-bottom: 22px;clear: both;" type="checkbox" id="build_receive_header" name="build_receive_header" <?php echo $settings['build_receive_header'] ? 'checked' : ''?> value="<?php echo $settings['build_receive_header']; ?>"/><label for="build_receive_header">Construir cabecera de recibos automaticamente con la informacion de la empresa</label>

            <input onclick="general_scripts.changeValueCheckbox($(this));" style="float: left;margin-right: 8px;margin-left: 4px;margin-bottom: 22px;clear: both;" type="checkbox" id="print_copy" name="print_copy" <?php echo $settings['print_copy'] ? 'checked' : ''?> value="<?php echo $settings['print_copy']; ?>"/><label for="print_copy">Imprimir copia de recibos</label>

            <input onclick="general_scripts.changeValueCheckbox($(this));" style="float: left;margin-right: 8px;margin-left: 4px;margin-bottom: 22px;clear: both;" type="checkbox" id="print_debit" name="print_debit" <?php echo $settings['print_debit'] ? 'checked' : ''?> value="<?php echo $settings['print_debit']; ?>"/><label for="print_debit">Imprimir recibo para debitos</label>
            
            <div class="section_selects">
                <label>Tipo de devolucion de prestamos</label>
                <select title="Esoecifica si para devolver prestamos se utilizara una devolucion flexible o estricta.
                     La devolucion flexible permite devolver en cuotas, es decir no es necesario que el monto
                     del credito cubra completamente el saldo prestado para realizar la devolucion.
                     La devolucion estricta implica que el credito si o si debe cubrir el monto del prestamo
                     para realizarse la devolucion" class="form-control ui-autocomplete-input" name="return_loan_in_dues">
                    <option <?php echo $settings['return_loan_in_dues'] ? 'selected' : '' ?> value="1">Flexible</option>
                    <option <?php echo!$settings['return_loan_in_dues'] ? 'selected' : '' ?> value="0">Estricta</option>
                </select>
            </div> 

            <div class="section_selects">
                <label>Control por codigo de autorizacion</label>
                <select title="Especifica si estara activo el sistema de generacion de codigos. Estos codigos sirven para solicitar habilitar campo 'dias de mora' para modificarlo" class="form-control ui-autocomplete-input" name="code_control">
                    <option <?php echo $settings['code_control'] ? 'selected' : '' ?> value="1">Activado</option>
                    <option <?php echo!$settings['code_control'] ? 'selected' : '' ?> value="0">Desactivado</option>
                </select>
            </div> 

            <div class="section_selects">
                <label>Inicio mensuales de cajas fisicas</label>
                <select title="Especifica si la caja fisica comenzara el nuevo mes con monto en $0.00 o arrastrara el monto con el que termino el mes anterior" class="form-control ui-autocomplete-input" name="begin_cash_zero">
                    <option <?php echo $settings['begin_cash_zero'] ? 'selected' : '' ?> value="1">Inicio de caja fisica siempre en $ 0.00 (cero)</option>
                    <option <?php echo!$settings['begin_cash_zero'] ? 'selected' : '' ?> value="0">Inicio de caja fisica arrastrado de mes anterior</option>
                </select>
            </div> 

            <div class="section_selects">
                <label>Prestamos a Rendiciones</label>
                <select title="Especifica si se permite que debitos de Rendiciones dejen en negativo la cuenta, por lo cual se generara un prestamo de la Inmobiliaria para solventar la Rendicion en la cuenta sin fondos del propietario" class="form-control ui-autocomplete-input" name="loan_rendition">
                    <option <?php echo $settings['loan_rendition'] ? 'selected' : '' ?> value="1">Generar prestamo por Rendicion</option>
                    <option <?php echo!$settings['loan_rendition'] ? 'selected' : '' ?> value="0">No generar prestamo por Rendicion</option>
                </select>
            </div> 

            <button class="btn btn-primary submit_button" type="submit">Guardar</button>
        </form>
    </div>

    <div class="tab-pane fade" id="tax">
        <form class="section_form" action="javascript:;" onsubmit="general_scripts.saveEntity('<?php echo site_url('saveSettings') ?>', this);return false;" enctype="multipart/form-data"> 

            <div class="section_description">
                <label>Registra porcentajes de impuestos que seran usados para calcular automaticamente las percepciones de los mismos</label>
            </div>

            <input value="<?php echo $settings['user_id']; ?>" name="user_id" type="hidden"/>
            <input value="<?php echo $settings['id']; ?>" name="id" type="hidden"/>
            <input value="tax" name="setting_section" type="hidden"/>

            <input required value="<?php echo $settings['iva_tax']; ?>" title="% IVA Ej: 21% = 0.21" placeholder="% IVA" type="text" name="iva_tax" class="form-control ui-autocomplete-input section_input _general_amount_input_control" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">
            <input required value="<?php echo $settings['iibb_bank_tax']; ?>" title="% IIBB/Operacion bancaria Ej: 2.45% = 0.0245" placeholder="% IIBB/Operacion bancaria" type="text" name="iibb_bank_tax" class="form-control ui-autocomplete-input section_input _general_amount_input_control" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">

            <button class="btn btn-primary submit_button" type="submit">Guardar</button>
        </form>
    </div>
    
</div>

<script>
    $(function(){
        general_scripts.activateUploadFromFileImage($('#logo'), 'bussines_logos', '._logo');
    });
</script>

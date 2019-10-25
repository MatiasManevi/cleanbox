
/*
 * Project: Cleanbox
 * Document: maintenance
 * 
 * @author Manevi Matias Alejandro 
 * Informatic Engineer - Web Developer
 * 
 * Contact: manevimatias@gmail.com
 * 
 * All rights reserved ®
 */

var maintenance = new Object();
var provider = new Object();
var provider_rol = new Object();

maintenance.init = function () {
    maintenance.initComponents();
    
    // Proveedores searchers
    general_scripts.bindInputListSearcher('proveedores', 'prov_id', 'proveedor');
    
    // Mantenimientos searchers
    general_scripts.bindInputAutocomplete($('._filter_propietary'), 'cuentas_corrientes', 'cc_id', 'cc_prop');
    general_scripts.bindInputAutocomplete($('._filter_renter'), 'clientes', 'client_id', 'client_name');
    general_scripts.bindInputAutocomplete($('._filter_provider'), 'proveedores', 'prov_id', 'prov_name');
    general_scripts.bindInputAutocomplete(maintenance.mant_domicilio, 'propiedades', 'prop_id', 'prop_dom');
    general_scripts.bindInputAutocomplete(maintenance.mant_prop, 'cuentas_corrientes', 'cc_id', 'cc_prop');
    general_scripts.bindInputAutocomplete(maintenance.mant_inq, 'clientes', 'client_id', 'client_name');
    general_scripts.bindDatepicker($('._datepicker_filter'));
    general_scripts.bindDatepicker(maintenance.mant_date_deadline);
    general_scripts.bindDatepicker(maintenance.mant_date_end);
    general_scripts.bindInputListSearcher('proveedores', 'prov_id', 'proveedor', true);
    
    // Provider rol searchers
    general_scripts.bindInputListSearcher('providers_rols', 'id', 'area');
};

// Inicializa componentes html
maintenance.initComponents = function () {
    // Proveedor fields
    provider.areas_added = $('._areas_added');
    provider.areas = $('._areas');
    provider.prov_id = $('#prov_id');
    provider.prov_name = $('#prov_name');
    provider.prov_tel = $('#prov_tel');
    provider.prov_email = $('#prov_email');
    provider.prov_domicilio = $('#prov_domicilio');
    // Nota fields
    provider.nota_id = $('#nota_id');
    provider.nota_garantia = $('#nota_garantia');
    provider.nota_prov_id = $('#nota_prov_id');
    provider.nota_exp = $('#nota_exp');
    provider.nota_timing = $('#nota_timing');
    provider.nota_presup = $('#nota_presup');
    provider.nota_trust = $('#nota_trust');
    provider.nota_calidad = $('#nota_calidad');
    provider.nota_total = $('#nota_total');
    provider.bindCalculateNota();
    
    // Mantenimiento fields
    maintenance.mant_id = $('#mant_id');
    maintenance.mant_domicilio = $('#mant_domicilio');
    maintenance.mant_prop = $('#mant_prop');
    maintenance.mant_inq = $('#mant_inq');
    maintenance.mant_prov_1 = $('#mant_prov_1');
    maintenance.mant_prov_2 = $('#mant_prov_2');
    maintenance.mant_prov_3 = $('#mant_prov_3');
    maintenance.mant_desc = $('#mant_desc');
    maintenance.mant_monto = $('#mant_monto');
    maintenance.mant_date_deadline = $('#mant_date_deadline');
    maintenance.mant_date_end = $('#mant_date_end');
    maintenance.mant_prioridad = $('#mant_prioridad');
    maintenance.mant_status = $('#mant_status');
    maintenance.mant_calif = $('#mant_calif');
    
    // Providers rols fields
    provider_rol.id = $('#id');
    provider_rol.rol = $('#rol');
    maintenance.image_listing = $('.image_listing');
    
    maintenance.pictures = $('._pictures');
};

provider.bindCalculateNota = function () {
    $('._eval_param').on('focus keydown keyup keypress paste', function (key) {
        var sum = 0;
        $('._eval_param').each(function(){
            var val = parseInt($(this).val());
            if(val){
                sum += val;
            }
        });
        var total = sum / 6;
        total = Math.round(total * 100) / 100;
        
        provider.nota_total.val(total);
    }); 
};

provider.addArea = function (area_name) {
    if(area_name != 'Area' && area_name != 'Seleccione sus áreas'){
        var exists = false;
        
        $('.area').each(function(){
            if($(this).attr('id') == 'area_'+area_name){
                exists = true;
            }
        });
        
        if(!exists){
            
            // Creo el html
            var $new_area = $('<div class="provider_area">"');
            var action = "provider.deleteArea('"+area_name+"')";
            var $input = "<input value='"+area_name+"' readonly class='form-control ui-autocomplete-input section_input'>";
            var $close = $('<a onclick="'+action+',$(this).parent().hide()" title="Eliminar area" href="javascript:;" class="delete_area">&times;</a>');
            $new_area.html($input);
            $new_area.append($close);   
            provider.areas.append($new_area);  
            
            // Guardo en el input hidden todas
            var provider_areas = provider.areas_added.val();
            provider_areas = provider_areas + '#' + area_name;
            provider.areas_added.val(provider_areas);
           
            general_scripts.initTooltips();
        }    
    }
};

provider.deleteArea = function (area) {
    var provider_areas = provider.areas_added.val();
    provider_areas = provider_areas.replace(area, '');
    
    provider.areas_added.val(provider_areas);
};

provider_rol.loadFormData = function (entity){
    provider_rol.id.val(entity.id);
    provider_rol.rol.val(entity.rol);
};

provider.loadFormData = function (entity, areas, nota){
    provider.prov_id.val(entity.prov_id);
    provider.prov_name.val(entity.prov_name);
    provider.prov_tel.val(entity.prov_tel);
    provider.prov_email.val(entity.prov_email);
    provider.prov_domicilio.val(entity.prov_domicilio);
    
    if(nota){
        provider.nota_id.val(nota.nota_id);
        provider.nota_prov_id.val(nota.nota_prov_id);
        provider.nota_garantia.val(nota.nota_garantia != 0 ? nota.nota_garantia : '');
        provider.nota_exp.val(nota.nota_exp != 0 ? nota.nota_exp : '');
        provider.nota_timing.val(nota.nota_timing != 0 ? nota.nota_timing : '');
        provider.nota_presup.val(nota.nota_presup != 0 ? nota.nota_presup : '');
        provider.nota_trust.val(nota.nota_trust != 0 ? nota.nota_trust : '');
        provider.nota_calidad.val(nota.nota_calidad != 0 ? nota.nota_calidad : '');
        provider.nota_total.val(nota.nota_total != 0 ? nota.nota_total : '');
    }
    
    if(areas.length > 0){
        $('._areas').append('<h5>Areas del proveedor</h5>')
    }
    
    for (x = 0; x < areas.length; x++) {
        provider.addArea(areas[x]['area_area']);
    }
};

maintenance.loadFormData = function (entity){
    maintenance.mant_id.val(entity.mant_id);
    maintenance.mant_domicilio.val(entity.mant_domicilio);
    maintenance.mant_prop.val(entity.mant_prop);
    maintenance.mant_inq.val(entity.mant_inq);
    maintenance.mant_prov_1.val(entity.mant_prov_1);
    maintenance.mant_prov_2.val(entity.mant_prov_2);
    maintenance.mant_prov_3.val(entity.mant_prov_3);
    maintenance.mant_desc.val(entity.mant_desc);
    maintenance.mant_monto.val(entity.mant_monto != 0 ? entity.mant_monto : '');
    maintenance.mant_date_deadline.val(entity.mant_date_deadline);
    maintenance.mant_date_end.val(entity.mant_date_end);
    maintenance.mant_calif.val(entity.mant_calif != 0 ? entity.mant_calif : '');
    // selects
    maintenance.mant_prioridad.val(entity.mant_prioridad);
    maintenance.mant_status.val(entity.mant_status);

    // pictures
    var pics = '';
    for (var i = entity.pictures.length - 1; i >= 0; i--) {
        if(entity.pictures[i].url.length){
            pics = pics + entity.pictures[i].url+',';
        }
    }
    
    if(pics.length){
        maintenance.pictures.val(pics);
        capturePics();
    }
};

var providers = [];
var $provider_field_clicked;
maintenance.chooseProv = function ($provider_field){
    modals.loadModalChooseProvider('Elige un proveedor'); 
    
    if(!providers.length){
        provider.getProviders();
    }else{
        maintenance.loadProviders();
    }
    
    $provider_field_clicked = $provider_field;
};

provider.getProviders = function () {
    general_scripts.ajaxSubmit(get_providers, {}, function(response){
        providers = response.providers;
        maintenance.loadProviders();
    });
};

maintenance.loadProviders = function (){
    general_scripts.cleanTable('proveedores');
    
    var table = {
        'table' : 'proveedores',
        'table_pk' : 'prov_id',
        'entity_name' : 'proveedor'
    }
    
    for (x = 0; x < providers.length; x++) {
        general_scripts.loadEntityToList(providers[x], table, true);
    }
};

maintenance.chooseProvider = function (provider_id){
    if($provider_field_clicked){
        var provider_chosen = maintenance.getProvider(provider_id);
        var provider_name = provider_chosen.prov_name;
        $provider_field_clicked.val(provider_name);
        modals.$chooseProviderModal.modal('hide');
    }
};

maintenance.getProvider = function (provider_id){
    for (var x = 0; x < providers.length; x++) {
        if(providers[x].id == provider_id){
            return providers[x];
            break;
        }
    }
};

$(function(){
    maintenance.init();
});
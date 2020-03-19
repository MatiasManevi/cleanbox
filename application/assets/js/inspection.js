
/*
 * Project: Cleanbox
 * Document: inspection
 * 
 * @author Manevi Matias Alejandro 
 * Informatic Engineer - Web Developer
 * 
 * Contact: manevimatias@gmail.com
 * 
 * All rights reserved ®
 */

var inspection = new Object();

inspection.init = function () {
    inspection.initComponents();

    // Mantenimientos searchers
    general_scripts.bindInputAutocomplete(inspection.address, 'propiedades', 'prop_id', 'prop_dom', false, function (response) {       
        inspection.property_id.val(response.id);
        inspection.address.prop('readonly', true);
    });
    general_scripts.bindInputAutocomplete(inspection.renter, 'clientes', 'client_id', 'client_name', false, function (response) {       
        inspection.renter_id.val(response.id);
        inspection.renter.prop('readonly', true);
    });
    general_scripts.bindDatepicker(inspection.date); 
    // show_inspection_report
};

// Inicializa componentes html
inspection.initComponents = function () {
    // Mantenimiento fields
    inspection.id = $('#id');
    inspection.address = $('#address');
    inspection.property_id = $('#property_id');
    inspection.renter = $('#renter');
    inspection.renter_id = $('#renter_id');
    inspection.momentum = $('#momentum');
    inspection.date = $('#date');
    inspection.description = $('#description');
    inspection.pictures = $('._pictures');

    inspection.image_listing = $('.image_listing');
};

inspection.loadFormData = function (entity){
    inspection.id.val(entity.id);
    inspection.address.val(entity.address);
    inspection.property_id.val(entity.property_id);
    inspection.renter.val(entity.renter);
    inspection.renter_id.val(entity.renter_id);
    inspection.description.val(entity.description);
    inspection.momentum.val(entity.momentum);
    inspection.date.val(entity.date);

    // pictures
    var pics = '';
    if(entity.pictures.length === 1){
        pics = entity.pictures[0].url;
    }else{
        for (var i = entity.pictures.length - 1; i >= 0; i--) {
            if(entity.pictures[i].url.length){
                pics = pics + entity.pictures[i].url+',';
            }
        }
    }
    
    if(pics.length){
        inspection.pictures.val(pics);
        capturePics();
    }
};

$(function(){
    inspection.init();
});
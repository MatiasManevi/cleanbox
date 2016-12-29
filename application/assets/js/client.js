
/*
 * Project: Cleanbox
 * Document: client
 * 
 * @author Manevi Matias Alejandro 
 * Informatic Engineer - Web Developer
 * 
 * Contact: manevimatias@gmail.com
 * 
 * All rights reserved ®
 */

var client = new Object();

client.init = function () {
    client.initComponents();
    general_scripts.bindInputListSearcher('clientes', 'client_id', 'cliente');
}; 

$(window).scroll(function(){ 
    // Recarga la lista cuando hay scroll down y el scroll bar llega a bottom    
    general_scripts.getEntitiesOnScrollDown('clientes','client_id','cliente','client_name');
});

// Inicializa componentes html
client.initComponents = function () {
    // Client fields
    client.client_id = $('#client_id');
    client.client_name = $('#client_name');
    client.client_cuit = $('#client_cuit');
    client.client_razon_vinculo = $('#client_razon_vinculo');
    client.client_tel = $('#client_tel');
    client.client_celular = $('#client_celular');
    client.client_email = $('#client_email');
    client.client_calle = $('#client_calle');
    client.client_nro_calle = $('#client_nro_calle');
    client.client_piso = $('#client_piso');
    client.client_dto = $('#client_dto');
    client.client_postal = $('#client_postal');
    client.client_localidad = $('#client_localidad');
    client.client_provincia = $('#client_provincia');
    client.client_comentario = $('#client_comentario');
    client.client_categoria = $('#client_categoria');
};

client.loadFormData = function(entity) {
    client.client_id.val(entity.client_id);
    client.client_name.val(entity.client_name);
    client.client_cuit.val(entity.client_cuit);
    client.client_razon_vinculo.val(entity.client_razon_vinculo);
    client.client_tel.val(entity.client_tel);
    client.client_celular.val(entity.client_celular);
    client.client_email.val(entity.client_email);
    client.client_calle.val(entity.client_calle);
    client.client_nro_calle.val(entity.client_nro_calle);
    client.client_piso.val(entity.client_piso);
    client.client_dto.val(entity.client_dto);
    client.client_postal.val(entity.client_postal);
    client.client_localidad.val(entity.client_localidad);
    client.client_provincia.val(entity.client_provincia);
    client.client_comentario.val(entity.client_comentario);
    client.client_categoria.find('option[value="' + entity.client_categoria.capitalize() + '"]').prop('selected', true);
};

$(function(){
    client.init();   
});

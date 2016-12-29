<div class="section">
    <div class="section_description">
        <label>Informe o historial de pagos de alquiler de un inquilino en particular, en un año determinado</label>
    </div>

    <form class="section_form" action="javascript:;" onsubmit="report.buildReport('<?php echo site_url('buildRenterPaymentHistorialReport') ?>', this);return false;" enctype="multipart/form-data"> 
        <input required type="text" name="year" required class="form-control _general_number_input_control ui-autocomplete-input section_input" placeholder="Año" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">   

        <input required type="text" class="form-control ui-autocomplete-input section_input _general_letters_input_control _search_input" placeholder="Inquilino" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">         
        <input type="hidden" name="client_id" class="form-control ui-autocomplete-input section_input _client_id">         

        <button class="btn btn-primary submit_button">Generar</button>
    </form>
</div>

<script>
    $(function(){
        general_scripts.bindInputAutocomplete($('._search_input'), 'clientes', 'client_id', 'client_name', false, function(response){
            $('._client_id').val(response.id);
        });
        $('._search_input').on('keypress','', function (key) {
            if((key.charCode < 97 || key.charCode > 122) && key.charCode != 0 && key.charCode != 32 && key.charCode != 8 && key.charCode != 9  && (key.charCode < 65 || key.charCode > 90)) return false;
   
            $('._client_id').val('');
        });
    });
</script>
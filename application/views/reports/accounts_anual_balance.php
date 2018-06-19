<div class="section">
    <div class="section_description">
        <label>Informe que lista todos los balances de las cuentas corrientes en cada mes del año</label>
    </div>

    <form class="section_form" action="javascript:;" onsubmit="report.buildReport('<?php echo site_url('buildAccountsAnualBalanceReport') ?>', this);return false;" enctype="multipart/form-data"> 

        <input required type="text" class="form-control ui-autocomplete-input section_input _general_letters_input_control _search_input" placeholder="Propietario" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">
        <input type="hidden" name="cc_id" class="form-control ui-autocomplete-input section_input _cc_id" placeholder="Propietario" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">    
        
        <input type="text" name="year" required class="form-control ui-autocomplete-input section_input" placeholder="Año" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">   

        <button class="btn btn-primary submit_button">Generar</button>
    </form>
</div>

<script>
    $(function(){
        general_scripts.bindInputAutocomplete($('._search_input'), 'cuentas_corrientes', 'cc_id', 'cc_prop', false, function(response){
            $('._cc_id').val(response.id);
        });
        $('._search_input').on('keypress','', function (key) {
            if((key.charCode < 97 || key.charCode > 122) && key.charCode != 0 && key.charCode != 32 && key.charCode != 8 && key.charCode != 9  && (key.charCode < 65 || key.charCode > 90)) return false;
   
            $('._cc_id').val('');
        });
    });
</script>
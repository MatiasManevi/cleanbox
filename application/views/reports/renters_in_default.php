<div class="section">
    <div class="section_description">
        <label>Informe de inquilinos morosos al dia de la Fecha</label>
    </div>

    <form class="section_form" action="javascript:;" onsubmit="report.buildReport('<?php echo site_url('buildRentersInDefaultReport') ?>', this);return false;" enctype="multipart/form-data"> 
        <input type="text" name="date" required class="form-control _defaulters_date _general_number_input_control _general_amount_input_control _general_letters_input_control ui-autocomplete-input section_input" placeholder="Fecha" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">   
        <button class="btn btn-primary submit_button">Generar</button>
    </form>
</div>

<script>
    $(function(){
        general_scripts.bindDatepicker($('._defaulters_date'), "D");
    });
</script>

<div class="section">
    <div class="section_description">
        <label>Informe Diario detallado de los movimientos en la Caja</label>
    </div>

    <form class="section_form" action="javascript:;" onsubmit="report.buildReport('<?php echo site_url('buildCashReport') ?>', this);return false;" enctype="multipart/form-data"> 
        <input type="text" name="date" required class="form-control _datepicker_filter _general_number_input_control _general_amount_input_control _general_letters_input_control ui-autocomplete-input section_input" placeholder="Fecha Caja" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">   

        <select class="form-control ui-autocomplete-input section_input" name="type">
            <option value="cash">Fisica</option>
            <option value="bank">Bancaria</option>
        </select>

        <button class="btn btn-primary submit_button">Generar</button>
    </form>
</div>

<div class="panel panel-primary cash_transfers">
    <div class="panel-heading">
        <h3 class="panel-title">Generador de codigos de autorizacion</h3>
    </div>
    <div class="panel-body">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="input-group">
                            <input type="text" class="form-control ui-autocomplete-input _code" placeholder="Código">
                            <span class="input-group-btn">
                                <button onclick="general_scripts.generateCode('<?php echo site_url('generateCode'); ?>');" class="btn btn-primary" type="button">Generar código</button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
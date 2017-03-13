<div class="panel panel-primary cash_flush">
    <div class="panel-heading">
        <h3 class="panel-title">Flujo de caja &nbsp; - &nbsp;<?php echo Date('d/m/Y'); ?></h3>
    </div>
    <div class="panel-body">
        <div class="panel panel-default margB33">
            <div class="panel-body">
                Estado inicial CAJA FISICA: <strong>$ <?php echo $begin_cash; ?></strong>
            </div>
        </div>
        <div class="panel panel-default margB33">
            <div class="panel-body">
                Estado actual CAJA FISICA: <strong>$ <span class="_cash"><?php echo $monthly_progressive; ?></span></strong>
            </div>
        </div>
        <div class="panel panel-default margB33">
            <div class="panel-body">
                Estado actual CAJA FUERTE: <strong>$ <span class="_safebox"><?php echo $safe_box; ?></span></strong>
            </div>
        </div>
    </div>
</div>

<div class="panel panel-primary cash_transfers">
    <div class="panel-heading">
        <h3 class="panel-title">Transferencias</h3>
    </div>
    <div class="panel-body">

        <div class="panel panel-default">
            <div class="panel-heading">Transferencia de caja fisica a caja fuerte</div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="input-group">
                            <input type="text" class="form-control _general_amount_input_control _transfer_to_safebox" placeholder="Monto a transferir">
                            <span class="input-group-btn">
                                <button onclick="transfers.transferToSafeBox();" class="btn btn-primary" type="button">Aceptar</button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">Transferencia de caja fuerte a caja fisica</div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="input-group">
                            <input type="text" class="form-control _general_amount_input_control _transfer_to_cash" placeholder="Monto a transferir">
                            <span class="input-group-btn">
                                <button onclick="transfers.transferToCash();" class="btn btn-primary" type="button">Aceptar</button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
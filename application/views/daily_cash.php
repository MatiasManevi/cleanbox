<div class="panel panel-primary cash_flush">
    <div class="panel-heading">
        <h3 class="panel-title">Flujo de caja &nbsp; - &nbsp;<?php echo Date('d/m/Y'); ?></h3>
    </div>
    <div class="panel-body">
        <div class="panel panel-default margB33">
            <div class="panel-body">
                Estado inicial CAJA FISICA: <strong class="_begin_cash">calculando...</strong>
            </div>
        </div>
        <div class="panel panel-default margB33">
            <div class="panel-body">
                Estado actual CAJA FISICA: <strong><span class="_cash">calculando...</span></strong>
            </div>
        </div>
        <!-- <div class="panel panel-default margB33">
            <div class="panel-body">
                Estado actual CAJA FUERTE: <strong>$ <span class="_safebox"><?php echo $safe_box; ?></span></strong>
            </div>
        </div> -->
    </div>
</div>
<!-- 
<div class="panel panel-primary cash_transfers">
    <div class="panel-heading">
        <h3 class="panel-title">Transferencias</h3>
    </div>
    <div class="panel-body">

        <div class="panel panel-default">
            <div class="panel-heading">Transferencia de caja fisica a caja fuerte</div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="input-group" style="float: left;">
                            <input style="margin-bottom: 3px" type="text" class="form-control _general_amount_input_control _transfer_to_safebox" placeholder="Monto a transferir">
                            <input style="margin-bottom: 3px" type="text" class="form-control _reason_transfer_to_safebox" placeholder="Razon de la transferencia">
                        </div>
                        <button style="float: right;" onclick="transfers.transferToSafeBox();" class="btn btn-primary" type="button">Aceptar</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">Transferencia de caja fuerte a caja fisica</div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="input-group" style="float: left;">
                            <input style="margin-bottom: 3px" type="text" class="form-control _general_amount_input_control _transfer_to_cash" placeholder="Monto a transferir">
                            <input style="margin-bottom: 3px" type="text" class="form-control _reason_transfer_to_cash" placeholder="Razon de la transferencia">
                        </div>
                        <button style="float: right;" onclick="transfers.transferToCash();" class="btn btn-primary" type="button">Aceptar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
 -->
<script>
    $(function(){
        $.ajax({
            url: '<?php echo site_url("deliveryReports"); ?>',
            type:'POST',
            dataType: 'json'
        });
    });
</script>


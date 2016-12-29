<div id="deleteTransactionModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" style="width: 95%;">
        <div class="modal-content">
            <div class="modal-header back_menu">
                <h3 class="modal-title text-center _title">Eliminar movimientos de transacción</h3>
                <h5 class="modal-title text-center _title">Al eliminar estos registros, tambien se elimina el impacto de los mismos a las cuentas corrientes afectadas</h5>
            </div>
            <div class="modal-body">
                <form class="_delete_transaction_form" action="javascript:;" enctype="multipart/form-data"> 
                    <h4 class="_delete_credits_title"></h4>
                    <table class="table table-hover _credits_delete_table">
                        <tr>    
                            <th>Depositante o Impositor</th>
                            <th>Cta. Cte.</th>
                            <th>Concepto</th>
                            <th>Monto</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </table>

                    <h4 class="_delete_debits_title"></h4>
                    <table class="table table-hover _debits_delete_table">
                        <tr>    
                            <th>Cta. Cte. Debitada</th>
                            <th>Concepto</th>
                            <th>Monto</th>
                            <th>Domicilio</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </table>
                    
                    <h4 class="_delete_services_control_title"></h4>
                    <table class="table table-hover _services_control_delete_table">
                        <tr>    
                            <th>Servicio controlado</th>
                            <th>Mes controlado</th>
                            <th>Fecha de control</th>
                            <th>Acciones</th>
                        </tr>
                    </table>
                    <div class="modal-footer">
                        <button class="btn btn-primary button_report" type="submit">Borrar Transacción</button>
                        <a class="btn btn-primary button_report" data-dismiss="modal">Cancelar</a>
                    </div>
                </form>
            </div> 
        </div>
    </div>
</div> 

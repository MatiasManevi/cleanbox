<h2><?php echo t('Transferencias') ?></h2>
<ul id="ccnav" class="nav nav-tabs">
    <li class="_list_tab_button active"><a href="#list-in" data-toggle="tab">Transferencias a Caja fuerte</a></li>
    <li><a href="#list-out" data-toggle="tab">Transferencias a Caja fisica</a></li>
</ul>

<div class="tab-content section">
    <!--  Transf. a Caja fuerte/a  -->
    <div class="tab-pane in active fade _list_entities" id="list-in">

        <div class="filter_container _transferencias_filter">
            <form action="javascript:;" onsubmit="general_scripts.filterByValues(this);" enctype="multipart/form-data">  
                <input autocomplete="off" name="from" title="Desde" placeholder="Desde" type="text" class="form-control filter_input _datepicker_filter">
                <input autocomplete="off" name="to" title="Hasta" placeholder="Hasta" type="text" class="form-control filter_input _datepicker_filter">      
                <input name="table" type="hidden" value="transferencias_to_safe">      
                <button class="btn btn-primary" type="submit">Filtrar</button>
                <a onclick="general_scripts.refreshList('transferencias_to_safe', 'transf_id', 'transf_id', 'transferencia')" href="javascript:;" class="refresh glyphicon glyphicon-refresh" title="Recargar lista"></a>
            </form>
        </div>

        <div class="_list">
            <table class="table table-hover _table _transferencias_to_safe_table">
                <tr>    
                    <th>Fecha</th>
                    <th>Monto</th>
                    <th>Tipo</th>
<!--                    <th>Acciones</th>-->
                </tr>
                <?
                if (count($transfers_to_safe)) {
                    foreach ($transfers_to_safe as $row) {
                        ?>
                        <tr class="_reg_entity_<?php echo $row['cred_id']; ?>">
                            <td><?php echo $row['cred_fecha']; ?></td>
                            <td>$ <?php echo $row['cred_monto']; ?></td>
                            <td><?php echo $row['cred_concepto']; ?></td>
        <!--                            <td>
                                <a title="Eliminar" onclick="modals.deleteEntityModal(<?php echo $row['cred_id']; ?>, 'creditos', 'cred_id', 'transferencia');" href="javascript:;" class="glyphicon glyphicon-trash"></a>
                            </td>-->
                        </tr>
                        <?
                    }
                } else {
                    ?>
                    <tr class="_no_records"><td colspan="100%"> No se encontraron registros </td></tr>
                <? } ?>
            </table>
        </div>
    </div>
    <!--  Transf. a Caja fisica  -->
    <div class="tab-pane fade" id="list-out">

        <div class="filter_container _transferencias_filter">
            <form action="javascript:;" onsubmit="general_scripts.filterByValues(this);" enctype="multipart/form-data">  
                <input autocomplete="off" name="from" title="Desde" placeholder="Desde" type="text" class="form-control filter_input _datepicker_filter">
                <input autocomplete="off" name="to" title="Hasta" placeholder="Hasta" type="text" class="form-control filter_input _datepicker_filter">      
                <input name="table" type="hidden" value="transferencias_to_cash">      
                <button class="btn btn-primary" type="submit">Filtrar</button>
                <a onclick="general_scripts.refreshList('transferencias_to_cash', 'transf_id', 'transf_id', 'transferencia')" href="javascript:;" class="refresh glyphicon glyphicon-refresh" title="Recargar lista"></a>
            </form>
        </div>

        <div class="_list">
            <table class="table table-hover _table _transferencias_to_cash_table">
                <tr>    
                    <th>Fecha</th>
                    <th>Monto</th>
                    <th>Tipo</th>
<!--                    <th>Acciones</th>-->
                </tr>
                <?
                if (count($transfers_to_cash)) {
                    foreach ($transfers_to_cash as $row) {
                        ?>
                        <?php
                        if ($row['deb_concepto'] == 'Eliminacion de credito') {
                            $title = 'Transf. creada automaticamente para nivelar la caja fisica ya que en la eliminacion de un credito la misma se quedo sin fondos y se requirio debitar el monto faltante a debitar en la CAJA FUERTE';
                        } else {
                            $title = '';
                        }
                        ?>
                        <tr  title="<?php echo $title ?>" class="_reg_entity_<?php echo $row['deb_id']; ?>">
                            <td><?php echo $row['deb_fecha']; ?></td>
                            <td>$ <?php echo $row['deb_monto']; ?></td>
                            <td><?php echo $row['deb_concepto']; ?></td>
        <!--                            <td>
                                <a title="Eliminar" onclick="modals.deleteEntityModal(<?php echo $row['deb_id']; ?>, 'debitos', 'deb_id', 'transferencia');" href="javascript:;" class="glyphicon glyphicon-trash"></a>
                            </td>-->
                        </tr>
                        <?
                    }
                } else {
                    ?>
                    <tr class="_no_records"><td colspan="100%"> No se encontraron registros </td></tr>
                <? } ?>
            </table>
        </div>
    </div>
</div>

<table class="table table-hover _table _conceptos_table">
    <tr>    
        <th>Concepto</th>
        <th>Percibe Interes</th>
        <th>Percibe Gestion de Cobro</th>
        <th>Percibe IVA</th>
        <th>Tipo</th>
        <th>Cuenta</th>
        <th>Acciones</th>
    </tr>
    <?
    if (count($concepts)) {
        foreach ($concepts as $row) {
            ?>
            <tr class="_reg_entity_<?php echo $row['conc_id']; ?>">
                <td><?php echo $row['conc_desc']; ?></td>
                <td><?php echo $row['interes_percibe'] ? 'Si' : 'No'; ?></td>
                <td><?php echo $row['gestion_percibe'] ? 'Si' : 'No'; ?></td>
                <td><?php echo $row['iva_percibe'] ? 'Si' : 'No'; ?></td>
                <td><?php echo $row['conc_tipo']; ?></td>
                <td><?php echo $row['conc_cc'] == 'cc_saldo' ? 'Cta. Principal' : 'Cta. Secundaria'; ?></td>
                <td>
                    <a title="Editar" onclick="general_scripts.loadEntityToEdit(<?php echo $row['conc_id']; ?>, 'conceptos', 'conc_id');" href="javascript:;" class="glyphicon glyphicon-edit"></a>
                    <a title="Eliminar" onclick="modals.deleteEntityModal(<?php echo $row['conc_id']; ?>, 'conceptos', 'conc_id', 'concepto');" href="javascript:;" class="glyphicon glyphicon-trash"></a>
                </td>
            </tr>
            <?
        }
    } else {
        ?>
        <tr class="_no_records"><td colspan="100%"> No se encontraron registros </td></tr>
    <? } ?>
</table>
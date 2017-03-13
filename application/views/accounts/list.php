<table class="table table-hover _table _cuentas_corrientes_table">
    <tr>    
        <th>Nombre</th>
        <th>Saldo cuenta</th>
<!--        <th>Saldo Cuenta Secundaria</th>-->
        <th>Acciones</th>
    </tr>
    <?php
    if (count($accounts)) {
        foreach ($accounts as $row) {
            if (!in_array($row['cc_prop'], array('INMOBILIARIA', 'CAJA FUERTE'))) {
                ?>
                <tr class="_reg_entity_<?php echo $row['cc_id']; ?>">
                    <td><?php echo $row['cc_prop']; ?></td>
                    <td>$ <?php echo round($row['cc_saldo'] + $row['cc_varios'], 2); ?></td>
<!--                    <td><?php // echo '$ ' . $row['cc_varios']; ?></td>-->
                    <td>
                        <?php if ($this->session->userdata('username') == 'admin') { ?>
                            <a title="Editar" onclick="general_scripts.loadEntityToEdit(<?php echo $row['cc_id']; ?>, 'cuentas_corrientes', 'cc_id');" href="javascript:;" class="glyphicon glyphicon-edit"></a>
                            <a title="Eliminar" onclick="modals.deleteEntityModal(<?php echo $row['cc_id']; ?>, 'cuentas_corrientes', 'cc_id', 'cuenta corriente');" href="javascript:;" class="glyphicon glyphicon-trash"></a>
                        <?php } ?>
                    </td>
                </tr>
                <?php
            }
        }
    } else {
        ?>
        <tr class="_no_records"><td colspan="100%"> No se encontraron registros </td></tr>
    <?php } ?>
</table>
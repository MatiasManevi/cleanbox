<form style="overflow: visible;" action="javascript:;" onsubmit="request_post('<?= site_url('manager/delete_transact/' . $trans . '/' . $tipo_transaccion) ?>',this,'.contenedor_centro');return false;" enctype="multipart/form-data"> 

    <h2 style="font-size: 19px;">Creditos de Transaccion nro: <?= $trans ?></h2>
    <table class="table table-hover">
        <tr>    
            <th>Depositante o Impositor</th>
            <th>Cta. Cte.</th>
            <th>Concepto</th>
            <th>Monto</th>
            <th>Fecha</th>
            <th>Interes</th>
            <th>Acciones</th>
        </tr>
        <?
        if ($creditos->num_rows() > 0) {
            foreach ($creditos->result() as $credito) {
                echo '<tr class="reg_' . $credito->cred_id . '">';
                echo '<td>' . $credito->cred_depositante . '</td>';
                echo '<td>' . $credito->cred_cc . '</td>';
                echo '<td>' . $credito->cred_concepto . ' (' . $credito->cred_mes_alq . ')' . '</td>';
                echo '<td>$ ' . $credito->cred_monto . '</td>';
                echo '<td>' . $credito->cred_fecha . '</td>';
                echo '<td>por ' . $credito->cred_interes . ' dias</td>';
                echo '<td>';
                echo '<a href="javascript:;" class="glyphicon glyphicon-trash" onclick="del(\'' . $credito->cred_id . '\',\'' . site_url('manager/del_creditos/' . $credito->cred_id) . '\')"></a>  ';
                echo '</tr>';
            }
        } else {
            echo '<tr><td colspan="100%">' . t('No records found.') . '</td></tr>';
        }
        ?>
    </table>

    <h2 style="font-size: 19px;">Debitos de Transaccion nro: <?= $trans ?></h2>
    <table class="table table-hover">
        <tr>    
            <th>Cta. Cte. Debitada</th>
            <th>Concepto</th>
            <th>Monto</th>
            <th>Fecha</th>
            <th>Acciones</th>
        </tr>
        <?
        if ($debitos->num_rows() > 0) {
            foreach ($debitos->result() as $debito) {
                echo '<tr class="reg_' . $debito->deb_id . '">';
                echo '<td>' . $debito->deb_cc . '</td>';
                echo '<td>' . $debito->deb_concepto . '</td>';
                echo '<td>$ ' . $debito->deb_monto . '</td>';
                echo '<td>' . $debito->deb_fecha . '</td>';
                echo '<td>';
                echo '<a href="javascript:;" class="glyphicon glyphicon-trash" onclick="del(\'' . $debito->deb_id . '\',\'' . site_url('manager/del_debitos/' . $debito->deb_id) . '\')"></a>  ';
                echo '</tr>';
            }
        } else {
            echo '<tr><td colspan="100%">' . t('No records found.') . '</td></tr>';
        }
        ?>
    </table>
    <div style="float:left">
        <button class="btn btn-primary" type="submit" id="buttons_cli" style="margin-left:11px;float: left; line-height: 0;"><?= 'Borrar Transaccion' ?></button>
        <span id="spans" type="button" class="btn btn-default btn-lg">
            <a style=" margin: 0 auto;" onclick="$('#back_fader1').hide();$('#popup1').hide();"id="buttons_cli" class="btn" href="javascript:;"><?= 'Cancelar' ?></a>
        </span>
    </div>    
</form>

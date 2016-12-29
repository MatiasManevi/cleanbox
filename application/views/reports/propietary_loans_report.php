<div id="printable" class="report_sheet _excel">
    <div id="non-printable" class="report_actions">
        <a class="btn btn-primary button_report" href="javascript:;" onclick="window.print();return false;">Imprimir</a>
        <a class="btn btn-primary button_report" href="<?php echo site_url('propietaryLoansReport') ?>">Volver</a>
    </div>
    <h4 style="float:left;">Posadas, Misiones</h4>           
    <h4 style="float:right;"><?php echo date('d-m-Y') ?></h4>
    <h4>Prestamos a Propietarios<?php echo ' (' . $from . ' a ' . $to . ')' ?></h4>

    <table class="table">
        <tr><th class="cell" colspan="4">Prestamos en Mora</th></tr>
        <tr>    
            <th class="cell">Prestado A</th>
            <th class="cell">Monto</th>
            <th class="cell">Fecha</th>                   
            <th class="cell">Mes</th>
        </tr> 
        <?php if (count($default_loans) > 0) { ?>
            <?php foreach ($default_loans as $default_loan) { ?>
                <tr class="reg_<?php echo $default_loan['cred_id']; ?>">
                    <td class="cell"><?php echo $default_loan['cred_cc']; ?></td>
                    <td class="cell">$ <?php echo $default_loan['cred_monto']; ?></td>
                    <td class="cell"><?php echo $default_loan['cred_fecha']; ?></td>
                    <td class="cell"><?php echo $default_loan['cred_mes_alq']; ?></td>
                </tr>

            <?php } ?>
            <tr>
                <td class="cell">Total Prestamos en Mora</td>
                <td class="cell"> $ <?php echo $total_loans; ?></td>
            </tr>
        <?php } else { ?>
            <tr><td colspan="4" class="cell">No se encontraron registros en las fechas indicadas</td></tr>
        <?php } ?>
        <tr>
            <td></td>
            <td></td>
        </tr>
    </table>

    <table class="table">
        <tr><th class="cell" colspan="4">Prestamos Devueltos</th></tr>
        <tr>    
            <th class="cell">Prestado A</th>
            <th class="cell">Monto</th>
            <th class="cell">Fecha</th>                   
            <th class="cell">Mes</th>
        </tr>
        <?php if (count($returned_loans) > 0) { ?>
            <?php foreach ($returned_loans as $returned_loan) { ?>
                <tr class="reg_<?php echo $returned_loan['cred_id']; ?>">
                    <td class="cell"><?php echo $returned_loan['cred_cc']; ?></td>
                    <td class="cell">$ <?php echo $returned_loan['cred_monto']; ?></td>
                    <td class="cell"><?php echo $returned_loan['cred_fecha']; ?></td>
                    <td class="cell"><?php echo $returned_loan['cred_mes_alq']; ?></td>
                </tr>

            <?php } ?>
            <tr>
                <td class="cell">Total Prestamos Devueltos</td>
                <td class="cell"> $ <?php echo $total_loans_returned; ?></td>
            </tr>
        <?php } else { ?>
            <tr><td colspan="4" class="cell">No se encontraron registros en las fechas indicadas</td></tr>
        <?php } ?>
        <tr>
            <td></td>
            <td></td>
        </tr>
    </table>
</div>

<script>
    $(function(){
        $("._excel").tableExport({
            formats: ["xlsx"],  
            position: 'bottom',
            fileName: "Prestamos a Propietarios<?php echo ' (' . $from . ' a ' . $to . ')' ?>"
        });
    });
</script>


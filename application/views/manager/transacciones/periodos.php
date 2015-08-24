<div class="tablas">
    <table style="margin-bottom: 0px"class="table">
        <tr>    
            <th class="centrar">Periodo</th>
            <th class="centrar">Monto</th>
        </tr>
        <?
        if ($periodos->num_rows() > 0) {
            foreach ($periodos->result() as $periodo) {

                echo '<tr class="reg_' . $periodo->per_id . ' ' . ($periodo->per_id == $pintar ? 'pintar' : '') . '">';
                echo '<td class="centrar">' . $periodo->per_inicio . ' | ' . $periodo->per_fin . '</td>';
                echo '<td class="centrar"> $ ' . $periodo->per_monto . '</td>';
            }
        } else {
            echo '<tr><td colspan="100%">' . t('Periodos no Ingresados.') . '</td></tr>';
        }
        ?>

    </table>
</div>
<script>
    $(function() {var meses = [
            'Enero ' + <?= Date('Y') ?>,
            'Febrero ' +  <?= Date('Y') ?>,
            'Marzo ' + <?= Date('Y') ?> ,
            'Abril ' + <?= Date('Y') ?> ,
            'Mayo ' + <?= Date('Y') ?>,
            'Junio ' + <?= Date('Y') ?>,
            'Julio ' + <?= Date('Y') ?>,
            'Agosto ' + <?= Date('Y') ?>,
            'Septiembre ' + <?= Date('Y') ?>,
            'Octubre ' + <?= Date('Y') ?>,
            'Noviembre ' + <?= Date('Y') ?>,             
            'Diciembre ' + <?= Date('Y') ?>,             
        ];
        $( "#mes" ).autocomplete({
            source: meses
        });  
    });
</script>
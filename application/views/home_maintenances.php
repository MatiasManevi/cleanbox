<?php if (count($maintenances) > 0) { ?>
    <div class="panel panel-primary home_maintenances">
        <div class="panel-heading">
            <h3 class="panel-title">Mantenimientos en proceso, ordenados por fecha y resaltados por prioridad</h3>
        </div>
        <div class="panel-body panel-body">
            <?php foreach ($maintenances as $maintenance) { ?>
                <div class="panel panel-default">
                    <div class="row">
                        <div class="col-lg-12">
                            <?php
                            if ($maintenance['priority'] == 1) {
                                $class = 'priority-maximum';
                            } elseif ($maintenance['priority'] == 2) {
                                $class = 'priority-medium';
                            } elseif ($maintenance['priority'] == 3) {
                                $class = 'priority-low';
                            }
                            ?>
                            <div class="alert <?php echo $class ?> margin0">
                                <span>
                                    Domicilio: <?php echo $maintenance['address']; ?>
                                    <br>Fecha limite: <?php echo ($maintenance['deadline_date'] ? Date('d-m-Y', $maintenance['deadline_date']) : 'Sin definir'); ?>
                                    <br>Proveedores: <?php echo $maintenance['provider']; ?>
                                </span>
                                <button onclick="report.buildReportFromList('<?php echo site_url('maintenanceReport') ?>', <?php echo $maintenance['id']; ?>);" href="javascript:;" class="btn btn-primary floatRight">Ver más</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
<?php } ?>
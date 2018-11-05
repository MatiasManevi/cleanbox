<div id="non-printable" class="navbar navbar-inverse navbar-fixed-top">
    <div role="navigation" class="navbar navbar-inverse navbar-fixed-top">
        <div class="container-fluid">
            <div class="navbar-header">
                <a href="<?php echo site_url('home') ?>" class="navbar-brand">
                    <i class="glyphicon glyphicon-home" style="color: white;margin-right: 5px;font-size: 15px;"></i>
                </a>
            </div>
            <div class="navbar-collapse collapse">
                <ul class="nav navbar-nav center_header">
                    <li><a href="javascript:;">Propietarios</a>
                        <ul class="sub">
                            <li><a href="<?php echo site_url('accounts') ?>">Cuentas Corrientes</a></li>                                  
                            <li><a href="<?php echo site_url('contracts') ?>">Contratos</a></li>                                  
                            <li><a href="<?php echo site_url('properties') ?>">Propiedades</a></li>                                  
                            <li><a href="<?php echo site_url('comentaries') ?>">Comentarios</a></li>  
                            <li><label class="reports_header_separator">Reportes</label></li>
                            <li><a href="<?php echo site_url('accountReport') ?>">Reporte Cta. Cte. Propietario</a></li>
                            <!-- <li><a href="<?php echo site_url('propietaryRenditionsReport') ?>">Reporte Rendiciones a Propietarios</a></li>               -->
                            <li><a href="<?php echo site_url('accountsBalanceReport') ?>">Reporte Mensual de Balance</a></li>              
                            <li><a href="<?php echo site_url('accountFlushReport') ?>">Reporte por periodos de Balance</a></li>                    
                            <li><a href="<?php echo site_url('accountsAnualBalanceReport') ?>">Reporte Anual de Balances</a></li>         
                            <li><a href="<?php echo site_url('propietaryLoansReport') ?>">Reporte Prestamos a Propietarios</a></li> 
                        </ul>
                    </li>
                    <li><a href="<?php echo site_url('clients') ?>">Clientes</a></li>              
                    <li><a href="javascript:;">Mantenimiento</a>
                        <ul class="sub">
                            <li><a href="<?php echo site_url('providers') ?>">Proveedores</a></li>              
                            <li><a href="<?php echo site_url('maintenances') ?>">Mantenimientos</a></li>              
                            <li><a href="<?php echo site_url('providersRols') ?>">Areas de Proveedores</a></li>              
                        </ul>
                    </li>
                    <li><a href="javascript:;">Transacciones</a>
                        <ul class="sub">
                            <li><a href="<?php echo site_url('credits') ?>">Creditos</a></li>                                   
                            <li><a href="<?php echo site_url('debits') ?>">Debitos</a></li>              
                            <li><a href="<?php echo site_url('migrations') ?>">Migrar</a></li>   
                            <li><a href="<?php echo site_url('concepts') ?>">Conceptos</a></li>                                  
                        </ul>
                    </li>
                    <li><a href="javascript:;">Reportes</a>
                        <ul class="sub">
                            <li><a href="<?php echo site_url('cashReport') ?>">Caja Diaria Detallada</a></li> 
                            <li><a href="<?php echo site_url('outmonthTransactionsReport') ?>">Transacciones Outmonth</a></li> 
                            <li><label class="reports_header_separator">Mantenimientos</label></li>
                            <li><a href="<?php echo site_url('endedMaintenancesReport') ?>">Historial Mantenimientos</a></li>              
                            <li><label class="reports_header_separator">Inquilinos</label></li>
                            <li><a href="<?php echo site_url('renterPaymentHistorialReport') ?>">Historial de Pagos Inquilino</a></li>              
                            <li><a href="<?php echo site_url('rentersInDefaultReport') ?>">Inquilinos Morosos</a></li>                            
                            <li><a href="<?php echo site_url('rentersPaymentPercentReport') ?>">Porcentaje cobro a Inquilinos</a></li>                            
                            <li><label class="reports_header_separator">Estadísticas</label></li>
                            <li><a href="<?php echo site_url('generalBalanceReport') ?>">Balance General</a></li>              
                            <!-- <li><a href="<?php echo site_url('pendingRenditionsReport') ?>">Rendiciones Pendientes</a></li>               -->
                            <li><a href="<?php echo site_url('renditionsPercentReport') ?>">Porcentaje Rendiciones efectuadas</a></li>
                            <li><a href="<?php echo site_url('contractsDeclinationReport') ?>">Vencimientos de Contratos</a></li>
                            <li><a href="<?php echo site_url('allConceptsMovementsReport') ?>">General de Conceptos</a></li>              
                            <li><a href="<?php echo site_url('bankTransactionsReport') ?>">Transacciones Bancarias</a></li>              
                            <li><a href="javascript:;" onclick="report.buildReport('<?php echo site_url('buildHonoraryPaymentsReport') ?>');">Reporte Pago Honorarios</a></li>              
                        </ul>
                    </li>  
                    <li><a href="<?php echo site_url('transfers') ?>">Transferencias</a></li>                                  
                    <li><a href="<?php echo site_url('users') ?>">Usuarios</a></li> 
                    <li style="margin-left: 170px;"><a style="color:white;padding: 0px;"><span style="float: right;margin-top: 15px;">En sesion: <strong><?php echo $this->session->userdata('username'); ?></strong></span></a></li>  
                    <li id="notifications">
                        <a title="Notificaciones" href="javascript:;" style="color:white;padding: 0px;">
                            <i class="glyphicon glyphicon-bell header_user_icon">
                                <div class="number _number">0</div>
                            </i>
                        </a>
                        <div class="notifications _notifications">
                            <div class="closing_all _closing_all col-lg-12">
                                <a href="javascript:;" onclick="notifications.removeAll();">Eliminar todas</a>
                            </div>
                            <div class="no_notifications col-lg-12">
                                <div class="col-lg-12"><small>0 notificaciones de deudas</small></div>
                            </div>
                        </div>
                    </li>
                    <?php if ($this->session->userdata('username') == 'admin') { ?>
                    <li style="width: 35px;"><a href="<?php echo site_url('settings') ?>" style="color:white;padding: 0px;"><i title="Configuraciones" class="glyphicon glyphicon-cog header_user_icon"></i></a></li>
                    <?php } ?>
                    <li><a href="<?php echo site_url('logout') ?>" style="color:white;padding: 0px;" title="Salir"><i class="glyphicon glyphicon-log-out header_user_icon"></i></a></li>
                </ul>
            </div><!--/.nav-collapse -->
        </div>
    </div>
</div>
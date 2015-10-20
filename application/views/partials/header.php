<style>
    body {
        background: Menu;
        padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
    }
</style>
<div id="non-printable" class="navbar navbar-inverse navbar-fixed-top">
    <div role="navigation" class="navbar navbar-inverse navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <a id="project" href="<?= site_url('manager') ?>" class="navbar-brand">Inicio</a>
            </div>
            <div class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                    <li class="<?= isset($uri) && $uri == 'transacciones' ? 'active' : '' ?>"><a href="javascript:;">Propietarios</a>
                        <ul>
                            <li class="<?= isset($uri) && $uri == 'contratos' ? 'active' : '' ?>"><a href="<?= site_url('manager/contratos') ?>">Contratos</a></li>                                  
                            <li class="<?= isset($uri) && $uri == 'propiedades' ? 'active' : '' ?>"><a href="<?= site_url('manager/propiedades') ?>">Propiedades</a></li>                                  
                            <li class="<?= isset($uri) && $uri == 'comentarios' ? 'active' : '' ?>"><a href="<?= site_url('manager/comentarios') ?>">Comentarios</a></li>  
                        </ul>
                    </li>
                    <li><a href="<?= site_url('manager/clientes') ?>">Clientes</a></li>              
                    <li class="<?= isset($uri) && $uri == 'transacciones' ? 'active' : '' ?>"><a href="javascript:;">Mantenimiento</a>
                        <ul>
                            <li><a href="<?= site_url('manager/proveedores') ?>">Proveedores</a></li>              
                            <li><a href="<?= site_url('manager/mantenimientos') ?>">Mantenimientos</a></li>              
                        </ul>
                    </li>
                    <li class="<?= isset($uri) && $uri == 'transacciones' ? 'active' : '' ?>"><a href="javascript:;">Transacciones</a>
                        <ul>
                            <li><a href="<?= site_url('manager/creditos') ?>">Creditos</a></li>                                   
                            <li><a href="<?= site_url('manager/debitos') ?>">Debitos</a></li>              
                            <li><a href="<?= site_url('manager/migrar') ?>">Migrar</a></li>   
                            <li class="<?= isset($uri) && $uri == 'conceptos' ? 'active' : '' ?>"><a href="<?= site_url('manager/conceptos') ?>">Conceptos</a></li>                                  
                        </ul>
                    </li>
                    <li><a href="javascript:;">Reportes</a>
                        <ul>
                            <li><a href="<?= site_url('manager/caja_detallada') ?>">Caja Diaria Detallada</a></li> 
                            <li><label class="tit">Propietarios</label></li>
                            <li><a href="<?= site_url('manager/reporte_prop') ?>">Informe Cta. Cte. Propietario</a></li>
                            <li><a href="<?= site_url('manager/por_cobrar') ?>">Informe General Ctas. Propietarios</a></li>              
                            <li><a href="<?= site_url('manager/prestamos') ?>">Informe Prestamos a Propietarios</a></li> 
                            <li><label class="tit">Inquilinos</label></li>
                            <li><a href="<?= site_url('manager/reporte_historial') ?>">Informe Historial de Pagos Inquilino</a></li>              
                            <li><a href="<?= site_url('manager/reporte_morosos') ?>">Informe Inquilinos Morosos</a></li>                            
                            <li><label class="tit">Estadísticas</label></li>
                            <li><a href="<?= site_url('manager/rendiciones_pendientes') ?>">Informe Rendiciones Pendientes</a></li>              
                            <li><a href="<?= site_url('manager/porcentaje_rendiciones') ?>">Informe Porcentaje Rendiciones</a></li>
                            <li><a href="<?= site_url('manager/vencimientos') ?>">Informe Vencimientos Contratos</a></li>
                            <li><a href="<?= site_url('manager/mensual') ?>">Informe General por Conceptos</a></li>              
                            <li><a href="<?= site_url('manager/bancarias') ?>">Informe Transacciones Bancarias</a></li>              
                        </ul>
                    </li>  
                    <li class="<?= isset($uri) && $uri == 'transferencias' ? 'active' : '' ?>"><a href="<?= site_url('manager/transferencias') ?>">Caja Fuerte</a></li>                                  
                    <li class="<?= isset($uri) && $uri == 'admin' ? 'active' : '' ?>"><a href="<?= site_url('manager/admin') ?>">Usuarios</a></li>
                    <li><div style="color:white;float: right;margin-left: 15px;margin-top: 11px;">Logged: <span style="color:#FFFAFA;font-size: 19px;"><?= $this->session->userdata('username') ?></span></div></li>                  
                    <li><a style="float:right"href="<?= site_url('logout') ?>">Salir</a></li>
                </ul>
            </div><!--/.nav-collapse -->
        </div>
    </div>
</div>








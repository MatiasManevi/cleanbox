<style>
    body {
        background: Menu;
        padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
    }
</style>
<div id="non-printable"class="navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container">
            <a data-target=".nav-collapse" data-toggle="collapse" class="btn btn-navbar">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
            <a id="project" href="<?= site_url('manager') ?>" class="brand">DyA</a>
            <div class="nav-collapse collapse">
                <ul class="nav">
                    <li class="<?= isset($uri) && $uri == 'arbitros' ? 'active' : '' ?>"><a href="<?= site_url('manager/arbitros') ?>">Arbitros</a></li>
                    <li class="<?= isset($uri) && $uri == 'designaciones' ? 'active' : '' ?>"><a href="<?= site_url('manager/designaciones') ?>">Designaciones</a></li>
                    <li class="<?= isset($uri) && $uri == 'aranceles' ? 'active' : '' ?>"><a href="<?= site_url('manager/aranceles') ?>">Aranceles</a></li>
                    <li class="<?= isset($uri) && $uri == 'recaudaciones' ? 'active' : '' ?>"><a href="<?= site_url('manager/recaudaciones') ?>">Recaudaciones</a></li>
                    <li class="<?= isset($uri) && $uri == 'admin' ? 'active' : '' ?>"><a href="<?= site_url('manager/admin') ?>">Usuario</a></li>
                    <li><a style="float:right"href="<?= site_url('logout') ?>">Salir</a></li>
                </ul>
            </div><!--/.nav-collapse -->
        </div>
    </div>
</div>





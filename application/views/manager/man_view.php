<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="es" xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <?= isset($head) ? $head : ''; ?>
    </head>
    <body>
        <div id="wrapper">
            <div >
                <?= isset($menu) ? $menu : ''; ?>          
            </div>
            <div class="contenedor_centro">
                <?= isset($content) ? $content : ''; ?>

            </div>
            <?= isset($caja) ? $caja : 'Error al cargar valores'; ?>
            <?= isset($codes) ? $codes : 'Error al cargar valores'; ?>
        </div>
    </body>
</html>
<div id="back_fader1">
    <div id="popup1">
    </div>
</div>
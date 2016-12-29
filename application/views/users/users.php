<h2><?php echo t('Usuarios') ?></h2>
<ul id="ccnav" class="nav nav-tabs">
    <li class="active _add_tab_button"><a href="#add" data-toggle="tab">Agregar Nuevo</a></li>
    <li class="_list_tab_button"><a href="#list" data-toggle="tab">Lista de Usuarios</a></li>
</ul>

<div class="tab-content section">
    <!--  Crear Cuenta  -->
    <div class="tab-pane fade in active _add" id="add">
        <div class="section_description">
            <label>Unicamente el usuario admin puede crear, modificar y dar de bajas todas las cuentas. Los demas usuarios solo pueden gestionar la informacion de su propia cuenta</label>
        </div>
        <form class="section_form" action="javascript:;" onsubmit="general_scripts.saveEntity('<?php echo site_url('saveUser') ?>', this);return false;" enctype="multipart/form-data"> 
            <input required type="text" name="username" class="form-control ui-autocomplete-input section_input" id="username" title="Usuario" placeholder="Usuario" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">
            <input required type="text" name="password" class="form-control ui-autocomplete-input section_input" id="password" title="Clave" placeholder="Clave" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">
            <input id="id" name="id" type="hidden"/>

            <button class="btn btn-primary submit_button _save_button" type="submit">Crear</button>
            <a class="btn btn-primary clear_button" onclick="general_scripts.cleanAddTab('man_users');">Resetear campos</a>
        </form>
    </div>

    <!--  Lista de Cuentas Corrientes -->
    <div class="tab-pane fade _list_entities" id="list">
        <div class="_list">
            <?php echo isset($list) ? $list : '' ?>
        </div>
    </div>
</div>
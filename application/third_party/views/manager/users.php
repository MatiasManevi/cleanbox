<div class="contenedor_centro">
    <h2><?= t('Usuario') ?></h2>
    <div class="actions_container">
        <span style="margin-top: 0px;" class="alert alert-info">Cambiar contraseña</span>
        <form action="javascript:;" onsubmit="request_post('<?= site_url('save_password')?>',this,'.msg_display')" enctype="multipart/form-data">
            <div id="user_cont" class="field buscar">
                <input name="actual" type="password" placeholder="Contraseña actual"/>
                <input name="nueva" type="password" placeholder="Contraseña nueva"/>
                <input name="nueva_c"type="password" placeholder="Confirme Contraseña"/>
            </div>
            <div id="user_cont">
                <button id="buttons_cli" class="btn btn-primary">Aceptar</button>
                <a id="buttons_cli" class="btn" href="<?= site_url('') ?>">Cancelar</a>
            </div>
        </form>
        <div class="alert alert-error msg_display"></div>
    </div>
</div>
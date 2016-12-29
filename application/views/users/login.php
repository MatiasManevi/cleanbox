<?php echo isset($head) ? $head : '' ?>
<link rel="stylesheet" href="<?php echo asset_url(); ?>css/login.css?<?php echo filemtime('application/assets/css/login.css') ?>"/>

<body>
    
    <div class="container">
        
        <form role="form" action="<?php echo site_url("make_login"); ?>" method="post" class="form-signin">
            <h2 class="form-signin-heading">Por favor, ingrese</h2>

            <input name="user" type="user" placeholder="Usuario" class="form-control">
            <input name="password" type="password" placeholder="Password" class="form-control">

            <button class="btn btn-lg btn-primary btn-block" type="submit" style="height: 47px;">Entrar</button>
        </form>

        <p class="error"><?php echo $message; ?></p>

    </div>
    
</body>
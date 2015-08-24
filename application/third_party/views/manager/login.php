<html lang="es" xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <style type="text/css">
            body {
                padding-top: 40px;
                padding-bottom: 40px;
                background-color: #f5f5f5;
            }

            .form-signin {
                margin: 0 auto 20px;
                max-width: 296px;
                overflow: hidden;
                padding: 19px 80px 44px;
                overflow: hidden;
                background-color: buttonface;
                border: 1px solid #e5e5e5;
                -webkit-border-radius: 5px;
                -moz-border-radius: 5px;
                border-radius: 5px;
                -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
                -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
                box-shadow: 0 1px 2px rgba(0,0,0,.05);
            }
            .form-signin .form-signin-heading,
            .form-signin .checkbox {
                margin-bottom: 10px;
                float: left;
            }
            .form-signin h2{
                font-size: 27.5px;
            }
            .form-signin input[type="text"],
            .form-signin input[type="password"] {
                font-size: 16px;
                height: auto;
                margin-bottom: 15px;
                padding: 7px 9px;
                width: 287px;
            }
        </style>
        <?= $head ?>
        <title>Login - DyA</title>
    </head>
    <body>
        <div  class="container">         
            <form class="form-signin" action="<?=site_url("make_login");?>" method="post">
                <h2 class="form-signin-heading">Por favor, ingrese</h2>
                <input name="user" type="text" placeholder="Usuario" class="input-block-level"/>
                <input name="password" type="password" placeholder="Contraseña" class="input-block-level"/>
                <button style="text-transform: none;height: 45px;width: 150px;"type="submit" class="btn btn-large btn-primary">Entrar</button>
            </form>
            <p class="error"><?=isset($msg) ? $msg : ''?></p>
        </div>
    </body>
</html>
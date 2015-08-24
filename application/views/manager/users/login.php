<?= isset($head) ? $head : ''?>
<style>
    body {
        padding-top: 40px;
        padding-bottom: 40px;
        background-color: #eee;
    }
    .form-signin {
        max-width: 330px;
        padding: 15px;
        margin: 0 auto;
    }
    .form-signin .form-signin-heading,
    .form-signin .checkbox {
        margin-bottom: 10px;
    }
    .form-signin .checkbox {
        font-weight: normal;
    }
    .form-signin .form-control {
        position: relative;
        height: auto;
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
        padding: 10px;
        font-size: 16px;
    }
    .form-signin .form-control:focus {
        z-index: 2;
    }
    .form-signin input[type="email"] {
        margin-bottom: -1px;
        border-bottom-right-radius: 0;
        border-bottom-left-radius: 0;
    }
    .form-signin input[type="password"] {
        margin-bottom: 10px;
        border-top-left-radius: 0;
        border-top-right-radius: 0;
    } 
</style>
<body>
    <div class="container">
        <form role="form" action="<?= site_url("make_login"); ?>" method="post" class="form-signin">
            <h2 class="form-signin-heading">Por favor, ingrese</h2>
            <input name="user" type="user" placeholder="Usuario" class="form-control">
            <input name="password" type="password" class="form-control" placeholder="Password" style="width: 299px;">
            <button class="btn btn-lg btn-primary btn-block" type="submit" style="height: 47px;">Entrar</button>
        </form>
        <p class="error"><?= isset($msg) ? $msg : '' ?></p>
    </div>
</body>
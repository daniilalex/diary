<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.1/css/bulma.min.css">
    <script src = "https://use.fontawesome.com/releases/v5.1.0/js/all.js"></script>

    <title>Document</title>
</head>
<body>
<div class="form" style="width: 600px; margin: 40px auto; height: 400px; background-color: #1a547678">
    <div class="form_container" style="width: 400px;padding: 60px 60px;height: 300px; margin: 40px auto;">
    <h1 style="margin: 20px auto; font-size: 22px;font-weight: bold">Log in to your diary</h1>
        <form action="<?= base_url('/auth/login') ?>" method="post">
            <div class="field">
                <p class="control has-icons-left has-icons-right">
                    <input class="input is-hovered" type="email" name="email" placeholder="email">
                    <span class="icon is-small is-left">
      <i class="fas fa-envelope"></i>
    </span>
                    <span class="icon is-small is-right">
      <i class="fas fa-check"></i>
    </span>
                </p>
            </div>
            <div class="field">
                <p class="control has-icons-left">
                    <input class="input is-hovered" type="password" name="password" placeholder="password">
                    <span class="icon is-small is-left">
      <i class="fas fa-lock"></i>
    </span>
                </p>
            </div>
            <div class="field">
                <p class="control">
                    <button class="button is-info">
                        Login
                    </button>
                </p>
            </div>
        </form>
        <? if(isset($errors)){ ?>
            <?= $errors ?>
        <? } ?>
    </div>

</div>

</body>
</html>


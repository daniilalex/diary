<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.1/css/bulma.min.css">
    <script src="https://use.fontawesome.com/releases/v5.1.0/js/all.js"></script>
    <title>Document</title>
</head>
<body>
<? if (isset($class)) { ?>
    <div class="content is-normal" style="width: 600px; border: #31708f 1px solid;margin: 40px 0 0 20px;padding: 10px">
        <h2>My class : <?= $class['title'] ?></h2>
        <h2>My teacher : <?= $teacher['firstname'] ?> <?= $teacher['lastname']?></h2>
    </div>
<? } ?>
<hr>
<div class="button" style="margin: 35px 0 0 20px">
    <a href="<?= base_url('/Auth/log_out/') ?>">Log Out</a>
</div>

</body>
</html>


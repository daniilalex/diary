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

<? if (isset($errors)) { ?>
    <?= $errors ?>
<? } ?>
<? if (isset($success)) { ?>
    <?= $success ?>
<? } ?>
<hr>

<ul>
    <div class="button" style="margin: 35px 0 0 20px">
        <li>
            <a href="<?=base_url('/Director/teachers/')?>">Teachers</a>
        </li>
    </div>
    <div class="button" style="margin: 35px 0 0 20px">
        <li>
            <a href="<?=base_url('/Director/students/')?>">Students</a>
        </li>
    </div>
    <div class="button" style="margin: 35px 0 0 20px">
        <li>
            <a href="<?=base_url('/Director/classes/')?>">Classes</a>
        </li>
    </div>
    <div class="button" style="margin: 35px 0 0 20px">
        <li>
            <a href="<?=base_url('/Director/lessons/')?>">Lessons</a>
        </li>
    </div>
</ul>

</body>
</html>
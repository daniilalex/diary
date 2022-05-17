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
<ul>
    <div class="button" style="margin: 35px 0 0 20px">
        <li>
            <a href="<?=base_url('/Director/index')?>">Home</a>
        </li>
    </div>
</ul>
<hr>
<div class="content is-normal" style="margin-left: 20px">
    <h1>Classes</h1>
</div>
<hr>
<table class="table" style="margin-left: 20px">
    <tr>
        <th>ID</th>
        <th>Title</th>
    </tr>
    <? foreach ($classes as $class) { ?>
        <tr>
            <td><?= $class['id'] ?></td>
            <td><?= $class['title'] ?></td>
        </tr>
    <? } ?>
</table>
</body>
</html>

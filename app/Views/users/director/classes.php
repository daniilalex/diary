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
            <a href="<?= base_url('/Director/index') ?>">Home</a>
        </li>
    </div>
</ul>
<hr>
<div class="content is-normal" style="margin-left: 20px">
    <h1>Classes</h1>
</div>
<hr>
<div class="create" style="width: 300px; border: #31708f 1px solid;margin: 10px 0 10px 20px">
    <form style="width: 250px;margin: 0 20px;" action="<?= base_url('/director/createClass') ?>" method="post">
        <div class="content is-normal" style="margin: 10px 0 10px 20px">
            <h1>Add Class:</h1>
        </div>
        <label for="class">Class</label><br>
        <input class="input is-info" type="text" name="class"><br><br>
        <label for="class">Max week lessons</label><br>
        <input class="input is-info" type="text" name="max_lessons"><br><br>
        <input style="margin-bottom: 15px" type="submit" class="button is-primary" value="create">
    </form>
</div>
<hr>
<div class="content is-normal" style="margin: 10px 0 10px 20px">
    <h1>Update Class</h1>
</div>
<div class="update" style="width: 300px; border: #31708f 1px solid;margin: 10px 0 10px 20px">
    <? if (isset($class)) { ?>
        <form style="width: 250px;margin: 0 20px;" action="<?= base_url('/director/updateClass/' . $class['id']) ?>"
              method="post">
            <label for="class">Class</label><br>
            <input type="text" class="input is-info" name="class" value="<?= $class['title'] ?>"><br><br>
            <label for="class">Max week lessons</label><br>
            <input type="text" class="input is-info" name="max_lessons" value="<?= $class['max_week_lessons'] ?>"><br><br>
            <input style="margin-bottom: 15px" type="submit" class="button is-primary" value="update"><br>
        </form>

    <? } ?>
    <hr>
    <table class="table" style="margin-left: 20px">
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Action</th>
        </tr>
        <? foreach ($classes as $class) { ?>
            <tr>
                <td><?= $class['id'] ?></td>
                <td><?= $class['title'] ?></td>
                <td><a class="button is-small" href="<?= base_url('/director/classes/' . $class['id']) ?>">Edit</a></td>
                <td><a class="button is-small"
                       href="<?= base_url('/director/deleteClass/' . $class['id']) ?>">Delete</a></td>
            </tr>
        <? } ?>
    </table>
</body>
</html>

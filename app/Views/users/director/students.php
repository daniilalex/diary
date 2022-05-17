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
<div class="button is-primary" style="margin: 20px 0 0 20px">
    <a style="color: white" href="<?= base_url('/director/index/') ?>">Go back</a>
</div>
<div class="container" style="display: flex; flex-wrap: wrap;">
<div class="create" style="width: 300px; border: #31708f 1px solid;margin: 10px 0 10px 20px">
    <form style="width: 250px;margin: 0 20px;" action="<?= base_url('/director/createStudent') ?>" method="post">
        <div class="content is-normal" style="margin: 10px 0 10px 20px">
            <h1>Add Student:</h1>
        </div>
        <label for="email">Email</label><br>
        <input class="input is-info" type="text" name="email"><br>

        <label for="password">Password</label><br>
        <input class="input is-info" type="password" name="password"><br>

        <label for="firstname">First Name</label><br>
        <input class="input is-info" type="text" name="firstname"><br>

        <label for="lastName">Last Name</label><br>
        <input class="input is-info" type="text" name="lastname"><br>

        <label for="class_id">Classes</label><br>
        <select name="class_id">
            <option value="">Choose class</option>
            <? foreach ($classes as $class) { ?>
                <option value="<?= $class['id'] ?>"><?= $class['title'] ?></option>
            <? } ?>
        </select> <br><br>
        <input style="margin-bottom: 15px" type="submit" class="button is-primary" value="create">

    </form>
</div>
<div class="main" style="width: 620px; border: #31708f 1px solid;margin: 10px 0 10px 20px">
    <div class="content is-normal" style="margin: 20px 0 0 20px">
        <h1>Students</h1>
    </div>
    <table class="table" style="margin-left: 20px">
        <tr>
            <th>ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Class</th>
            <th>Action</th>
        </tr>
        <? foreach ($students as $student) { ?>

            <tr>
                <td><?= $student['id'] ?></td>
                <td><?= $student['firstname'] ?></td>
                <td><?= $student['lastname'] ?></td>
                <td><?= $student['class'] ?></td>
                <td><a class="button is-small" href="<?= base_url('/director/editStudent/' . $student['id']) ?>">Edit</a></td>
                <td><a class="button is-small" href="<?= base_url('/director/deleteStudent/' . $student['id']) ?>">Delete</a></td>
            </tr>
        <? } ?>
    </table>
</div>
</div>
</body>
</html>

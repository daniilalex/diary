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
<div class="update" style="width: 300px; border: #31708f 1px solid;margin: 10px 0 10px 20px">

    <form style="width: 250px;margin: 0 20px;" action="<?= base_url('/director/updateStudent/' . $student['id']) ?>"
          method="post">

        <div class="content is-normal">
            <h1>Update student</h1>
        </div>
        <label for="email">Email</label><br>
        <input type="text" class="input is-info" name="email" value="<?= $student['email'] ?>"><br>

        <label for="password">Password</label><br>
        <input type="password" class="input is-info" name="password"><br>

        <label for="firstname">First Name</label><br>
        <input type="text" class="input is-info" name="firstname" value="<?= $student['firstname'] ?>"><br>

        <label for="lastName">Last Name</label><br>
        <input type="text" class="input is-info" name="lastname" value="<?= $student['lastname'] ?>"><br>

        <label for="lesson_id">Classes</label><br>
        <select name="class_id">
            <option value="">-</option>
            <? foreach ($classes as $class) { ?>
                <option value="<?= $class['id'] ?>"><? if ($class['id'] == $student['class_id']) {
                        echo 'selected';
                    } ?><?= $class['title'] ?></option>
            <? } ?>
        </select> <br><br>
        <input style="margin-bottom: 15px" type="submit" class="button is-primary" value="update">
        <div class="button is-primary" style="float: right;">
            <a style="color: white" href="<?= base_url('/director/students/') ?>">Go back</a>
        </div>
    </form>
</body>
</html>

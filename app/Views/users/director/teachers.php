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
<div class="container" style="width: 800px">
<div class="flex" style="display: flex; width: 800px">
    <div class="form" style="width: 620px; border: #31708f 1px solid;margin: 40px 0 10px 20px">
        <form style="width: 250px;margin: 0 20px;" action="<?= base_url('/director/createTeacher') ?>" method="post">
            <div class="content is-normal" style="margin: 10px 0 10px 20px">
                <h3>Add teacher:</h3>
            </div>
            <label for="email">Email</label><br>
            <input class="input is-info" type="text" name="email"><br>

            <label for="password">Password</label><br>
            <input class="input is-info" type="password" name="password"><br>

            <label for="firstname">First Name</label><br>
            <input class="input is-info" type="text" name="firstname"><br>

            <label for="lastName">Last Name</label><br>
            <input class="input is-info" type="text" name="lastname"><br>

            <label for="lesson_id">Lessons</label><br>
            <select name="lesson_id">
                <option value="">Choose lesson</option>
                <? foreach ($lessons as $lesson) { ?>
                    <option value="<?= $lesson['id'] ?>"><?= $lesson['title'] ?></option>
                <? } ?>
            </select> <br>
            <label for="lesson_id">Classes</label><br>
            <select name="class_id ">
                <option value="">Choose class</option>
                <? foreach ($classes as $class) { ?>
                    <option value="<?= $class['id'] ?>"><?= $class['title'] ?></option>
                <? } ?>
            </select> <br><br>
            <input style="margin-bottom: 15px" type="submit" class="button is-primary" value="create">

        </form>
    </div>
    <div class="main" style="width: 600px; border: #31708f 1px solid;margin: 40px 0 0 20px;">
        <div class="content is-normal" style="margin: 20px 0 0 20px">
            <h1>Teachers</h1>
        </div>
        <table class="table" style="margin-left: 20px">
            <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Class</th>
                <th>Lesson</th>
                <th>Action</th>
            </tr>
            <? foreach ($teachers as $teacher) { ?>

                <tr>
                    <td><?= $teacher['id'] ?></td>
                    <td><?= $teacher['firstname'] ?></td>
                    <td><?= $teacher['lastname'] ?></td>
                    <td><?= $teacher['class'] ?></td>
                    <td><?= $teacher['lesson'] ?></td>
                    <td><a class="button is-small" href="<?= base_url('/director/editTeacher/' . $teacher['id']) ?>">Edit</a></td>
                    <td><a class="button is-small" href="<?= base_url('/director/deleteTeacher/' . $teacher['id']) ?>">Delete</a></td>
                </tr>
            <? } ?>

        </table>
    </div>
</div>
    <div class="button is-primary" style="margin-left: 20px;margin-top: 20px; float: right">
        <a style="color: white" href="<?= base_url('/director/index/') ?>">Go back</a>
    </div>
</div>

</body>
</html>

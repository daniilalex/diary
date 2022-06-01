<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.1/css/bulma.min.css">
    <script src="https://use.fontawesome.com/releases/v5.1.0/js/all.js"></script>
</head>
<body>

<div class="main" style="width: 600px; border: #31708f 1px solid;margin: 40px 0 0 20px;">
    <div class="table-container" style="margin: 10px 20px;">
        <td><a class="button is-small"
               href="<?= base_url('/teacher/index/') ?>">Go back</a>
        </td>
        <div class="content is-normal" style="margin: 20px 0 0 20px">
            <h5>Class: <?= $schedule['title'] ?></h5>
            <hr>
            <h5>Cabinet: <?= $schedule['cabinet'] ?></h5>
            <hr>
            <h5>Lesson: <?= $teacher['title'] ?></h5>
            <h5>Date: <?= $date ?></h5>
            <hr>
        </div>
<!--        create teacher form-->
        <form action="<?= base_url('/teacher/addNotice/' . $teacher['id'] . '/' . $teacher['lesson_id'] . '/' . $date) ?>"
              method="post">
            <table class="table">
                <tr>
                    <th>Name</th>
                    <th>Surname</th>
                    <th>Action</th>
                    <th>X</th>
                </tr>
                <? foreach ($students as $student) { ?>
                    <tr>
                        <td><?= $student['firstname'] ?></td>
                        <td><?= $student['lastname'] ?></td>
                        <td>
<!--                            add to name new  content array with student.id-->
                            <input class="input is-small" type="text" name="content[<?= $student['id'] ?>]">
                        </td>
                        <td><a class="button is-small"
                               href="<?= base_url('/teacher/deleteStudent/' . $student['id']) ?>">Delete</a>
                        </td>
                    </tr>
                <? } ?>
            </table>
            <input type="submit" class="button is-small"
                   value="add">
        </form>
    </div>
</div>

</body>
</html>
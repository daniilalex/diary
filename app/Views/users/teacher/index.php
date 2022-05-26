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


<? if (isset($class)) { ?>
    <div class="content is-normal" style="margin: 20px 0 0 20px">
        <h1>My Class: <?= $class['title'] ?></h1>
    </div>
    <div class="content is-normal" style="margin: 20px 0 0 20px">
        <h1>Lessons per week: <?= $class['max_week_lessons'] ?></h1>
    </div>

    <hr>
    <div class="button" style="margin: 35px 0 0 20px">
        <a href="<?= base_url('/Auth/log_out/') ?>">Log Out</a>
    </div>

    <div class="container" style="width: 800px">
        <div class="flex" style="display: flex; width: 800px">
            <div class="main" style="width: 600px; border: #31708f 1px solid;margin: 40px 0 0 20px;">
                <div class="content is-normal" style="margin: 20px 0 0 20px">
                    <h3>My students</h3>
                </div>
                <table class="table" style="margin-left: 20px">
                    <tr>
                        <th>ID</th>
                        <th>email</th>
                        <th>Name</th>
                        <th>Lastname</th>
                    </tr>
                    <? foreach ($students as $student) { ?>
                        <tr>
                            <td><?= $student['id'] ?></td>
                            <td><?= $student['email'] ?? null ?></td>
                            <td><?= $student['firstname'] ?? null ?></td>
                            <td><?= $student['lastname'] ?? null ?></td>
                        </tr>
                    <? } ?>
                </table>
            </div>
        </div>

        <div class="main" style="width: 600px; border: #31708f 1px solid;margin: 40px 0 0 20px;">

            <div class="content is-normal" style="margin: 10px 0 10px 20px">
                <h3>Add Lesson:</h3>
            </div>

            <form style="width: 250px;margin: 0 20px;" action="<?= base_url('/teacher/createTimetable') ?>"
                  method="post">

                <label for="week_day">Week day</label><br>
                <select name="week_day">
                    <option>-</option>
                    <? foreach ($days as $day) { ?>
                        <option value="<?= $day ?>"><?= ucfirst($day) ?></option>
                    <? } ?>
                </select><br>

                <label for="lesson_number">Lessons queue</label><br>
                <select name="lesson_number">
                    <option value="">Choose lesson queue</option>
                    <? for ($i = 1; $i <= round($class['max_week_lessons']) / 5; $i++) { ?>
                        <option value="<?= $i ?>"><?= $i ?></option>
                    <? } ?>
                </select> <br>

                <label for="teacher_id">Lesson</label><br>
                <select name="teacher_id">
                    <option value="">Choose a lesson</option>
                    <? foreach ($teachers as $teacher) { ?>
                        <option value="<?= $teacher['id'] ?>"><?= $teacher['lesson'] ?>
                            (<?= $teacher['firstname'] ?> <?= $teacher['lastname'] ?>)
                        </option>
                    <? } ?>
                </select> <br>
                <label for="cabinet">Cabinet</label><br>
                <input class="input is-info" type="text" name="cabinet"><br><br>
                <input style="margin-bottom: 15px" type="submit" class="button is-primary" value="create">
            </form>

            <div class="content is-normal" style="margin: 20px 0 0 20px">
                <h5>Schedule fill: <?= $count_lessons ?> / <?= $class['max_week_lessons'] ?></h5>
            </div>
        </div>

        <div class="main" style="width: 600px; border: #31708f 1px solid;margin: 40px 0 0 20px;">
            <div class="table-container">
                <div class="content is-normal" style="margin: 20px 0 0 20px">
                    <h3>My schedule</h3>
                </div>
                <table class="table">
                    <tr>
                        <? foreach ($days as $day) { ?>
                            <th>
                                <?= ucfirst($day) ?>
                            </th>

                        <? } ?>
                    </tr>
                    <tr>
                        <? foreach ($days as $day) { ?>
                            <td>
                                <table>
                                    <? foreach ($schedule[$day] as $item) { ?>
                                        <tr>
                                            <td>(<?= $item['lesson_number'] ?>)</td>
                                            <td><?= $item['title'] ?></td>
                                            <td>
                                                <div class="button is-small"
                                                     style="margin: 35px 0 0 20px; font-size: 10px;">
                                                    <a href="<?= base_url('/teacher/deleteLesson/' . $item['id']) ?>">Delete</a>
                                                </div>
                                            </td>
                                        </tr>
                                    <? } ?>
                                </table>
                            </td>

                        <? } ?>
                    </tr>
                </table>
            </div>
        </div>
    </div>
<? } else { ?>

    <div class="content is-normal" style="margin: 20px 0 0 20px">
        <h3>My schedule</h3>
    </div>

    <div class="content is-normal" style="margin: 20px 0 0 20px">
        <h6>Date: <?= $date ?? date('Y-m-d') ?></h6>
    </div>

    <form style="width: 250px;margin: 0 20px;" action="<?= base_url('/teacher/date') ?>" method="post">
        <input class="input is-info" type="text" name="date" value="<?= $date ?? date('Y-m-d') ?>"><br><br>
        <input class="button is-primary" type="submit" value="Show">
    </form>

    <div class="table-container">
        <table>
            <? foreach ($teacher_schedule as $item) { ?>
                <tr>
                    <td>(<?= $item['title'] ?>)</td>
                    <td><?= $item['week_day'] ?></td>
                    <td>(<?= $item['lesson_number'] ?>)</td>
                    <td>(<?= $item['class'] ?>)</td>
                </tr>
            <? } ?>
        </table>
    </div>

<? } ?>

</body>
</html>


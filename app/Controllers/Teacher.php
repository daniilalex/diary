<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AttendanceModel;
use App\Models\GradeModel;
use App\Models\NoticeModel;
use App\Models\StudentModel;
use App\Models\TeacherModel;
use App\Models\ClassModel;
use App\Models\LessonModel;
use App\Models\TimeTableModel;
use CodeIgniter\HTTP\RedirectResponse;


class Teacher extends BaseController
{
    public $teachers;
    public $classes;
    public $lessons;
    public $schedules;
    public $students;


    public function __construct()
    {
        $this->teachers = new TeacherModel();
        $this->students = new StudentModel();
        $this->classes = new ClassModel();
        $this->lessons = new LessonModel();
        $this->schedules = new TimeTableModel();


        if (session()->user['type'] != 'teacher') {
            dd((array)'Allowed only teacher');
        }
    }

    public function index(string $date = null)
    {
        //take a teacher id from session
        $teacher = $this->teachers->where('user_id', session()->user['id'])->first();
        //take values from database, static method is using :: scopes. it invoked directly from class
        $data = [
            //get days with static function
            'days' => TimeTableModel::DAYS,
            'errors' => $this->session->getFlashdata('errors') ?? null,
            'success' => $this->session->getFlashdata('success') ?? null,
            //get teacher lessons with created model function
            'teacher_schedule' => $this->schedules->getTeacherLessons($teacher['id'], $date)

        ];
//create date for displaying date
        if ($date != null) {
            $data['date'] = $date;
        } else {
            $data['date'] = date('Y-m-d');
        }

//if teacher class_id isn't 0(like in database), add to the data new keys, for displaying teachers array
        if ($teacher['class_id'] != 0) {
            //get lessons value with static function from model class
            $data['schedule'] = TimeTableModel::getLessons($teacher['class_id']);
            //get teacher class
            $data['class'] = $this->classes->find($teacher['class_id']);
            //get teachers students
            $data['count_lessons'] = $this->schedules->where('class_id', $teacher['class_id'])->countAll();
            $data['students'] = $this->classes->getStudents($teacher['class_id']);
            //get teacher who has lesson_id
            $data['teachers'] = $this->teachers
                ->select('teachers.id, users.email, users.firstname, users.lastname, lessons.title as lesson')
                ->join('users', 'users.id = teachers.user_id')
                ->join('lessons', 'lessons.id = teachers.lesson_id', 'left')
                ->where('lesson_id !=', 0)
                ->findAll();

        }

        return view('users/teacher/index', $data);
    }

    public function createTimeTable()
    {
        if ($this->validate([
            //in_list - must be chosen from select(list) + convert array to string
            'week_day' => 'required|in_list[' . implode(',', TimeTableModel::DAYS) . ']',
            //Fails if field is not exactly the parameter
            //value.
            'lesson_number' => 'required|integer|exact_length[1]',
            //checks from DB values, put method isNotUnique to table teachers.id
            'teacher_id' => 'required|is_not_unique[teachers.id]',
            'cabinet' => 'required|string|min_length[1]|max_length[30]',
        ])) {
            $schedule = $this->schedules
                ->where('class_id', (new TeacherModel())->where('user_id', session()->user['id'])->first()['class_id'])
                ->where('week_day', $this->request->getVar('week_day'))
                ->where('lesson_number', $this->request->getVar('lesson_number'))
                ->first();
            //if $schedule is false take values with post method and insert them to the database
            if (!$schedule) {
                //get user_id from session
                $user = $this->teachers->where('user_id', session()->user['id'])->first();
                //put value to class_id
                $class_id = $user['class_id'];
                //get teacher from database by id and get select value with post method
                $teacher = $this->teachers->where('id', $this->request->getVar('teacher_id'))->first();
//create schedule data for inserting to database
                $schedule_data = [
                    'class_id' => $class_id,
                    'lesson_number' => $this->request->getVar('lesson_number'),
                    'lesson_id' => $teacher['lesson_id'],
                    'teacher_id' => $teacher['id'],
                    'cabinet' => $this->request->getVar('cabinet'),
                    'week_day' => $this->request->getVar('week_day'),
                ];
                $this->schedules->insert($schedule_data);
                return redirect()->to(base_url('/teacher/index'))->with('success', 'Lesson is successfully added to schedule');
            } else {
                $errors = 'Time is used';
            }
        } else {
            $errors = $this->validator->listErrors();
        }
        return redirect()->to(base_url('/teacher/index'))->with('errors', $errors);
    }

    public function deleteLesson(int $id)
    {
        $lesson = $this->schedules->find($id);
        if ($lesson) {
            $this->schedules->delete($id);
            return redirect()->to(base_url('/teacher/index'))->with('success', 'Lesson is successfully deleted');
        }
        return redirect()->to(base_url('/teachers/index'))->with('errors', 'Lesson is not found');
    }

//create date method
    public function date()
    {
        if ($this->validate([
            'date' => 'required|valid_date[Y-m-d]',
        ])) {
            $date = $this->request->getVar('date');
            return redirect()->to(base_url('/teacher/index/' . $date));

        }

        return redirect()->to(base_url('/teacher/index'))->with('errors', 'Wrong date');
    }

//get all students from teacher lesson

    /**
     * @param int $id
     * @param string $date
     * @return RedirectResponse|string
     */
    public function getLessonStudents(int $id, string $date)
    {
//get teacher values by teacher.id
        $teacher = $this->teachers
            ->select('teachers.*, lessons.title')
            ->join('lessons', 'lessons.id = teachers.lesson_id')
            ->where('teachers.user_id', session()->user['id'])
            ->first();
//get timetable values by timetable.id
        $schedule = $this->schedules
            ->join('classes', 'classes.id = timetable.class_id')
            ->where('timetable.week_day', strtolower(date('l', strtotime($date))))
            ->where('timetable.teacher_id', $teacher['id'])
            ->where('timetable.id', $id)
            ->first();
        //if timetable exist get students from timetable.class.id
        if ($schedule) {
            $students = $this->students
                ->select('users.firstname, users.lastname, classes.title, students.user_id, students.id')
                ->join('classes', 'classes.id = students.class_id')
                ->join('users', 'users.id = students.user_id')
                ->where('students.class_id', $schedule['class_id'])
                ->findAll();
//create data for displaying values
            $data = [
                'teacher' => $teacher,
                'schedule' => $schedule,
                'students' => $students,
                'date' => $date
            ];

            return view('users/teacher/lessonStudents', $data);
        }
        return redirect()->to(base_url('/teacher/index'))->with('errors', 'Error');

    }

    public function deleteStudent(int $id)
    {
        $student = $this->schedules->find($id);
        if ($student) {
            $this->schedules->delete($id);
            return redirect()->to(base_url('/teacher/getLessonStudent'))->with('success', 'Student is successfully deleted');
        }
        return redirect()->to(base_url('/teachers/index'))->with('errors', 'Student is not found');
    }

    public function addNotice(int $teacher_id, int $lesson_id)
    {
        //get array from inputs
        $items = $this->request->getVar('content');
//loop student values(content) and make checks
        foreach ($items as $student_id => $content) {
            //if content value is empty, skip it and goes further
            if (empty($content)) {
                continue;
            }
            //make input values to lowercase
            $content = strtolower($content);
//create data for inserting values
            $data = [
                'teacher_id' => $teacher_id,
                'lesson_id' => $lesson_id,
                'student_id' => $student_id,
                'date' => date('Y-m-d H:i:s'),
            ];
//check inputs values for numeric and numbers from 1 to 10,
//check for late, missing
//check good and bad words in notice
// merge arrays and insert them to the tables
            if (is_numeric($content) && in_array($content, [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10])) {
                (new GradeModel())->insert(
                    array_merge($data, [
                        'grade' => $content,
                    ])
                );
            } else if (in_array($content, ['l', 'late'])) {
                (new AttendanceModel())->insert(
                    array_merge($data, [
                        'status' => 'late',
                    ])
                );
            } else if (in_array($content, ['wn', 'was not', 'missed'])) {
                (new AttendanceModel())->insert(
                    array_merge($data, [
                        'status' => 'missing',
                    ])
                );
            } else {
                //create 2 arrays with words
                $badWords = ['not listen', 'not learning', 'fooled', 'fight', 'bad'];
                $goodWords = ['good', 'careful', 'attentive', 'tries'];
                // returns an array containing all the words found inside the string
                $words = str_word_count($content, 1);
                // count and checking if any of the strings in an array matches a string
                $badWords = count(array_intersect($badWords, $words));
                $goodWords = count(array_intersect($goodWords, $words));

                if ($badWords > $goodWords) {
                    $status = 'negative';
                } elseif ($badWords < $goodWords) {
                    $status = 'positive';
                } else {
                    $status = 'other';
                }

                (new NoticeModel())->insert(
                    array_merge($data, [
                        'message' => $content,
                        'status' => $status,
                    ])
                );
            }
        }

        return redirect()->to(base_url('/teacher/index'))->with('success', 'The lesson notice is successfully added');
    }
}



//        $input = $_POST['notice'];
//        $teacher = $this->teachers->where('teachers.user_id', session()->user['id'])->first();
//        $student_id = $this->students
//            ->select('students.id')
//            ->join('timetable', 'timetable.class_id = students.class_id')
//            ->join('users', 'users.id = students.user_id')
//            ->find($id);
//        $attendance = ['was', 'wasNot', 'w', 'wN', 'late', 'l'];
//        $grades = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
//        $good_words = strtolower(implode('|', ['good', 'not bad', 'excellent', 'pleasant']));
//        $bad_words = strtolower(implode('|', ['bad', 'unlike', 'terrible', 'without comment']));
//
//        if (preg_match("($good_words)", strtolower($input))) {
//            $status = 'positive';
//        } else if (preg_match("($bad_words)", strtolower($input))) {
//            $status = 'negative';
//        } else {
//            $status = 'other';
//        }
//        $attendance_data = [
//            'teacher_id' => $teacher['id'],
//            'lesson_id' => $teacher['lesson_id'],
//            'student_id' => $student_id,
//            'attendance' => $this->request->getPost('notice')
//        ];
//        $grades_data = [
//            'teacher_id' => $teacher['id'],
//            'lesson_id' => $teacher['lesson_id'],
//            'student_id' => $student_id,
//            'grade' => $this->request->getPost('notice'),
//            'date' => date('Y-m-d H:i:s')
//        ];
//        $notice_data = [
//            'teacher_id' => $teacher['id'],
//            'lesson_id' => $teacher['lesson_id'],
//            'student_id' => $student_id,
//            'message' => $this->request->getPost('notice'),
//            'status' => $status,
//            'date' => date('Y-m-d H:i:s')
//        ];
//
//        if (in_array($_POST['notice'], $attendance)) {
//            (new AttendanceModel())->insert($attendance_data);
//            return redirect()->to(base_url('/teacher/index'))->with('success', 'Attendance is successfully added');
//        } else if (in_array($input, $grades)) {
//            (new GradeModel())->insert($grades_data);
//            return redirect()->to(base_url('/teacher/index'))->with('success', 'Grade is successfully added');
//        } else if ($input) {
//            (new NoticeModel())->insert($notice_data);
//            return redirect()->to(base_url('/teacher/index'))->with('success', 'Notice is successfully added');
//        }
//
//        return redirect()->to(base_url('/teacher/index'))->with('errors', 'Your notice is not created');
//    }






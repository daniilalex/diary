<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\StudentModel;
use App\Models\TeacherModel;
use App\Models\ClassModel;
use App\Models\LessonModel;
use App\Models\TimeTableModel;


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
        }


//if teacher class_id isn't 0(like in database), add to the data new keys, for displaying teachers array
        if ($teacher['class_id'] != 0 ) {
            //get lessons value with static function from model class
            $data['schedule'] = TimeTableModel::getLessons($teacher['class_id']);
            //get teacher class
            $data['class'] = $this->classes->find($teacher['class_id']);
            //get teachers students
            $data['students'] = $this->classes->getStudents($teacher['class_id']);
            //get teacher who has lesson_id
            $data['teachers'] = $this->teachers
                ->select('teachers.id, users.email, users.firstname, users.lastname, lessons.title as lesson')
                ->join('users', 'users.id = teachers.user_id')
                ->join('lessons', 'lessons.id = teachers.lesson_id', 'left')
                ->where('lesson_id !=', 0)
                ->findAll();
            //count lessons from timetable database by teacher_class_id
            $data['count_lessons'] = $this->schedules->where('class_id', $teacher['class_id'])->countAll();
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
        return  redirect()->to(base_url('/teacher/index'))->with('errors', $errors);
    }

    public function deleteLesson(int $id) {
        $lesson = $this->schedules->find($id);
        if($lesson) {
            $this->schedules->delete($id);
            return redirect()->to(base_url('/teacher/index'))->with('success', 'Lesson is successfully deleted');
        }
        return redirect()->to(base_url('/teachers/index'))->with('errors', 'Lesson is not found');
    }

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
}
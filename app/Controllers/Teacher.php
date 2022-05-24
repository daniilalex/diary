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

    public function index()
    {
        //take a teacher id from session
        $teacher = $this->teachers->where('user_id', session()->user['id'])->first();
        $data = [
            'students' => $this->classes->getStudents($teacher['class_id']),
            'days' => TimeTableModel::DAYS,
            'teachers' => $this->teachers
                ->select('teachers.id, users.email, users.firstname, users.lastname, lessons.title as lesson')
                ->join('users', 'users.id = teachers.user_id')
                ->join('lessons', 'lessons.id = teachers.lesson_id', 'left')
                ->where('lesson_id !=', 0)
                ->findAll(),
            'errors' => $this->session->getFlashdata('errors') ?? null,
            'success' => $this->session->getFlashdata('success') ?? null,
            'schedule' => TimeTableModel::getLessons($teacher['class_id']),
            'count_lessons' => $this->schedules->where('class_id', $teacher['class_id'])->countAll()
        ];

//if teacher class_id isn't null add to the data new key class with teacher class_id
        if ($teacher['class_id'] != null) {
            $data['class'] = $this->classes->find($teacher['class_id']);
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
            if (!$schedule) {
                $user = $this->teachers->where('user_id', session()->user['id'])->first();
                $class_id = $user['class_id'];
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
}
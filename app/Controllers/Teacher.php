<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\StudentModel;
use App\Models\TeacherModel;
use App\Models\ClassModel;
use App\Models\LessonModel;
use App\Models\TimeTable;
use App\Controllers\Director;

class Teacher extends BaseController
{
    public $teachers;
    public $classes;
    public $lessons;
    public $time_table;
    public $students;


    public function __construct()
    {
        $this->teachers = new TeacherModel();
        $this->students = new StudentModel();
        $this->classes = new ClassModel();
        $this->lessons = new LessonModel();



        if (session()->user['type'] != 'teacher') {
            dd((array)'Allowed only teacher');
        }
    }

    public function index()
    {
        //take a teacher id from session
        $teacher = $this->teachers->where('user_id', session()->user['id'])->first();
        $data = [
            'students' => $this->classes->getStudents(),
            'days' => TimeTable::DAYS,
            'teachers' => $this->teachers
                ->select('teachers.id, users.email, users.firstname, users.lastname, lessons.title as lesson')
                ->join('users', 'users.id = teachers.user_id')
                ->join('lessons', 'lessons.id = teachers.lesson_id')
                ->where('lesson_id', 0)
                ->findAll()

        ];

//if teacher class_id isn't null add to the data new key class with teacher class_id
        if ($teacher['class_id'] != null) {
            $data['class'] = $this->classes->find($teacher['class_id']);
        }

        return view('users/teacher/index', $data);
    }

    public function createTimeTable()
    {
        $timetable_data = [
            'lesson_number' => $this->request->getPost('lesson_number'),
            'cabinet' => $this->request->getPost('cabinet'),
            'class_id' => $this->request->getVar('class_id'),
            'lesson_id' => $this->request->getVar('lesson_id'),
            'teacher_id' => $this->request->getVar('teacher_id'),
        ];
        $this->time_table->insert($timetable_data);

        return redirect()->to(base_url('teacher/index'));
    }
}
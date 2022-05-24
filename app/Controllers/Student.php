<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\StudentModel;
use App\Models\TeacherModel;
use App\Models\ClassModel;

class Student extends BaseController
{
    public $students;
    public $classes;
    public $teachers;

    public function __construct()
    {
        $this->students = new StudentModel();
        $this->classes = new ClassModel();
        $this->teachers = new TeacherModel();
        //take from session user key and compare it with array element
        if (session()->user['type'] != 'student') {
            dd((array)'can enter only a student');
        }
    }

    public function index()
    {
        //take student id from session
        $student = $this->students->where('user_id', session()->user['id'])->first();
        //take a class where class id = student class_id
        $class = $this->classes->where('id', $student['class_id'])->first();
        //take a teacher where teacher_id = student teacher_id
        $teacher = $this->teachers
            ->select('teachers.id, users.email, users.firstname, users.lastname')
            ->join('users', 'users.id = teachers.user_id')
            ->where('class_id', $student['class_id'])
            ->first();
        $data = [
            'teacher' => $teacher,
            'student' => $student,
            'class' => $class
        ];

        return view('users/student/index', $data);
    }
}

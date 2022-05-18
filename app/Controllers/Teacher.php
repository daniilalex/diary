<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\TeacherModel;
use App\Models\ClassModel;

class Teacher extends BaseController
{
    public $teachers;
    public $classes;

    public function __construct()
    {
        $this->teachers = new TeacherModel();
        $this->classes = new ClassModel();

        if (session()->user['type'] != 'teacher') {
            dd((array)'Allowed only teacher');
        }
    }

    public function index()
    {
        //take a teacher id from session
        $teacher = $this->teachers->where('user_id', session()->user['id'])->first();
//get students by teachers class id with created function getStudents
        $data = [
            'students' => $this->classes->getStudents($teacher['class_id']),
        ];
//if teacher class_id isn't null add to the data new key class with teacher class_id
        if ($teacher['class_id'] != null) {
            $data['class'] = $this->classes->find($teacher['class_id']);
        }

        return view('users/teacher/index', $data);
    }
}
<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AuthModel;
use App\Models\LessonModel;
use App\Models\StudentModel;
use App\Models\TeacherModel;
use App\Models\ClassModel;
use Composer\Package\Loader\ValidatingArrayLoader;


class Director extends BaseController
{
    protected $db;
    public $teachers;
    public $lessons;
    public $classes;
    protected $session;
    public $students;


    public function __construct()
    {
        if (session()->user['type'] != 'director') {
            dd((array)'Allowed only director');
        }
        $teachers = $this->teachers = new TeacherModel();
        $lessons = $this->lessons = new LessonModel();
        $classes = $this->classes = new ClassModel();
        $students = $this->students = new StudentModel();

    }

    public function index()
    {
        //take values from tables
        //pass flash messages to session
        $data = [
            'lessons' => $this->lessons->findAll(),
            'classes' => $this->classes->findAll(),
            'teachers' => $this->teachers->findAllWithRelations(),
            'errors' => $this->session->getFlashdata('errors') ?? null,
            'success' => $this->session->getFlashdata('success') ?? null,
        ];
//show director.php page
        return view('users/director/home', $data);
    }

    public function teachers()
    {
        $data = [
            'lessons' => $this->lessons->findAll(),
            'classes' => $this->classes->findAll(),
            'teachers' => $this->teachers->findAllWithRelations(),
            'errors' => $this->session->getFlashdata('errors') ?? null,
            'success' => $this->session->getFlashdata('success') ?? null,
        ];

        echo view('users/director/teachers', $data);

    }

    public function students($id = null)
    {
        $data = [
            'classes' => $this->classes->findAll(),
            'students' => $this->students->getWithRelations(),
            'errors' => $this->session->getFlashdata('errors') ?? null,
            'success' => $this->session->getFlashdata('success') ?? null,
        ];
        //create new array key in data
        if ($id != null) {
            $data['student'] = $this->students->getWithRelations($id);
        }

        echo view('users/director/students', $data);

    }

    public function classes()
    {
        $data = [
            'classes' => $this->classes->findAll(),
        ];

        echo view('users/director/classes', $data);
    }

    public function lessons()
    {
        $data = [
            'lessons' => $this->lessons->findAll(),
        ];

        echo view('users/director/lessons', $data);
    }

    public function createTeacher()
    {
        //if validation ok, pass values from post to the array and insert them to the databse
        if ($this->validate([
            'password' => 'required|min_length[2]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'firstname' => 'required|min_length[2]|max_length[60]',
            'lastname' => 'required|min_length[2]|max_length[60]',
            'lesson_id' => 'permit_empty|is_not_unique[lessons.id]',
            'class_id' => 'permit_empty|is_not_unique[classes.id]',
        ])) {
            $user_data = [
                'email' => $this->request->getVar('email'),
                'password' => md5($this->request->getVar('password')),
                'firstname' => $this->request->getVar('firstname'),
                'lastname' => $this->request->getVar('lastname'),
                'type' => 'teacher'
            ];
            $user_id = (new AuthModel())->insert($user_data);

            $teacher_data = [
                'user_id' => $user_id,
                'lesson_id' => $this->request->getVar('lesson_id') ?? null,
                'class_id' => $this->request->getVar('class_id') ?? null,
            ];
            $this->teachers->insert($teacher_data);

            return redirect()->to(base_url('/director/index'))->with('success', 'Teacher is created');
        } else {
            return redirect()->to(base_url('/director/index'))->with('errors', $this->validator->listErrors());
        }
    }

    public function editTeacher($id)
    {
        //take data from teacher table
        $teacher = $this->teachers->getFullData($id);
        //if true, put to the data all data from database and show teacher_edit.php
        if ($teacher) {
            $data = [
                'lessons' => $this->lessons->findAll(),
                'classes' => $this->classes->findAll(),
                'teacher' => $teacher
            ];
            return view('users/director/teacher_edit', $data);
        }
        //if false return to the main page
        return redirect()->to(base_url('director/teachers'))->with('errors', 'Teacher is not found');
    }

    public function updateTeacher(int $id)
    {
        //get data from teacher table
        $teacher = (new TeacherModel())->getFullData($id);
        if ($teacher) {
            if ($this->validate([
                'password' => 'permit_empty|min_length[2]',//permit_empty - Allows the field to receive an empty array,empty string, null or false.
                'email' => 'required|valid_email|is_unique[users.email,id,' . $teacher['user_id'] . ']',//email address should be unique in the database, except for the row that has an id matching the placeholder’s value. form POST data had the following value
                'firstname' => 'required|min_length[2]|max_length[20]',
                'lastname' => 'required|min_length[2]|max_length[20]',
                'lesson_id' => 'permit_empty|is_not_unique[lessons.id]',
                'class_id' => 'permit_empty|is_not_unique[classes.id]',
            ])) {
                $userData = [
                    'email' => $this->request->getVar('email'),
                    'firstname' => $this->request->getVar('firstname'),
                    'lastname' => $this->request->getVar('lastname'),
                ];

                $password = $this->request->getVar('password') ?? null;
                //if not null pass old password from post
                if ($password != null) {
                    $userData['password'] = md5($this->request->getVar('password'));
                }
//update user table
                (new AuthModel())->update($teacher['user_id'], $userData);
//update teacher table
                $this->teachers->update($id, [
                    'lesson_id' => $this->request->getVar('lesson_id') ?? null,
                    'class_id' => $this->request->getVar('class_id') ?? null,
                ]);

                return redirect()->to(base_url('/director/teachers'))->with('success', 'The teacher is successfully updated');
            }
        }

        return redirect()->to(base_url('/director/index'))->with('errors', 'The teacher is not found');
    }

    public function createStudent()
    {

        //if validation ok, pass values from post to the array and insert them to the databse
        if ($this->validate([
            'password' => 'required|min_length[2]',
            'email' => 'required|valid_email|is_unique[users.email]',//is_unique[table.field,ignore_field,ignore_value]
            'firstname' => 'required|min_length[2]|max_length[60]',
            'lastname' => 'required|min_length[2]|max_length[60]',
            'class_id' => 'permit_empty|is_not_unique[classes.id]',
        ])) {
            $user_data = [
                'email' => $this->request->getPost('email'),
                'password' => md5($this->request->getPost('password')),
                'firstname' => $this->request->getPost('firstname'),
                'lastname' => $this->request->getPost('lastname'),
                'type' => 'student'
            ];

            $user_id = (new AuthModel())->insert($user_data);
            $student_data = [
                'user_id' => $user_id,
                'class_id' => $this->request->getVar('class_id') ?? null,
            ];
            var_dump($student_data);
            $this->students->insert($student_data);

            return redirect()->to(base_url('/director/students'))->with('success', 'Student is created');
        } else {
            return redirect()->to(base_url('/director/students'))->with('errors', $this->validator->listErrors());
        }

    }
    public function editStudent($id)
    {
        //take data from teacher table
        $student = $this->students->getWithRelations($id);
        //if true, put to the data all data from database and show teacher_edit.php
        if ($student) {
            $data = [
                'lessons' => $this->lessons->findAll(),
                'classes' => $this->classes->findAll(),
                'student' => $student
            ];
            return view('users/director/student_edit', $data);
        }
        //if false return to the student page
        return redirect()->to(base_url('director/students'))->with('errors', 'Student is not found');
    }

    public function updateStudent(int $id)
    {
        $student = $this->students->getWithRelations($id);
        if ($student) {
            if ($this->validate([
                'password' => 'permit_empty|min_length[2]',
                'email' => 'required|valid_email|is_unique[users.email,id,' . $student['user_id'] . ']',
                'firstname' => 'required|min_length[2]|max_length[60]',
                'lastname' => 'required|min_length[2]|max_length[60]',
                'lesson_id' => 'permit_empty|is_not_unique[lessons.id]',
                'class_id' => 'permit_empty|is_not_unique[classes.id]',
            ])) {
                $student_data = [
                    'email' => $this->request->getVar('email'),
                    'firstname' => $this->request->getVar('firstname'),
                    'lastname' => $this->request->getVar('lastname'),
                ];

                $password = $this->request->getVar('password') ?? null;
                if ($password != null) {
                    $student_data['password'] = md5($this->request->getVar('password'));
                }

                (new AuthModel())->update($student['user_id'], $student_data);

                $this->students->update($id, [
                    'class_id' => $this->request->getVar('class_id') ?? null,
                ]);

                return redirect()->to(base_url('/director/students'))->with('success', 'Student is successfully updated');
            }
        }
        return redirect()->to(base_url('/director/students'))->with('errors', 'Student is not found');
    }

    public function deleteStudent($id)
    {
        $student = $this->students->find($id);
        if ($student) {
            (new AuthModel())->delete($student['user_id']);
            $this->students->delete($student['id']);

            return redirect()->to(base_url('/director/students'))->with('success', 'Student is successfully deleted');
        }

        return redirect()->to(base_url('/director/students'))->with('errors', 'Student is not found');
    }

    public function deleteTeacher($id)
    {
        $teacher = $this->teachers->find($id);
        if ($teacher) {
            (new AuthModel())->delete($teacher['user_id']);
            $this->teachers->delete($teacher['id']);
            return redirect()->to(base_url('/director/teachers'))->with('success', 'Teacher successfully deleted');
        }
        return redirect()->to(base_url('/director/teachers'))->with('errors', 'Teacher is not found');
    }

}

//    public function getData()
//    {
//        $db = \Config\Database::connect();
//        $builder = $db->table('users');
//        $query = $builder->get();// Produces: SELECT * FROM 'users'
//        $query = $db->query("SELECT * from diary.users");
//        //This method returns the query result as an array of objects
//        foreach ($query->getResultArray() as $row) {
//            echo $row['firstname'] . PHP_EOL;
//            echo $row['lastname'] . PHP_EOL ;
//            echo $row['email'] . PHP_EOL. ',';
//            echo $row['type'] . PHP_EOL. '|';
//        }
//        $query = $builder->getWhere(['id'=> $id]);
//    }

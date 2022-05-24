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
    protected \CodeIgniter\Session\Session $session;
    public $students;



    public function __construct()
    {
        if (session()->user['type'] != 'director') {
            dd((array)'Allowed only director');
        }
        $this->teachers = new TeacherModel();
        $this->lessons = new LessonModel();
        $this->classes = new ClassModel();
        $this->students = new StudentModel();

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

    public function teachers($id = null)
    {
        $data = [
            'lessons' => $this->lessons->findAll(),
            'classes' => $this->classes->findAll(),
            'teachers' => $this->teachers->findAllWithRelations(),
            'errors' => $this->session->getFlashdata('errors') ?? null,
            'success' => $this->session->getFlashdata('success') ?? null,
        ];
        if ($id != null) {
            $data['teacher'] = $this->teachers->findAllWithRelations($id);
        }
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

    public function classes($id = null)
    {
        $data = [
            'classes' => $this->classes->findAll(),
            'errors' => $this->session->getFlashdata('errors') ?? null,
            'success' => $this->session->getFlashdata('success') ?? null,
        ];
        if ($id != null) {
            $data['class'] = $this->classes->find($id);

        }

        echo view('users/director/classes', $data);
    }

    public function lessons()
    {
        $data = [
            'lessons' => $this->lessons->findAll(),
            'errors' => $this->session->getFlashdata('errors') ?? null,
            'success' => $this->session->getFlashdata('success') ?? null,
        ];

        echo view('users/director/lessons', $data);
    }

    public function createTeacher()
    {
        //if validation ok, pass values from post to the array and insert them to the database
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
                'class_id' => $this->request->getPost('class_id') ?? null,
            ];
            $this->teachers->insert($teacher_data);

            return redirect()->to(base_url('/director/teachers'))->with('success', 'Teacher is created');
        } else {
            return redirect()->to(base_url('/director/index'))->with('errors', $this->validator->listErrors());
        }
    }

    public function updateTeacher(int $id)
    {
        //get data from teacher table
        $teacher = $this->teachers->getFullData($id);
        if ($teacher) {
            if ($this->validate([
                'password' => 'permit_empty|min_length[2]',//permit_empty - Allows the field to receive an empty array,empty string, null or false.
                'email' => 'required|valid_email|is_unique[users.email,id,' . $teacher['user_id'] . ']',//email address should be unique in the database, except for the row that has an id matching the placeholder’s value. form POST data had the following value
                'firstname' => 'required|min_length[2]|max_length[20]',
                'lastname' => 'required|min_length[2]|max_length[20]',
                'lesson_id' => 'permit_empty|is_not_unique[lessons.id]',
                'class_id' => 'permit_empty|is_not_unique[classes.id]',
            ])) {
                $user_data = [
                    'email' => $this->request->getVar('email'),
                    'firstname' => $this->request->getVar('firstname'),
                    'lastname' => $this->request->getVar('lastname'),
                ];

                $password = $this->request->getVar('password') ?? null;
                //if not null add old password from post
                if ($password != null) {
                    $user_data['password'] = md5($this->request->getVar('password'));
                }
//update user table
                (new AuthModel())->update($teacher['user_id'], $user_data);
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
            $this->students->insert($student_data);

            return redirect()->to(base_url('/director/students'))->with('success', 'Student is created');
        } else {
            return redirect()->to(base_url('/director/students'))->with('errors', $this->validator->listErrors());
        }

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

    public function createLesson()
    {
        if ($this->validate([
            'title' => 'required|min_length[3]',
        ])) {
            $lesson_data = [
                'title' => $this->request->getVar('title'),
            ];
            $this->lessons->insert($lesson_data);

            return redirect()->to(base_url('/director/lessons'))->with('success', 'Lesson is created');
        } else {
            return redirect()->to(base_url('/director/lessons'))->with('errors', $this->validator->listErrors());
        }
    }

    public function editLesson($id)
    {
        //take data from teacher table
        $lesson = $this->lessons->find($id);
        //if true, put to the data all data from database and show teacher_edit.php
        if ($lesson) {
            $data = [
                'lessons' => $this->lessons->findAll(),
                'lesson' => $lesson
            ];
            return view('users/director/lesson_edit', $data);
        }
        //if false return to the student page
        return redirect()->to(base_url('director/lessons'))->with('errors', 'Lesson is not found');
    }

    public function updateLesson(int $id)
    {
        $lesson = $this->lessons->find($id);
        if ($lesson) {
            if ($this->validate([
                'lesson' => 'required|min_length[3]|is_unique[lessons.title,id,' . $id . ']',
            ])) {
                $lesson_data = [
                    'title' => $this->request->getVar('lesson'),
                ];

                $this->lessons->update($id, $lesson_data);

                return redirect()->to(base_url('/director/lessons'))->with('success', 'Lesson is successfully updated');
            }
        }
        return redirect()->to(base_url('/director/index'))->with('errors', 'Lesson is not found');
    }

    public function deleteLesson(int $id)
    {
        $lesson = $this->lessons->find($id);
        if ($lesson) {
            $this->lessons->delete($lesson['id']);
            $this->teachers->set('lesson_id', null)
                ->where('lesson_id', $id)
                ->update();

            return redirect()->to(base_url('/director/lessons'))
                ->with('success', 'Lesson successfully deleted');

        }
        return redirect()->to(base_url('/director/lessons'))
            ->with('errors', 'Lesson is not found');
    }

    public function createClass()
    {
        if ($this->validate([
            'class' => 'required|min_length[2]'
        ])) {
            $class_data = [
                'title' => $this->request->getPost('class'),
                'max_week_lessons' => $this->request->getPost('max_lessons')
            ];
            $this->classes->insert($class_data);
            return redirect()->to(base_url('director/classes'))->with('success', 'The class is created');
        }
        return redirect()->to(base_url('director/classes'))->with('errors', 'The class is not created');
    }

    /**
     * @throws \ReflectionException
     */
    public function updateClass(int $id)
    {
        $class = $this->classes->find($id);
        if ($class) {
            if ($this->validate([
                'class' => 'required|min_length[3]|is_unique[lessons.title,id,' . $id . ']',
            ])) {
                $class_data = [
                    'title' => $this->request->getPost('class') ?? null,
                    'max_week_lessons' => $this->request->getPost('max_lessons') ?? null
                ];

                $this->classes->update($id, $class_data);

                return redirect()->to(base_url('/director/classes'))->with('success', 'Class is successfully updated');
            }
        }
        return redirect()->to(base_url('/director/index'))->with('errors', 'Class is not found');
    }


    public function deleteClass(int $id)
    {
        $class = $this->classes->find($id);
        if ($class) {
            $this->classes->delete($id);
            $this->teachers
                ->set('class_id', 0)
                ->where('class_id', $id)
                ->update();
            $this->students
                ->set('class_id', 0)
                ->where('class_id', $id)
                ->update();

            return redirect()->to(base_url('/director/classes'))->with('success', 'Class is deleted');
        } else {
            $errors = 'Error';
        }

        return redirect()->to(base_url('/director/classes'))->with('errors', $errors);
    }




}



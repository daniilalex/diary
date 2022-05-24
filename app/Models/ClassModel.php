<?php

namespace App\Models;

use App\Models\StudentModel;
use CodeIgniter\Model;



class ClassModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'classes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'title',
        'max_week_lessons'
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function getStudents(int $id = null): array
    {
        if ($id != null) {
            $students = (new StudentModel)
                ->select('students.id, users.email, users.firstname, users.lastname, students.user_id')
                ->join('users', 'users.id = students.user_id')
                ->where('class_id', $id)
                ->findAll();
        } else {
            $students = [];
        }

        return $students;
    }

    public function getTeachers(int $id = null): array
    {
        if ($id != null) {
            $teachers = (new TeacherModel())
                ->select('teachers.id, users.email, users.firstname, users.lastname, teachers.user_id')
                ->join('users', 'users.id = teachers.user_id')
                ->where('class_id', $id)
                ->findAll();
        } else {
            $teachers = [];
        }

        return $teachers;
    }


}

<?php

namespace App\Models;

use CodeIgniter\Model;

class GradeModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'grades';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'teacher_id',
        'lesson_id',
        'student_id',
        'title',
        'grade',
        'date'
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

    public function getStudentGrade(int $student_id, $date, $lesson_id)
    {
        return $this->select('grade')
                ->where('student_id', $student_id)
                ->where('date', $date)
                ->where('lesson_id', $lesson_id)
                ->first()['grade'] ?? null;
    }
}

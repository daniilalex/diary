<?php

namespace App\Models;

use CodeIgniter\Model;

class TimeTableModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'timetable';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    =  [
        'class_id',
        'lesson_number',
        'lesson_id',
        'teacher_id',
        'cabinet',
        'week_day',
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

    const MONDAY = 'monday';
    const TUESDAY = 'tuesday';
    const WEDNESDAY = 'wednesday';
    const THURSDAY = 'thursday';
    const FRIDAY = 'friday';
    const DAYS = [
        self::MONDAY,
        self::TUESDAY,
        self::WEDNESDAY,
        self::THURSDAY,
        self::FRIDAY,
    ];

    static public function getLessons(int $class_id)
    {
        $response = [];
        foreach (self::DAYS as $day) {
            $response[$day] = (new self())
                ->select('schedules.id, schedules.lesson_number, lessons.title')
                ->join('lessons', 'lessons.id = schedules.lesson_id', 'left')
                ->where('class_id', $class_id)
                ->where('week_day', $day)
                ->orderBy('lesson_number', 'ASC')
                ->findAll();
        }

        return $response;
    }
}


<?php

namespace App\Models;

use App\Models\AuthModel;
use CodeIgniter\Model;
use App\Controllers\Auth;
use App\Models\ClassModel;

class TeacherModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'teachers';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $insertID = 0;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'user_id',
        'class_id',
        'lesson_id',
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    /**
     * @param int $id
     * @return array|false|object
     */
    public function getFullData(int $id)
    {
        $teacher = $this->find($id);
        if ($teacher) {
            $user = (new AuthModel())->find($teacher['user_id']);
            if ($user) {
                //merge arrays elements together
                return array_merge($user, $teacher);
            }
        }

        return false;
    }

    /**
     * @param int|null $id
     * @return array|object|void|null
     */
    public function findAllWithRelations(int $id = null)
    {
        $data = $this
            ->select('teachers.id,users.firstname, users.lastname, users.email, lessons.title as lesson, classes.title as class')
            ->join('users', 'users.id = teachers.user_id')
            ->join('lessons', 'lessons.id = teachers.lesson_id', 'left')
            ->join('classes', 'classes.id = teachers.class_id', 'left')
            ->orderBy('classes.title');

        if ($id != null) {
            return $data->find($id);
        } else {
           return $data->findAll();
        }
    }


}

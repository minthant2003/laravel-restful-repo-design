<?php

namespace App\Repositories;

use App\Interfaces\StudentRepositoryInterface;
use App\Models\Student;

class StudentRepository implements StudentRepositoryInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function index()
    {
        return Student::all();
    }

    public function getById($id)
    {
        return Student::find($id);
    }

    public function getByName($name)
    {
        return Student::where('name', $name)->first();
    }

    public function store(array $data)
    {
        return Student::create($data);
    }

    public function update(array $data, $id)
    {
        Student::whereId($id)->update($data);
        return self::getById($id);
    }

    public function delete($id)
    {
        Student::destroy($id);
    }
}

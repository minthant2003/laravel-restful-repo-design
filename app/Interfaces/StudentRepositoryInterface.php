<?php

namespace App\Interfaces;

interface StudentRepositoryInterface
{
    public function index();
    public function getById($id);
    public function getByName($name);
    public function store(array $data);
    public function update(array $data, $id);
    public function delete($id);
}

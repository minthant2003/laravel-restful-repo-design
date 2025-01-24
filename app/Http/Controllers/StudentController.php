<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponseClass;
use App\Models\Student;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Http\Resources\StudentResource;
use App\Interfaces\StudentRepositoryInterface;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    private StudentRepositoryInterface $studentRepositoryInterface;

    public function __construct(StudentRepositoryInterface $studentRepositoryInterface)
    {
        $this->studentRepositoryInterface = $studentRepositoryInterface;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->studentRepositoryInterface->index();
        return ApiResponseClass::sendResponse(StudentResource::collection($data), 'All Students retrieved successfully.', 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStudentRequest $request)
    {
        $newStudent = [
            'name' => $request->name,
            'age' => $request->age,
            'date_of_birth' => $request->date_of_birth
        ];
        DB::beginTransaction();
        try {
            $newStudent = $this->studentRepositoryInterface->store($newStudent);
            DB::commit();
            return ApiResponseClass::sendResponse(new StudentResource($newStudent), 'Student created successfully.', 201);
        } catch (\Exception $e) {
            return ApiResponseClass::rollback($e, 'Error while creating student.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $student = $this->studentRepositoryInterface->getById($id);
        if ($student === null) {
            return ApiResponseClass::sendResponse(null, 'Student not found.', 404);
        } else {
            return ApiResponseClass::sendResponse(new StudentResource($student), 'Student retrieved successfully.', 200);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStudentRequest $request, $id)
    {
        $updatedStudent = [
            'name' => $request->name,
            'age' => $request->age,
            'date_of_birth' => $request->date_of_birth
        ];
        DB::beginTransaction();
        try {
            $updatedStudent = $this->studentRepositoryInterface->update($updatedStudent, $id);
            DB::commit();
            return ApiResponseClass::sendResponse(new StudentResource($updatedStudent), 'Student updated successfully.', 200);
        } catch (\Exception $e) {
            return ApiResponseClass::rollback($e, 'Error while updating student.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $existingStudent = $this->studentRepositoryInterface->getById($id);
        if ($existingStudent === null) {
            return ApiResponseClass::sendResponse(null, 'Student not found.', 404);
        } else {
            $deletedStudent = $this->studentRepositoryInterface->delete($id);
            return ApiResponseClass::sendResponse(new StudentResource($deletedStudent), 'Student deleted successfully.', 200);
        }
    }
}

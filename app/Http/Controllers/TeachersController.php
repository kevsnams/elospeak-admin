<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Teacher;

use App\Http\Requests\StoreTeacher as StoreTeacherRequest;

use Hash;

class TeachersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $teachers = Teacher::orderBy('full_name', 'ASC')->get();

        return response()->json($teachers->toArray());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\StoreTeacher  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTeacherRequest $request)
    {
        $teacher = new Teacher();

        foreach ($request->input('data') as $column => $value) {
            if ($column === 'password_repeat') {
                continue;
            }

            $newValue = $value;

            if ($column == 'password') {
                $newValue = Hash::make($value);
            }

            $teacher->{$column} = $newValue;
        }

        $teacher->save();

        return response()->json([
            'success' => true,
            'id' => $teacher->id
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $teacher = Teacher::find($id);

        return response()->json($teacher);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\StoreTeacher  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreTeacherRequest $request, $id)
    {
        $teacher = Teacher::findOrFail($id);

        foreach ($request->input('data') as $column => $value) {
            if ($column == 'password_repeat') {
                continue;
            }

            $newValue = $value;

            if ($column == 'password') {
                $newValue = Hash::make($value);
            }

            $teacher->{$column} = $newValue;
        }

        $teacher->save();

        return response()->json([true]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $teacher = Teacher::find($id);
        $teacher->delete();

        return response()->json([
            'success' => true
        ]);
    }

    public function getEducationalAttainment()
    {
        return response()->json(Teacher::educationalAttainments()->toArray());
    }
}

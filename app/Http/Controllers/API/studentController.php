<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Student;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class studentController extends Controller
{
    public function index() 
    {
        $students = Student::all();
        $data = [
            'data' => $students,
            'status' => 200
        ];
        return response()->json($data, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:student',
            'phone' => 'required|digits:10',
            'language' => 'required|in:English,Spanish,French'
        ]);

        if ($validator->fails()) {
            $data = [
                'massage' => 'Error en la validación de los datos',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        $student = Student::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'language' => $request->language
        ]);

        if (!$student) {
            return response()->json([
                'massage' => 'Error al crear el estudiante',
                'status' => 500
            ], 500);
        }

        return response()->json([
            'student' => $student,
            'status' => 201
        ], 201);
    }

    public function show($id)
    {
        $student = Student::find($id);
        if (!$student) {
            $data = [
                'message' => 'Estudiante no encontrado',
                'status' => 404
            ];
        } else {
            $data = [
                'data' => $student,
                'status' => 200
            ];
        }

        return response()->json($data, $data['status']);
    }

    public function destroy($id) 
    {
        $student = Student::find($id);
        if (!$student) {
            $data = [
                'message' => 'Estudiante no encontrado',
                'status' => 404
            ];
        } else {
            $student->delete();
            $data = [
                'message' => 'Estudiante eliminado',
                'status' => 200
            ];
        }

        return response()->json($data, $data['status']);
    }

    public function update(Request $request, $id)
    {
        $student = Student::find($id);
        if (!$student) {
            $data = [
                'message' => 'Estudiante no encontrado',
                'status' => 404
            ];
            return response()->json($data, $data['status']);
        }

        $validator = Validator::make( $request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:student',
            'phone' => 'required|digits:10',
            'language' => 'required|in:English,Spanish,French'
        ]);

        if ($validator->fails()) {
            $data = [
                'massage' => 'Error en la validación de los datos',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        $student->name = $request->name;
        $student->email = $request->email;
        $student->phone = $request->phone;
        $student->language = $request->language;

        $student->save();

        return response()->json([
            'message' => 'Estudiante actualizado',
            'data' => $student,
            'status' => 200
        ]);

    }

    public function updatePartial(Request $request, $id)
    {
        $student = Student::find($id);
        if (!$student) {
            $data = [
                'message' => 'Estudiante no encontrado',
                'status' => 404
            ];
            return response()->json($data, $data['status']);
        }

        $validator = Validator::make( $request->all(), [
            'name' => 'max:255',
            'email' => 'email',
            'phone' => 'digits:10',
            'language' => 'in:English,Spanish,French'
        ]);

        if ($validator->fails()) {
            $data = [
                'massage' => 'Error en la validación de los datos',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        if ($request->has('name')) {
            $student->name = $request->name;
        }
        if ($request->has('email')) {
            $student->email = $request->email;
        }
        if ($request->has('phone')) {
            $student->phone = $request->phone;
        }
        if ($request->has('language')) {
            $student->language = $request->language;
        }

        $student->save();

        return response()->json([
            'message' => 'Estudiante actualizado',
            'data' => $student,
            'status' => 200
        ]);

    }
}

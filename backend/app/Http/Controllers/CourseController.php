<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        // Para estudiantes/público, mostrar solo los cursos activos
        return response()->json(Course::where('status', 1)->get());
    }

    public function indexAdmin()
    {
        return response()->json(Course::all());
    }

    public function show($id)
    {
        $course = Course::findOrFail($id);
        return response()->json($course);
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'image_file' => 'nullable|image|mimes:png,jpg,jpeg',
            'content' => 'nullable|string',
            'video_link' => 'nullable|string|max:255',
            'status' => 'integer'
        ]);

        $data = $request->all();

        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $count = Course::count() + 1;
            $extension = $file->getClientOriginalExtension();
            $filename = 'img' . str_pad($count, 2, '0', STR_PAD_LEFT) . '.' . $extension;
            
            // Ruta al frontend public
            $destinationPath = base_path('../frontend/public/resources/img');
            $file->move($destinationPath, $filename);
            
            $data['image'] = '../resources/img/' . $filename;
        } else {
            $data['image'] = '../resources/img/default.png';
        }

        $course = Course::create($data);
        return response()->json($course, 201);
    }

    public function update(Request $request, $id)
    {
        $course = Course::findOrFail($id);
        
        $request->validate([
            'course_name' => 'sometimes|required|string|max:100',
            'image_file' => 'nullable|image|mimes:png,jpg,jpeg'
        ]);

        $data = $request->all();

        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            // Usamos el ID del curso para el nombre o el contador. 
            // El usuario pidió img0x. Usaremos el ID para consistencia si es posible, 
            // o simplemente seguiremos el patrón.
            $extension = $file->getClientOriginalExtension();
            $filename = 'img' . str_pad($course->course_id, 2, '0', STR_PAD_LEFT) . '.' . $extension;
            
            $destinationPath = base_path('../frontend/public/resources/img');
            $file->move($destinationPath, $filename);
            
            $data['image'] = '../resources/img/' . $filename;
        }

        $course->update($data);
        return response()->json($course);
    }

    public function destroy($id)
    {
        $course = Course::findOrFail($id);
        $course->delete();
        return response()->json(['message' => 'Course deleted successfully']);
    }
}

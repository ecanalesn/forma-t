<?php

namespace App\Http\Controllers;

use App\Models\CourseRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseRequestController extends Controller
{
    // Para que el administrador vea todas las solicitudes
    public function index()
    {
        $requests = CourseRequest::with(['user', 'course'])->get();
        return response()->json($requests);
    }

    // Para que el estudiante vea sus propias solicitudes
    public function myRequests()
    {
        $user = Auth::user();
        $requests = CourseRequest::with('course')->where('user_id', $user->user_id)->get();
        return response()->json($requests);
    }

    // El estudiante solicita acceso a un curso
    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,course_id'
        ]);

        $user = Auth::user();

        // Comprobar si la solicitud ya existe
        $existing = CourseRequest::where('user_id', $user->user_id)
            ->where('course_id', $request->course_id)
            ->first();

        if ($existing) {
            return response()->json(['message' => 'Ya has solicitado este curso'], 400);
        }

        $courseRequest = CourseRequest::create([
            'user_id' => $user->user_id,
            'course_id' => $request->course_id,
            'status' => 'pending',
            'allowed_access' => false,
            'is_read' => false
        ]);

        return response()->json($courseRequest, 201);
    }

    // El administrador actualiza el estado
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected'
        ]);

        $courseRequest = CourseRequest::findOrFail($id);
        $courseRequest->status = $request->status;
        $courseRequest->allowed_access = ($request->status === 'approved');
        $courseRequest->is_read = true;
        
        $courseRequest->save();

        return response()->json($courseRequest);
    }
}

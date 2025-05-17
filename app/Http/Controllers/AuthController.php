<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    // ====== Étudiant login ======
    public function studentLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:students',
            'password' => 'required',
        ]);

        $student = Student::where('email', $request->email)->first();

        if (!$student || !Hash::check($request->password, $student->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $student->createToken('student-token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'student' => $student,
            
        ]);
    }

    // ====== Admin login ======
    public function adminLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required',
        ]);

        $admin = User::where('email', $request->email)->first();

        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);

        }

        $token = $admin->createToken('admin-token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'admin' => $admin,
        ]);
    }

    public function updatePasswordStudent(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $student = $request->user();

        if (!Hash::check($request->current_password, $student->password)) {
            return response()->json([
                'message' => 'The current password is incorrect.',
            ], 400);
        }

        $student->password = Hash::make($request->new_password);
        $student->save();

        return response()->json([
            'message' => 'Password updated successfully.',
        ]);
    }

    public function updatePasswordAdmin(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $admin = $request->user();

        if (!Hash::check($request->current_password, $admin->password)) {
            return response()->json([
                'message' => 'The current password is incorrect.',
            ], 400);
        }

        $admin->password = Hash::make($request->new_password);
        $admin->save();

        return response()->json([
            'message' => 'Password updated successfully.',
        ]);
    }

        public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Déconnexion réussie.']);
    }
}
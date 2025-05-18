<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use Dom\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $admins = User::all(); 
        return response()->json($admins);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $admin = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'message' => 'Admin created successfully',
            'admin'   => $admin,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $admin)
    {
        return response()->json($admin);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $admin = User::find($id);

        if (!$admin) {
            return response()->json(['message' => 'Admin not found'], 404);
        }

        $admin->delete();

        return response()->json(['message' => 'Admin deleted successfully']);
    }



     // ✅ Voir tous les étudiants
    public function getAllStudents()
    {
        return response()->json(Student::all());
    }

    // ✅ Supprimer un étudiant
    public function deleteStudent($id)
    {
        $student = Student::findOrFail($id);
        $student->delete();

        return response()->json(['message' => 'Étudiant supprimé avec succès.']);
    }

    // ✅ Voir tous les documents (ou uniquement en attente si tu veux)
    public function getAllDocuments()
    {
        return response()->json(Document::with('student')->latest()->get());
    }

    // ✅ Valider un document
    public function validateDocument($id)
    {
        $document = Document::findOrFail($id);
        $document->status = 'validated';
        $document->save();

        return response()->json(['message' => 'Document validé.']);
    }

    // ✅ Rejeter un document
    public function rejectDocument($id)
    {
        $document = Document::findOrFail($id);
        $document->status = 'rejected';
        $document->save();

        return response()->json(['message' => 'Document rejeté.']);
    }

    // ✅ Modifier un document
    public function updateDocument(Request $request, $id)
    {
        $document = Document::findOrFail($id);

        $request->validate([
            'title' => 'sometimes|string',
            'description' => 'sometimes|string',
        ]);

        $document->update($request->only(['title', 'description']));

        return response()->json(['message' => 'Document mis à jour.', 'document' => $document]);
    }

    // ✅ Supprimer un document
    public function deleteDocument($id)
    {
        $document = Document::findOrFail($id);
        $document->delete();

        return response()->json(['message' => 'Document supprimé.']);
    }
}

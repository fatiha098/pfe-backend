<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Container\Attributes\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // Accessible sans auth
    public function index()
    {
        $documents = Document::with('student')->latest()->get();
        return response()->json($documents);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'file_path' => 'required|file|mimes:pdf,doc,docx,ppt,pptx|max:10240',
            'description' => 'nullable|string|max:1000',
            'user_id' => 'required|exists:users,id',
            'is_validated' => 'nullable|boolean',
        ]);

        $path = $request->file('file_path')->store('documents', 'public');

        $document = Document::create([
            'title' => $request->title,
            'description' => $request->description,
            'file_path' => $path,
            'student_id' => auth('student')->id(),
            'is_validated' => $request->is_validated ?? false,
        ]);

        return response()->json([
            'message' => 'Document uploaded successfully',
            'document' => $document
        ], 201);

    }

    public function download($id)
    {

        $document = Document::findOrFail($id);

        return Storage::download($document->file_path);
    }

    /**
     * Display the specified resource.
     */
    // Accessible sans auth
    public function show(Request $request)
    {
        $query = $request->input('q');

        $document = Document::where('title', 'like', "%$query%")
                        ->orWhere('description', 'like', "%$query%")
                        ->get();
        
        return response()->json([
            'message' => 'Document est trouvé',
            'document' => $document
        ], 201); 
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $document = Document::findOrFail($id);

        $request->validate([
            'description' => 'nullable|string|max:1000',
            'is_validated' => 'boolean',
        ]);

        $document->update([
            'description' => $request->description ?? $document->description,
            'is_validated' => $request->has('is_validated') ? $request->is_validated : $document->is_validated,
        ]);

        return response()->json([
            'message' => 'Document updated successfully',
            'document' => $document
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $document = Document::findOrFail($id);

        if ($document->student_id !== auth('student')->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        Storage::delete($document->file_path);
        $document->delete();

        return response()->json(['message' => 'Document deleted']);
    }
}

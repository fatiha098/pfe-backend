<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Dom\Document;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
     public function index($documentId)
    {
        $comments = Comment::with('student')
            ->where('document_id', $documentId)
            ->latest()
            ->get();

        return response()->json($comments);
    }

    /**
     * Store a newly created resource in storage.
     */
     public function store(Request $request, $documentId)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'content' => 'required|string|max:1000',
        ]);

        $document = Document::findOrFail($documentId);

        $comment = Comment::create([
            'content' => $request->input('content'),
            'student_id' => $request->input('student_id'),
            'document_id' => $document->id,
        ]);

        return response()->json([
            'message' => 'Commentaire ajouté avec succès',
            'comment' => $comment->load('student')
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
         $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $comment = Comment::findOrFail($id);
        $comment->content = $request->input('content');
        $comment->save();

        return response()->json([
            'message' => 'Commentaire mis à jour avec succès',
            'comment' => $comment->load('student')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
     public function destroy(Comment $comment, Request $request, $id)
    {
        if ($comment->student_id !== $request->input('student_id')) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        $comment = Comment::findOrFail($id);
        $comment->delete();

        return response()->json([
            'message' => 'Commentaire supprimé avec succès'
        ]);
    }
}

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
    public function index(Request $request)
    {
        if ($request->has('document_id')) {
            $comments = Comment::where('document_id', $request->document_id)->with('user')->get();
        } else {
            $comments = Comment::with(['user', 'document'])->get();
        }

        return response()->json($comments);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $documentId)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $document = Document::findOrFail($documentId);

        $comment = Comment::create([
            'content' => $request->content,
            'user_id' => Auth::id(),
            'document_id' => $document->id,
        ]);

        return response()->json(['message' => 'Comment added successfully', 'comment' => $comment], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $comment = Comment::with(['user', 'document'])->findOrFail($id);
        return response()->json($comment);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);

        // Optionnel : autoriser seulement le propriétaire du commentaire
        if ($comment->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $comment->update([
            'content' => $request->content,
        ]);

        return response()->json(['message' => 'Comment updated successfully', 'comment' => $comment]);
    }

    /**
     * Remove the specified resource from storage.
     */
     public function destroy($id)
    {
        $comment = Comment::findOrFail($id);

        // Optionnel : autoriser seulement le propriétaire ou un admin
        if ($comment->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $comment->delete();

        return response()->json(['message' => 'Comment deleted successfully']);
    }
}

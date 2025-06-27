<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        // Reacties van de gebruiker (op profielen en spellen)
        $comments = $user->comments()->with('commentable')->orderByDesc('created_at')->get();
        return view('comments', compact('comments'));
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
            'commentable_id' => 'required|integer',
            'commentable_type' => 'required|string',
        ]);

        if ($request->commentable_type === 'App\Models\User') {
            $commentable = \App\Models\User::findOrFail($request->commentable_id);
        } elseif ($request->commentable_type === 'App\Models\Game') {
            $commentable = \App\Models\Game::findOrFail($request->commentable_id);
        } else {
            abort(400, 'Invalid commentable type: [' . $request->commentable_type . ']');
        }

        $comment = new \App\Models\Comment([
            'user_id' => auth()->id(),
            'content' => $request->content,
        ]);
        $commentable->comments()->save($comment);

        return back();
    }
}

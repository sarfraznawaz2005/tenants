<?php

namespace App\Http\Controllers;

use App\Models\Rent;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Rent $rent)
    {
        $request->validate([
            'body' => 'required|string',
        ]);

        $comment = new Comment($request->all());
        $comment->rent_id = $rent->id;
        $comment->save();

        return redirect()->route('rents.show', $rent)->with('success', 'Comment added successfully.');
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();

        return redirect()->back()->with('success', 'Comment deleted successfully.');
    }
}

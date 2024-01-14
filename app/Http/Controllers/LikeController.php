<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LikeController extends Controller
{
    public function like(Request $request, Post $post)
    {

        try {
            $like = [
                'post_id' => $post->id,
                'user_id' => auth()->id(),
            ];

            Like::create($like);

            return response()->json(['likes' => $post->likesCount()]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::all();

        return response()->json([
            'success' => true,
            'data' => $posts
        ]);
    }

    public function getMyPosts()
    {
        $posts = Post::where([
            ['user_id', auth()->user()->id]
        ])
        ->get();

        return response()->json([
            'success' => true,
            'data' => $posts
        ]);
    }

    public function show($id)
    {
        $post = Post::where([
            'id', $id
        ])
        ->first();

        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Post not found '
            ], 400);
        }

        return response()->json([
            'success' => true,
            'data' => $post->toArray()
        ], 400);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'description' => 'required'
        ]);

        $post = new Post();
        $post->title = $request->title;
        $post->description = $request->description;

        if (auth()->user()->posts()->save($post)) {
            return response()->json([
                'success' => true,
                'data' => $post->toArray()
            ]);
        }
        else {
            return response()->json([
                'success' => false,
                'message' => 'Post not added'
            ], 500);
        }
    }

    public function update(Request $request)
    {
        $post = Post::where([
            ['id', $request->id],
            ['user_id', auth()->user()->id]
        ])
        ->first();

        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorised access'
            ], 403);
        }

        $updated = Post::where([
            ['id', $request->id]
        ])
        ->update([
            'title' => $request->title,
            'description' => $request->description
        ]);

        if ($updated)
            return response()->json([
                'success' => true
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Post can not be updated'
            ], 500);
    }

    public function destroy($id)
    {
        $post = auth()->user()->posts()->find($id);

        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorised access'
            ], 400);
        }

        if ($post->delete()) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Post can not be deleted'
            ], 500);
        }
    }
}

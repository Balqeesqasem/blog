<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth:sanctum")->except(["index", "show"]);
        $this->middleware("admin")->only(["store", "update", "destroy"]);
    }

    public function index()
    {
        $posts = Post::all();
        return response()->json($posts);
    }

    public function show($id)
    {
        $post = Post::findOrFail($id);
        return response()->json($post);
    }

    public function store(Request $request)
    {
        $request->validate([
            "title" => "required|string|max:255",
            "content" => "required|string",
        ]);

        $post = Post::create([
            "title" => $request->title,
            "content" => $request->content,
        ]);

        return response()->json($post, 201);
    }

    public function update(Request $request, $id)
    {
        // Find the post or fail if not found
        $post = Post::findOrFail($id);

        // Validate only the provided attributes
        $request->validate([
            "title" => "sometimes|required|string|max:255",
            "content" => "sometimes|required|string",
        ]);

        // Only update the provided attributes
        $post->fill($request->only(["title", "content"]));
        $post->save();

        // Return the updated post
        return response()->json($post);
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();

        return response()->json(["message" => "Post deleted successfully"]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth:sanctum");
    }

    private function findPostOrFail($postId)
    {
        $post = Post::find($postId);
        if (!$post) {
            return response()->json(["message" => "Post not found."], 404);
        }
        return $post;
    }

    private function findCommentOrFail($postId, $commentId)
    {
        $comment = Comment::where("post_id", $postId)->find($commentId);
        if (!$comment) {
            return response()->json(["message" => "Comment not found."], 404);
        }
        return $comment;
    }

    private function denyIfAdmin($user)
    {
        if ($user->role === "admin") {
            return response()->json(
                [
                    "message" =>
                        "Access denied. Admins cannot perform this action.",
                ],
                403
            );
        }
        return null;
    }

    public function store(Request $request, $postId)
    {
        $post = $this->findPostOrFail($postId);
        if ($post instanceof \Illuminate\Http\JsonResponse) {
            return $post;
        }

        $user = Auth::user();
        if ($response = $this->denyIfAdmin($user)) {
            return $response;
        }

        $request->validate([
            "body" => "required|string",
        ]);

        $comment = Comment::create([
            "user_id" => $user->id,
            "post_id" => $postId,
            "body" => $request->body,
        ]);

        return response()->json($comment, 201);
    }

    public function update(Request $request, $postId, $id)
    {
        $post = $this->findPostOrFail($postId);
        if ($post instanceof \Illuminate\Http\JsonResponse) {
            return $post;
        }

        $comment = $this->findCommentOrFail($postId, $id);
        if ($comment instanceof \Illuminate\Http\JsonResponse) {
            return $comment;
        }

        $user = Auth::user();
        if (
            $response =
                $this->denyIfAdmin($user) || $comment->user_id !== $user->id
        ) {
            return $response ?:
                response()->json(["message" => "Access denied."], 403);
        }

        $request->validate([
            "body" => "required|string",
        ]);

        $comment->update($request->only("body"));

        return response()->json($comment);
    }

    public function destroy($postId, $id)
    {
        $post = $this->findPostOrFail($postId);
        if ($post instanceof \Illuminate\Http\JsonResponse) {
            return $post;
        }

        $comment = $this->findCommentOrFail($postId, $id);
        if ($comment instanceof \Illuminate\Http\JsonResponse) {
            return $comment;
        }

        $user = Auth::user();
        if (
            $response =
                $this->denyIfAdmin($user) || $comment->user_id !== $user->id
        ) {
            return $response ?:
                response()->json(["message" => "Access denied."], 403);
        }

        $comment->delete();

        return response()->json(["message" => "Comment deleted successfully."]);
    }

    public function index($postId)
    {
        $post = $this->findPostOrFail($postId);
        if ($post instanceof \Illuminate\Http\JsonResponse) {
            return $post;
        }

        $comments = Comment::where("post_id", $postId)->get();

        return response()->json($comments);
    }

    public function show($postId, $id)
    {
        $post = $this->findPostOrFail($postId);
        if ($post instanceof \Illuminate\Http\JsonResponse) {
            return $post;
        }

        $comment = $this->findCommentOrFail($postId, $id);
        if ($comment instanceof \Illuminate\Http\JsonResponse) {
            return $comment;
        }

        return response()->json($comment);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class CommentController extends Controller
{
    public function store(Request $request, $postId)
    {
        try {
            $post = Post::find($postId);

            if (!$post) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Post no encontrado'
                ], 404);
            }

            $request->validate([
                'content' => 'required|string'
            ]);

            $comment = Comment::create([
                'post_id' => $postId,
                'user_id' => Auth::id(),
                'content' => $request->content
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Comentario creado exitosamente',
                'data' => $comment
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al crear el comentario'
            ], 500);
        }
    }
}

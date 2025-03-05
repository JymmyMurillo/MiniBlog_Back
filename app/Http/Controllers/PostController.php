<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class PostController extends Controller
{
    public function index()
    {
        try {
            $posts = Post::with(['user', 'comments.user'])->latest()->get();

            return response()->json([
                'status' => 'success',
                'data' => $posts
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener los posts'
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string'
            ]);

            $post = Post::create([
                'user_id' => Auth::id(),
                'title' => $request->title,
                'content' => $request->content
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Post creado exitosamente',
                'data' => $post
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
                'message' => 'Error al crear el post'
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $post = Post::with(['user', 'comments.user'])->find($id);

            if (!$post) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Post no encontrado'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'data' => $post
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener el post'
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $post = Post::find($id);

            if (!$post) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Post no encontrado'
                ], 404);
            }

            if ($post->user_id !== Auth::id()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No autorizado para esta acción'
                ], 403);
            }

            $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string'
            ]);

            $post->update($request->all());

            return response()->json([
                'status' => 'success',
                'message' => 'Post actualizado exitosamente',
                'data' => $post
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al actualizar el post'
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $post = Post::find($id);

            if (!$post) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Post no encontrado'
                ], 404);
            }

            if ($post->user_id !== Auth::id()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No autorizado para esta acción'
                ], 403);
            }

            $post->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Post eliminado exitosamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al eliminar el post'
            ], 500);
        }
    }
}

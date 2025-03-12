<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostStoreFormRequest;
use App\Http\Requests\PostUpdateFormRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\QueryBuilder;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $posts = QueryBuilder::for(Post::class)
                ->allowedFilters(['title', 'content'])
                ->allowedSorts(['title', 'content', 'created_at', 'updated_at'])
                ->allowedIncludes(['user'])
                ->paginate(10);

            return PostResource::collection($posts)->additional([
                'message' => 'Posts retrieved successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while retrieving posts',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostStoreFormRequest $request)
    {
        try {
            DB::beginTransaction();
            $post = Post::create($request->validated());
            DB::commit();

            return response()->json([
                'message' => 'Post created successfully',
                'data' => PostResource::make($post),
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'An error occurred while creating the post',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        try {
            $post = QueryBuilder::for(Post::class)
                ->allowedIncludes(['user'])
                ->findOrFail($post->id);

            return response()->json([
                'message' => 'Post retrieved successfully',
                'data' => PostResource::make($post),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while retrieving the post',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PostUpdateFormRequest $request, Post $post)
    {
        try {
            DB::beginTransaction();
            $post->update($request->validated());
            DB::commit();

            return response()->json([
                'message' => 'Post updated successfully',
                'data' => PostResource::make($post),
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'An error occurred while updating the post',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        try {
            $post->delete();

            return response()->json([
                'message' => 'Post deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while deleting the post',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}

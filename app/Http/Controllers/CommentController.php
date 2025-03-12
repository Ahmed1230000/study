<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentStoreFormRequest;
use App\Http\Requests\CommentUpdateFormRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\QueryBuilder;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $comments = QueryBuilder::for(Comment::class)
                ->allowedFilters(['comment', 'status', 'user_id', 'post_id'])
                ->allowedSorts(['id', 'comment', 'status', 'user_id', 'post_id'])
                ->allowedIncludes(['user', 'post'])
                ->paginate(10);

            return CommentResource::collection($comments)->additional([
                'message' => 'Comments retrieved successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while retrieving comments',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CommentStoreFormRequest $request)
    {
        try {
            DB::beginTransaction();
            $comment = Comment::create($request->validated());
            DB::commit();

            return response()->json([
                'message' => 'Comment created successfully',
                'data' => CommentResource::make($comment),
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'An error occurred while creating the comment',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        try {
            $comment = QueryBuilder::for(Comment::class)
                ->allowedFilters(['comment', 'status', 'user_id', 'post_id'])
                ->allowedSorts(['id', 'comment', 'status', 'user_id', 'post_id'])
                ->allowedIncludes(['user', 'post'])
                ->findOrFail($comment->id);

            return response()->json([
                'message' => 'Comment retrieved successfully',
                'data' => CommentResource::make($comment),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while retrieving the comment',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CommentUpdateFormRequest $request, Comment $comment)
    {
        try {
            DB::beginTransaction();
            $comment->update($request->validated());
            DB::commit();

            return response()->json([
                'message' => 'Comment updated successfully',
                'data' => CommentResource::make($comment),
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'An error occurred while updating the comment',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        try {
            $comment->delete();

            return response()->json([
                'message' => 'Comment deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while deleting the comment',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}

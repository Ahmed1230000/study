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
            $comment = QueryBuilder::for(Comment::class)
                ->allowedFilters(['comment', 'status', 'user_id', 'post_id'])
                ->allowedSorts(['id', 'comment', 'status', 'user_id', 'post_id'])
                ->allowedIncludes(['user', 'post'])
                ->paginate(10);
            if ($comment->isEmpty()) {
                return response()->json([
                    'message' => 'Not Found Any Comments',
                    'data' => []
                ], 200);
            }
            return response()->json([
                'message' => 'Comments',
                'data' => CommentResource::collection($comment)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error: ' . $e->getMessage()
            ], 400);
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
            $comment->save();
            if ($comment) {
                DB::commit();
                return response()->json([
                    'message' => 'Comment Created',
                    'data' => CommentResource::make($comment)
                ], 201);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error: ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($comment)
    {
        try {
            $comment = QueryBuilder::for(Comment::class)
                ->allowedFilters(['comment', 'status', 'user_id', 'post_id'])
                ->allowedSorts(['id', 'comment', 'status', 'user_id', 'post_id'])
                ->allowedIncludes(['user', 'post'])
                ->findOrFail($comment);
            if (!$comment) {
                return response()->json([
                    'message' => 'Comment Not Found'
                ], 404);
            }
            return response()->json([
                'message' => 'Comment',
                'data' => CommentResource::make($comment)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error: ' . $e->getMessage()
            ], 400);
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
            $comment->save();
            if ($comment) {
                DB::commit();
                return response()->json([
                    'message' => 'Comment Updated',
                    'data' => CommentResource::make($comment)
                ], 200);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error: ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        try {
            DB::beginTransaction();
            $comment->delete();
            DB::commit();
            return response()->json([
                'message' => 'Comment Deleted'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error: ' . $e->getMessage()
            ], 400);
        }
    }
}

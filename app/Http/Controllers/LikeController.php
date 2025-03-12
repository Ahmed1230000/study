<?php

namespace App\Http\Controllers;

use App\Http\Requests\LikeStoreFormRequest;
use App\Http\Requests\LikeUpdateFormRequest;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LikeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function store(LikeStoreFormRequest $request)
    {
        try {
            DB::beginTransaction();
            $like = Like::create($request->validated());
            $like->save();
            DB::commit();
            if ($like) {
                return response()->json([
                    'message' => 'Like Created',
                    'data' => $like
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
     * Handle the incoming request.
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $like = Like::find($id);
            if ($like) {
                $like->delete();
                DB::commit();
                return response()->json([
                    'message' => 'Like Deleted'
                ], 200);
            }
            return response()->json([
                'message' => 'Like Not Found'
            ], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error: ' . $e->getMessage()
            ], 400);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Room;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $posts = Post::where('room_id', $request->route('room_id'))->orderBy('created_at', 'desc')
        //     ->get();
        // return PostResource::collection($posts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        // if (!($request->user_handling && $room->users()->where('user_id', $request->user_handling)->exists()))
        //     return new JsonResponse("Unauthorized", 403);

        $data = $request->validated();
        $data["room_id"] = $request->route('room');
        $data["added_by"] = Auth::user()->id;
        $result = Post::create($data);
        if ($result && $request->hasFile('image')) {
            $image = $result->addImage($request->file('image'));
        }

        return new PostResource($result);
    }

    /**
     * Display the specified resource.
     */
    public function show(Room $room, Post $post)
    {
        return new PostResource($post);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Room $room, Post $post)
    {


        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($post->image) {
                Storage::disk('public')->delete($post->image->url);

                $post->image->delete();
            }
            $image = $post->addImage($request->file('image'));
        }

        $post->update($data);
        return new PostResource($post);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //
    }
}

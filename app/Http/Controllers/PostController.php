<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        //    $room = Room::where("id",$request->room_id);

        //    $a = Auth::User()->rooms()->wherePivot('role', '<', 3)->wherePivot('status', 1)->find($request->room_id);

        //    if(!$a){
        //     return new JsonResponse("You are not a member of this room", 403);
        //    }
        $data = $request->validated();
        $data["room_id"] = $request->route('room');
        $data["added_by"] = Auth::user()->id;
        $result = Post::create($data);

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
        // dd($request);

        $data = $request->validated();
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

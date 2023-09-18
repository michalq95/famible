<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Room;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
    //    $room = Room::where("id",$request->room_id);
    
       $a = Auth::User()->rooms()->wherePivot('role', '<', 3)->wherePivot('status', 1)->find($request->room_id);

       if(!$a){
        return new JsonResponse("You are not a member of this room", 403);
       }
       $data = $request->validated();
       
       $data["added_by"]=Auth::user()->id;
    //    dd($data);
       $result = Post::create($data);
       
       return new PostResource($result);

    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //
    }
}

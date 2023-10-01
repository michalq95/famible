<?php

namespace App\Http\Controllers;

use App\Http\Resources\OtherUserResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UsersController extends Controller
{


    public function index(Request $request)
    {
        $keyword = $request->query('keyword');
        if (!$keyword) {
            return response(['error' => 'Provide user name'], 422);
        }
        $query = User::with(['image'])->where('name', 'LIKE', "%$keyword%")->orderBy('created_at', 'desc')->simplepaginate(2);;
        return OtherUserResource::collection($query);
    }

    public function setImage(Request $request)
    {

        if ($request->hasFile('image')) {
            if (Auth::user()->image) {
                Storage::disk('public')->delete(Auth::user()->image->url);

                Auth::user()->image->delete();
            }
            $image = Auth::user()->addImage($request->file('image'));
        }
        return new UserResource(Auth::user());
    }

    public function markAsRead(Request $request)
    {

        if (!$request->id)
            return new JsonResponse("Provide notification", 404);
        // dd(Auth::user()->unreadNotifications()->where("id", $request->id)->first());
        $notification = Auth::user()->unreadNotifications->where("id", $request->id)->first();
        if (!$notification)
            return new JsonResponse("Notification not found", 404);
        $notification->markAsRead();
        return new JsonResponse(Auth::user()->unreadNotifications, 200);
    }
    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications()->update(['read_at' => now()]);
        return new JsonResponse(Auth::user()->unreadNotifications, 200);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Resources\OtherUserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

<?php

namespace App\Http\Controllers;

use App\Http\Resources\OtherUserResource;
use App\Models\User;
use Illuminate\Http\Request;

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
}

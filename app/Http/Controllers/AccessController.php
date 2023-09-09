<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAccessRequest;
use App\Models\Access;

use Illuminate\Http\JsonResponse;

class AccessController extends Controller
{
    public function store(CreateAccessRequest $request)
    {
        $result = Access::create($request->validated());
        dd("success");
        return new JsonResponse("Sent invitation to user");
    }}
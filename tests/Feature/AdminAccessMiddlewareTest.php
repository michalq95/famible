<?php

namespace Tests\Feature;

use App\Enums\UserStatusEnum;
use App\Http\Middleware\AdminAccessMiddleware;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Mockery;
use Tests\TestCase;

class AdminAccessMiddlewareTest extends TestCase
{
    protected $middleware;
    protected $request;
    protected $nextClosure;
    public function setUp(): void
    {
        parent::setUp();

        $this->middleware = new AdminAccessMiddleware();

        $this->request = Mockery::mock(Request::class);

        $this->nextClosure = function ($request) {
            return new Response('Next middleware executed');
        };

        Auth::shouldReceive('id')->andReturn(1);
    }
    public function testAdminAccessMiddlewareWithSuperAdmin()
    {
        $userAccess = (object) ['user_id' => 1, 'role' => UserStatusEnum::SuperAdmin];

        $room = Mockery::mock('Room');
        $room->shouldReceive('accesses')->andReturnSelf();
        $room->shouldReceive('find')->andReturn($room);

        Auth::shouldReceive('id')->andReturn(1);

        $this->request->shouldReceive('route')->with('room')->andReturn(1);
        $this->request->shouldReceive('input')->with('room_id')->andReturn(null);
        $this->request->attributes = new \Illuminate\Support\Fluent([]);

        $response = $this->middleware->handle($this->request, $this->nextClosure);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testAdminAccessMiddlewareWithAdmin()
    {
        $userAccess = (object) ['user_id' => 1, 'role' => UserStatusEnum::Admin];


        $room = Mockery::mock('Room');
        $room->shouldReceive('accesses')->andReturnSelf();
        $room->shouldReceive('find')->andReturn($room);

        Auth::shouldReceive('id')->andReturn(1);

        $this->request->shouldReceive('route')->with('room')->andReturn(1);
        $this->request->shouldReceive('input')->with('room_id')->andReturn(null);
        $this->request->attributes = new \Illuminate\Support\Fluent([]);

        $response = $this->middleware->handle($this->request, $this->nextClosure);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }


    public function testAdminAccessMiddlewareWithRoomNotFound()
    {
        $this->request->shouldReceive('route')->with('room')->andReturn(null);
        $this->request->shouldReceive('input')->with('room_id')->andReturn(2);

        $response = $this->middleware->handle($this->request, $this->nextClosure);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals('"Room not found"', $response->getContent());
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testAdminAccessMiddlewareWithoutRoomId()
    {
        $request = new Request();

        $response = $this->middleware->handle($request, $this->nextClosure);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals('"Provide ID of the room"', $response->getContent());
        $this->assertEquals(422, $response->getStatusCode());
    }
}

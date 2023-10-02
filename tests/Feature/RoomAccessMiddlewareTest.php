<?php

namespace Tests\Feature;

use App\Enums\AccessStatusEnum;
use App\Http\Middleware\RoomAccessMiddleware;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Mockery;
use PHPUnit\Framework\TestCase;

class RoomAccessMiddlewareTest extends TestCase
{
    protected $middleware;
    protected $request;
    protected $nextClosure;

    public function setUp(): void
    {
        parent::setUp();

        $this->middleware = new RoomAccessMiddleware();

        $this->request = Mockery::mock(Request::class);

        $this->nextClosure = function ($request) {
            return new Response('Next middleware executed');
        };

        Auth::shouldReceive('id')->andReturn(1);
    }

    public function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }

    public function testRoomAccessMiddlewareWithValidAccess()
    {
        $room = Mockery::mock('Room');
        $room->shouldReceive('accesses')->andReturnSelf();
        $room->shouldReceive('where')->andReturnSelf();
        $room->shouldReceive('first')->andReturn((object) ['user_id' => 1, 'status' => AccessStatusEnum::Accepted]);

        $this->request->shouldReceive('route')->with('room')->andReturn($room);

        $response = $this->middleware->handle($this->request, $this->nextClosure);

        $this->assertEquals('Next middleware executed', $response->getContent());
    }

    public function testRoomAccessMiddlewareWithInvalidAccess()
    {
        $room = Mockery::mock('Room');
        $room->shouldReceive('accesses')->andReturnSelf();
        $room->shouldReceive('where')->andReturnSelf();
        $room->shouldReceive('first')->andReturn(null);

        $this->request->shouldReceive('route')->with('room')->andReturn($room);

        $response = $this->middleware->handle($this->request, $this->nextClosure);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals('"Unauthorized"', $response->getContent());
        $this->assertEquals(403, $response->getStatusCode());
    }

    public function testRoomAccessMiddlewareWithNoRoom()
    {
        $room = null;

        $this->request->shouldReceive('route')->with('room')->andReturn($room);

        $response = $this->middleware->handle($this->request, $this->nextClosure);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals('"Room not found"', $response->getContent());
        $this->assertEquals(404, $response->getStatusCode());
    }
}

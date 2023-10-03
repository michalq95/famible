<?php

namespace Tests\Feature;

use App\Http\Middleware\CheckRoomMembership;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Mockery;
use PHPUnit\Framework\TestCase;

class CheckRoomMembershipTest extends TestCase
{
    protected $middleware;
    protected $request;
    protected $nextClosure;
    public function setUp(): void
    {
        parent::setUp();

        $this->middleware = new CheckRoomMembership();

        $this->request = Mockery::mock(Request::class);

        $this->nextClosure = function ($request) {
            return new Response('Next middleware executed');
        };

        Auth::shouldReceive('id')->andReturn(1);
    }

    public function testCheckRoomMembershipWithValidMembership()
    {
        // Mock the user object with a valid room membership
        $user = Mockery::mock('User');
        $user->shouldReceive('rooms')->andReturnSelf();
        $user->shouldReceive('wherePivot')->andReturnSelf();
        $user->shouldReceive('find')->andReturn((object) ['id' => 1]); // Simulate a valid room

        Auth::shouldReceive('user')->andReturn($user);

        // Create a request with valid user and room data
        $request = new Request(['room' => 1, 'user_handling' => null]);

        $response = $this->middleware->handle($request, $this->nextClosure);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals('Next middleware executed', $response->getContent());
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testCheckRoomMembershipWithNonexistentRoom()
    {
        // Mock the user object with no room membership (room doesn't exist)
        $user = Mockery::mock('User');
        $user->shouldReceive('rooms')->andReturnSelf();
        $user->shouldReceive('wherePivot')->andReturnSelf();
        $user->shouldReceive('find')->andReturn(null);

        Auth::shouldReceive('user')->andReturn($user);

        // Create a request with a non-existent room
        $request = new Request(['room' => 2, 'user_handling' => null]);

        $response = $this->middleware->handle($request, $this->nextClosure);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals('"You are not a member of this room or it does not exist"', $response->getContent());
        $this->assertEquals(404, $response->getStatusCode());
    }


    public function testCheckRoomMembershipWithInvalidUserHandling()
    {
        $user = Mockery::mock('User');
        $user->shouldReceive('rooms')->andReturnSelf();
        $user->shouldReceive('wherePivot')->andReturnSelf();
        $user->shouldReceive('find')->andReturnUsing(function ($roomId) {
            $room = Mockery::mock('Room');
            $room->shouldReceive('wherePivot')->andReturnSelf();
            $room->shouldReceive('find')->andReturn($room);
            $room->shouldReceive('users')->andReturnSelf();
            $room->shouldReceive('where')->andReturnSelf();
            $room->shouldReceive('exists')->andReturn(false);
            return $room;
        });

        Auth::shouldReceive('user')->andReturn($user);

        $request = new Request(['room' => 1, 'user_handling' => 2]);

        $response = $this->middleware->handle($request, $this->nextClosure);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals('"User Handling has to be a member of the room"', $response->getContent());
        $this->assertEquals(403, $response->getStatusCode());
    }
}

<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\Users\DTO\UsersListDTO;
use App\Services\Users\UsersService;
use Database\Seeders\UsersSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;


class UsersServiceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([
            UsersSeeder::class,
        ]);
        $this->actingAs(
            Auth::loginUsingId(2)
        );
    }

    public function test_users_list(): void
    {
        $usersListDTO = new UsersListDTO([
            'perPage' => 20,
            'pageNumber' => 1,
        ]);

        $usersService = $this->app->make(UsersService::class);
        $result = $usersService->list($usersListDTO);
        $this->assertContainsOnlyInstancesOf(User::class, $result);
        $this->assertCount(20, $result);
    }
}

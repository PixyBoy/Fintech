<?php
namespace App\Modules\Auth\Infrastructure\Persistence\Eloquent\Repositories;

use App\Models\User;
use App\Modules\Auth\Domain\Repositories\UserRepository;

class EloquentUserRepository implements UserRepository
{
    public function findByPhone(string $phone): ?User
    {
        return User::query()->where('phone', $phone)->first();
    }

    public function findByEmail(string $email): ?User
    {
        return User::query()->where('email', $email)->first();
    }

    public function createWithPhone(string $phone): User
    {
        return User::create(['phone' => $phone]);
    }
}

<?php
namespace App\Modules\Auth\Domain\Repositories;

use App\Models\User;

interface UserRepository {
    public function findByPhone(string $phone): ?User;
    public function findByEmail(string $email): ?User;
    public function createWithPhone(string $phone): User;
}

<?php

namespace App\Modules\PayForMe\Domain\Repositories;

use App\Modules\PayForMe\Domain\Entities\PayForMeRequest;

interface PayForMeRepositoryInterface
{
    public function create(array $data): PayForMeRequest;

    public function findByUser(int $userId, int $perPage = 15);

    public function find(int $id): ?PayForMeRequest;

    public function updateStatus(int $id, string $status, ?string $note = null, ?array $attachment = null): void;
}

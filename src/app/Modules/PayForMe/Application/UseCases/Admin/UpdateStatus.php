<?php

namespace App\Modules\PayForMe\Application\UseCases\Admin;

use App\Modules\PayForMe\Domain\Repositories\PayForMeRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class UpdateStatus
{
    public function __construct(protected PayForMeRepositoryInterface $repo)
    {
    }

    public function execute(int $id, string $status, ?string $note = null, ?array $attachment = null): void
    {
        $validator = Validator::make([
            'status' => $status,
            'note' => $note,
        ], [
            'status' => 'required|in:paid,processing,done,refunded,failed',
            'note' => 'nullable|max:2000',
        ]);
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
        if ($note) {
            $note = now().' '.(Auth::user()->email ?? 'admin').": $note";
        }
        $this->repo->updateStatus($id, $status, $note, $attachment);
    }
}

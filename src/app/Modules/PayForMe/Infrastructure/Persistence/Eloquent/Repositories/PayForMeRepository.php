<?php

namespace App\Modules\PayForMe\Infrastructure\Persistence\Eloquent\Repositories;

use App\Modules\PayForMe\Domain\Entities\PayForMeRequest;
use App\Modules\PayForMe\Domain\Enums\PayForMeStatus;
use App\Modules\PayForMe\Domain\Repositories\PayForMeRepositoryInterface;
use App\Modules\PayForMe\Infrastructure\Persistence\Eloquent\Models\PayForMeRequestModel;

class PayForMeRepository implements PayForMeRepositoryInterface
{
    public function create(array $data): PayForMeRequest
    {
        $model = PayForMeRequestModel::create($data);
        return $this->toEntity($model);
    }

    public function findByUser(int $userId, int $perPage = 15)
    {
        return PayForMeRequestModel::where('user_id', $userId)->paginate($perPage);
    }

    public function find(int $id): ?PayForMeRequest
    {
        $model = PayForMeRequestModel::find($id);
        return $model ? $this->toEntity($model) : null;
    }

    public function updateStatus(int $id, string $status, ?string $note = null, ?array $attachment = null): void
    {
        $model = PayForMeRequestModel::findOrFail($id);
        if ($note) {
            $notes = ($model->notes ? $model->notes . "\n" : '') . $note;
            $model->notes = $notes;
        }
        if ($attachment) {
            $attachments = $model->attachments ?: [];
            $attachments[] = $attachment;
            $model->attachments = $attachments;
        }
        $model->status = $status;
        $model->save();
    }

    protected function toEntity(PayForMeRequestModel $model): PayForMeRequest
    {
        return new PayForMeRequest(
            $model->id,
            $model->user_id,
            $model->request_code,
            $model->target_url,
            $model->amount_usd,
            $model->notes,
            $model->attachments ?: [],
            $model->quote_snapshot ?: [],
            $model->order_id,
            PayForMeStatus::from($model->status)
        );
    }
}

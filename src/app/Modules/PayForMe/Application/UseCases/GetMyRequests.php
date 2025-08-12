<?php

namespace App\Modules\PayForMe\Application\UseCases;

use App\Modules\PayForMe\Application\DTOs\RequestView;
use App\Modules\PayForMe\Domain\Repositories\PayForMeRepositoryInterface;

class GetMyRequests
{
    public function __construct(protected PayForMeRepositoryInterface $repo)
    {
    }

    public function execute(int $userId, int $perPage = 15)
    {
        $paginator = $this->repo->findByUser($userId, $perPage);
        $paginator->getCollection()->transform(function ($model) {
            return new RequestView(
                $model->id,
                $model->request_code,
                $model->amount_usd,
                $model->quote_snapshot['total_irr'] ?? 0,
                $model->status
            );
        });
        return $paginator;
    }
}

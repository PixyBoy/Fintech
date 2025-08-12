<?php

namespace App\Modules\PayForMe\Application\UseCases;

use App\Modules\PayForMe\Application\DTOs\CreateRequestInput;
use App\Modules\PayForMe\Application\DTOs\RequestView;
use App\Modules\PayForMe\Application\Services\Quote\QuoteCalculator;
use App\Modules\PayForMe\Domain\Enums\PayForMeStatus;
use App\Modules\PayForMe\Domain\Repositories\PayForMeRepositoryInterface;
use App\Modules\PayForMe\Infrastructure\Storage\Attachments\AttachmentHelper;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CreateRequest
{
    public function __construct(
        protected PayForMeRepositoryInterface $repo,
        protected QuoteCalculator $calculator,
    ) {
    }

    public function execute(CreateRequestInput $input): RequestView
    {
        $this->validate($input);
        $attachments = [];
        foreach ($input->attachments as $file) {
            $attachments[] = AttachmentHelper::store($file);
        }

        $quote = $this->calculator->calculate('payforme', $input->amount_usd);

        $requestCode = 'PF-'.now()->format('Ymd').'-'.Str::upper(Str::random(4));

        $data = [
            'user_id' => $input->user_id,
            'request_code' => $requestCode,
            'target_url' => $this->sanitizeUrl($input->target_url),
            'amount_usd' => $input->amount_usd,
            'notes' => $input->notes,
            'attachments' => $attachments,
            'quote_snapshot' => [
                'amount_usd' => $quote->amount_usd,
                'fee_usd' => $quote->fee_usd,
                'subtotal_usd' => $quote->subtotal_usd,
                'rate_used' => $quote->rate_used,
                'total_irr' => $quote->total_irr,
            ],
            'status' => PayForMeStatus::Pending->value,
        ];

        $entity = $this->repo->create($data);

        return new RequestView(
            $entity->id,
            $entity->request_code,
            $entity->amount_usd,
            $quote->total_irr,
            $entity->status,
        );
    }

    protected function validate(CreateRequestInput $input): void
    {
        $validator = Validator::make([
            'target_url' => $input->target_url,
            'amount_usd' => $input->amount_usd,
            'notes' => $input->notes,
            'attachments' => $input->attachments,
        ], [
            'target_url' => 'required|url|max:1024',
            'amount_usd' => 'required|numeric|min:1|max:100000',
            'notes' => 'nullable|max:2000',
            'attachments.*' => 'file|mimes:jpg,png,webp,pdf|max:5120',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    protected function sanitizeUrl(string $url): string
    {
        $parts = parse_url($url);
        $scheme = $parts['scheme'] ?? 'http';
        $host = $parts['host'] ?? '';
        $path = $parts['path'] ?? '';
        $query = '';
        if (!empty($parts['query'])) {
            parse_str($parts['query'], $params);
            $params = array_filter($params, fn($v, $k) => !str_starts_with($k, 'utm_'), ARRAY_FILTER_USE_BOTH);
            if ($params) {
                $query = '?'.http_build_query($params);
            }
        }
        return $scheme.'://'.$host.$path.$query;
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Portfolios\Requests;

use App\Http\Common\Requests\ApiRequest;
use App\Http\Common\Requests\AuthContextTrait;

final class GetPortfolioAssetsByIdApiRequest extends ApiRequest
{
    use AuthContextTrait;

    public function rules(): array
    {
        return [
            'page' => 'integer|min:1',
            'per_page' => 'integer|min:1|max:50',
        ];
    }

    public function id(): int
    {
        return (int) $this->route('id');
    }

    public function page(): ?int
    {
        return (int) $this->get('page');
    }

    public function perPage(): ?int
    {
        return (int) $this->get('per_page');
    }
}

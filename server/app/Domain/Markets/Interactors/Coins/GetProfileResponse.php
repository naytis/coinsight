<?php

declare(strict_types=1);

namespace App\Domain\Markets\Interactors\Coins;

use App\Domain\Markets\Entities\Coin;
use App\Domain\Markets\Entities\Profile;
use Spatie\DataTransferObject\DataTransferObject;

final class GetProfileResponse extends DataTransferObject
{
    public Coin $coin;
    public Profile $profile;
}

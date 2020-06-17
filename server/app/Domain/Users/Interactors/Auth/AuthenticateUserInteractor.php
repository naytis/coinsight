<?php

declare(strict_types=1);

namespace App\Domain\Users\Interactors\Auth;

use App\Domain\Users\Entities\User as UserEntity;
use App\Domain\Users\Exceptions\WrongPassword;
use App\Domain\Users\Models\User as UserModel;
use Illuminate\Support\Facades\Hash;

final class AuthenticateUserInteractor
{
    public function execute(AuthenticateUserRequest $request): AuthenticateUserResponse
    {
        $user = UserModel::whereUsername($request->username)->firstOrFail();

        if (!Hash::check($request->password, $user->password)) {
            throw new WrongPassword();
        }

        return new AuthenticateUserResponse([
            'user' => UserEntity::fromModel($user)
        ]);
    }
}

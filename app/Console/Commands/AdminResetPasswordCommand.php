<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class AdminResetPasswordCommand extends Command
{
    protected $signature = 'admin:reset-password';

    protected $description = 'Reset an admin user password';

    public function handle(): int
    {
        $email = (string) $this->ask('Email');
        $user = User::query()->where('email', $email)->first();

        if (! $user instanceof User) {
            $this->error('No admin user was found for that email address.');

            return self::FAILURE;
        }

        $password = (string) $this->secret('Password');
        $passwordConfirmation = (string) $this->secret('Confirm password');

        $validator = Validator::make([
            'password' => $password,
            'password_confirmation' => $passwordConfirmation,
        ], [
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }

        $user->update([
            'password' => $password,
        ]);

        $this->info('Admin password reset.');

        return self::SUCCESS;
    }
}

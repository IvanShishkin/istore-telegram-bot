<?php

namespace App\Console\Commands;

use App\Domain\User\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class CreateAdminUserCommand extends Command
{
    protected $signature = 'admin:create-user';

    protected $description = 'Command description';

    public function handle(): void
    {
        $password = Str::random(8);

        $user = User::create([
            'name' => 'Admin',
            'last_name' => 'Admin',
            'email' => 'admin@admin.dev',
            'password' => \Hash::make($password),
            'active' => 1,
        ]);

        $this->info($password);
    }
}

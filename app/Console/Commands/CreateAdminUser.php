<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    protected $signature = 'admin:create';
    protected $description = 'Create an admin user';

    public function handle()
    {
        $user = User::create([
            'name' => 'Administrador',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
        ]);

        $this->info('Admin user created successfully!');
        $this->info('Email: admin@admin.com');
        $this->info('Password: password');
    }
} 
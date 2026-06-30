<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class UpdateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:setup {--email=test@example.com} {--password=admin123} {--name=Admin Wildani}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set up the admin user credentials';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $email = $this->option('email');
        $password = $this->option('password');
        $name = $this->option('name');

        $user = User::where('email', $email)->first();

        if ($user) {
            $user->update([
                'name' => $name,
                'password' => Hash::make($password),
            ]);
            $this->info("Admin user updated: {$user->email}");
        } else {
            User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
            ]);
            $this->info("Admin user created: {$email}");
        }

        $this->line("Email   : {$email}");
        $this->line("Password: {$password}");
    }
}

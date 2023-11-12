<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create {name} {email} {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new user';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
{
    $name = $this->argument('name');
    $email = $this->argument('email');
    $password = bcrypt($this->argument('password'));

    $user = new \App\Models\User();
    $user->name = $name;
    $user->email = $email;
    $user->password = $password;

    if ($user->save()) {
        $this->info("User {$name} created successfully.");
    } else {
        $this->error("Failed to create user {$name}.");
    }
}

}

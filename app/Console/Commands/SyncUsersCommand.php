<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SyncUsersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync users from JSON';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('started sych users');
        
        $response = Http::get('https://jsonplaceholder.typicode.com/users');

        if ($response->successful()) {
            $data = $response->json();
            foreach ($data as $user) {  
                User::updateOrCreate(
                    ['email' => $user['email']],  
                    [
                        'name' => $user['name'],    
                        'company' => $user['company']['name'] ?? null, 
                    ]
                );
            }
            $this->info('suscsess to sync users');
        }
        else {
            $this->error('error is sucseseful users');
        }
    }

}

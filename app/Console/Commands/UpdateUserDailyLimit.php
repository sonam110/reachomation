<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\EmailCollection;
class UpdateUserDailyLimit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:daily-limit';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $accountEmail = EmailCollection::get();
        foreach ($accountEmail as $key => $value) {
            $updateLimit = EmailCollection::find($value->id);
            $updateLimit->daily_limit = '1500';
            $updateLimit->save();
        }
    }
}

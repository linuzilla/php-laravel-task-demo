<?php

namespace App\Console\Commands;

use App\Services\TaskRunningService;
use App\Tasks\SleepingBeautyTask;
use Illuminate\Console\Command;

class RunTaskCommand extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'task:run {user} {taskId}';

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
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() {
        TaskRunningService::runViaCommandLine($this->argument('user'), $this->argument('taskId'));
        return Command::SUCCESS;
    }
}

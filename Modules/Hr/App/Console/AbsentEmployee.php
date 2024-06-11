<?php

namespace Modules\Hr\App\Console;


use Carbon\Carbon;
use Illuminate\Console\Command;
use Modules\Hr\App\Models\Attendance;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class AbsentEmployee extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'attendance:absent-employees';

    /**
     * The console command description.
     */
    protected $description = 'changes unsigned_in employees to absentees';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
           Attendance::where('day_date',Carbon::yesterday())->whereNull('sign_in')
           //could add more filters here if Employee is on vacation or something.
            ->chunk(100, function ($attendaces) {
                foreach ($attendaces as $attendance) {
                    $attendance->update([
                        'status'=>'absent'
                    ]);
                }
           });
          
        
    
    }

    /**
     * Get the console command arguments.
     */
    protected function getArguments(): array
    {
        return [
            ['example', InputArgument::REQUIRED, 'An example argument.'],
        ];
    }

    /**
     * Get the console command options.
     */
    protected function getOptions(): array
    {
        return [
            ['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
        ];
    }
}

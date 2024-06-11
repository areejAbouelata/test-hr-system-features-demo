<?php

namespace Modules\Hr\App\Console;

use Illuminate\Console\Command;
use Modules\Hr\App\Models\LeaveBalance;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class CalculateYearEndBalance extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'leave-balance:calculate-year-end';

    /**
     * The console command description.
     */
    protected $description = 'Calculate year-end balance for leave balances';

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
        LeaveBalance::
            chunk(100, function ($leaveBalances) {
                foreach ($leaveBalances as $balance) {
                   
                    if($balance->year_end_balance){
                        $yearEndBalance =$balance->year_end_balance;
                        $yearEndBalance += $balance->annual_entitlement - $balance->used_leaves_this_year;
                        $carry_forward = $balance->annual_entitlement  - $balance->used_leaves_this_year;
                       
                        $balance->update([
                            'year_end_balance' => $yearEndBalance,
                            'carry_forward' => $carry_forward
                        ]);
                    }else{
                        $yearEndBalance = $balance->annual_entitlement - $balance->used_leaves_this_year;
                        $carry_forward = $balance->annual_entitlement  - $balance->used_leaves_this_year;
                       
                        $balance->update([
                            'year_end_balance' => $yearEndBalance,
                            'carry_forward' => $carry_forward

                        ]);
                    }
                   
                }
            });

        $this->info('Year-end balance calculation completed.');
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

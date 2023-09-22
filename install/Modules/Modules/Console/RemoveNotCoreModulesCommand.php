<?php

namespace Modules\Modules\Console;

use Illuminate\Support\Carbon;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Modules\Modules\Repositories\ModuleRepository;
use Symfony\Component\Console\Input\InputArgument;
use Modules\Modules\Services\Generators\ModuleGenerator;

class RemoveNotCoreModulesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:remove-not-core';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove not core modules.';

    /**
     * @var ModuleRepository $modules
     */
    protected $modules;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->modules = new ModuleRepository;

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if(env('APP_DEMO')) {
            $modules = $this->modules
                ->where(
                    'created_at', 
                    '<=', 
                    \Carbon\Carbon::now()->subHours(6)->toDateTimeString()
                )
                ->where('is_core', 0)
                ->get();

            foreach($modules as $module) {
                (new ModuleGenerator($module->attributes))
                    ->delete();

                $module->delete();
            }
        }
    }
}

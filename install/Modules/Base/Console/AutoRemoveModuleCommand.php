<?php

namespace Modules\Base\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Input\InputOption;
use Modules\Modules\Repositories\ModuleRepository;
use Symfony\Component\Console\Input\InputArgument;
use Modules\Modules\Services\Generators\ModuleGenerator;

class AutoRemoveModuleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:auto-remove';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will remove module automatically created in demo mode.';

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
     * @return mixed
     */
    public function handle()
    {
        $modules = new ModuleRepository;

        if(env('APP_DEMO')) {

            $modules = $modules->with(['relations'])
                ->where('is_core', 0)
                ->get();

            foreach($modules as $module) {    
                (new ModuleGenerator($module->attributes))->delete();
                $module->delete();
            }
        }
    }
}

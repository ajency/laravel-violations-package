<?php

namespace Ajency\Violations\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\ServiceProvider;

class GenerateViolationEmailTemplates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aj_violation:generate_email_templates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generating email templates for each violation';

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
        // access the violation config
        $violationsConfig = json_decode(config('aj-vio-config.create_violation_rules'));

        foreach($violationsConfig as $violation) {
            // for each violation type copy the default template
            $this->publishes([
                __DIR__.'/views/default_email.blade.php' => resource_path('views/violations/'.$violation['violation_type'].'.blade.php')]);
        }

        $this->info("Violation email templates generated");
        return;
    }
}

<?php

namespace Mugen\LaravelCronManager\Commands;

use Illuminate\Console\Command;
use Mugen\LaravelCronManager\Server\Manager;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class CronManagerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'cron:manager';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cron manager.';

    /**
     * @var Manager
     */
    protected $manager;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        list($action, $param) = $this->getAction();

        $this->manager = $this->laravel->make('cron.manager');

        $this->{$action}($param);
    }

    public function start($daemon = false)
    {
        $info = 'Running cron manager';
        $info .= ($daemon ? ' as daemon' : '') . ' ...';
        $this->info($info);

        $this->loadTasks();

        $this->manager->start($daemon);
    }

    public function status()
    {
        $this->info('Checking cron manager running status ...');

        $this->manager->status();
    }

    public function stop($force = false)
    {
        $info = ($force ? 'Force s' : 'S') . 'topping cron manager ...';
        $this->info($info);

        $this->manager->stop($force);
    }

    public function restart()
    {
        $this->info('Restarting cron manager ...');

        $this->manager->restart();
    }

    public function log()
    {
        $this->info('Printing logs ...');

        $this->manager->log();
    }

    public function check()
    {
        $this->info('Cehcking your environment ...');

        $this->manager->check();
    }

    /**
     * Load task files.
     */
    public function loadTasks()
    {
        $taskFiles = $this->laravel['config']->get('cron.tasks');

        foreach ($taskFiles as $file)
            if (file_exists($file))
                include $file;
    }

    /**
     * Get action & param.
     * @return array
     */
    protected function getAction()
    {
        $action = $this->argument('action');

        if (!in_array($action, ['start', 'stop', 'restart', 'status', 'log', 'check'], true)) {
            $this->error("Unexpected argument \"{$this->action}\"");
            exit(1);
        }

        $param = null;

        if ($action == 'start') $param = $this->option('daemon');

        if ($action == 'stop') $param = $this->option('force');

        return [$action, $param];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['daemon', 'd', InputOption::VALUE_NONE, 'Run cron manager as daemon only for "start" action'],
            ['force', 'f', InputOption::VALUE_NONE, 'Force stop cron manager only for "stop" action'],
        ];
    }

    protected function getArguments()
    {
        return [
            ['action', InputArgument::REQUIRED, 'Run cron manager action: start|status|stop|restart|log|check'],
        ];
    }
}

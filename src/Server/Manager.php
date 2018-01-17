<?php

namespace Mugen\LaravelCronManager\Server;

use Illuminate\Contracts\Container\Container;
use SuperCronManager\CronManager;

/**
 * Class Manager
 * @package Mugen\LaravelCronManager\Server
 * @method status()
 * @method restart()
 * @method log()
 * @method chech()
 */
class Manager
{
    /**
     * @var Container
     */
    protected $app;

    /**
     * @var CronManager
     */
    protected $server;

    /**
     * Manager constructor.
     * @param Container $app
     */
    public function __construct(Container $app)
    {
        $this->app = $app;

        $this->initialize();
    }

    /**
     * Initialize
     */
    protected function initialize()
    {
        $this->createCronManger();
    }

    /**
     * Create cron manager.
     */
    protected function createCronManger()
    {
        $this->server = new CronManager();

        $this->server->workerNum = $this->app['config']->get('cron.worker_num');

        $this->server->output = $this->app['config']->get('cron.output');
    }

    /**
     * Start cron manager.
     *
     * @param bool $daemon
     */
    public function start($daemon = false)
    {
        global $argv;

        $argv[1] = $daemon ? '-d' : null;

        $this->server->run();
    }

    /**
     * Stop cron manager.
     *
     * @param $force
     */
    public function stop($force)
    {
        global $argv;

        $argv[1] = $force ? 'STOP' : 'stop';

        $this->server->run();
    }

    /**
     * @param $action
     * @param $arguments
     */
    public function __call($action, $arguments)
    {
        if (!in_array($action, ['status', 'restart', 'log', 'check']))
            return;

        global $argv;

        $argv[1] = $action;

        $this->server->run();
    }

    /**
     * Add task
     *
     * @param string $name
     * @param string $intervalTime
     * @param $callable
     */
    public function task(string $name, string $intervalTime, $callable)
    {
        $this->server->taskInterval($name, $intervalTime, $callable);
    }
}
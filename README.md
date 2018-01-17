# laravel-cron-manager

## 简介

基于 [cron-manager](https://gitee.com/jianglibin/cron-manager) 的 Laravel 5 包


## 安装

```bash
composer require mugen/laravel-cron-manager
```

Laravel 5.5 支持可自动发现包功能，用 `composer` 安装完成后可直接使用。

Laravel 版本低于 5.5 则需要手动复制服务提供者以及别名到 `config/app.php`：

```php
[
    ...
    'providers' => [
        ...
        /*
         * Package Service Providers...
         */         
         Mugen\LaravelCronManager\CronManagerServiceProvider::class,
        ...
    ],
    'aliases' => [
        ...
        'Cron' => Mugen\LaravelCronManager\Facades\Cron::class,
    ]
]
```

## 配置

请运行以下命令发布 `cron.php` 配置文件到 `config/` 目录：
```bash
php artisan vendor:publish --provider="Mugen\LaravelCronManager\CronManagerServiceProvider"
```

```php
return [
    /**
     * 设置 worker 数
     */
    'worker_num' => 1,

    /**
     * 设置输出重定向文件
     */
    'output'     => storage_path('logs/cron.txt'),

    /**
     * 设置需要加载的任务文件，支持多文件加载
     */
    'tasks'      => [
        base_path('routes/cron.php'),//请以绝对路径配置该项，不一定放在 routes 文件夹下，可以放在任意位置
    ],
];
```

`routes/cron.php` 文件示例，用于配置你想要执行的任务

```php
<?php
/**
 * 使用 Cron 别名配置任务
 */
Cron::task('a', 's@1', function () {
    echo "1\n";
});

/**
 * 也可以使用 app('cron.manager') 配置任务 
 */
app('cron.manager')->task('b', 's@1', function () {
    echo "2\n";
});

```

配置完成后就是以使用以下命令以启动服务：

```bash
php artisan cron:manager start
```


## 命令

启动（以守护进程启动）：
```bash
php artisan cron:manager start [--daemon|-d]
```

运行状态：
```bash
php artisan cron:manager status
```

停止（强制停止）：
```bash
php artisan cron:manager stop [--force|-f]
```

重启：
```bash
php artisan cron:manager restart
```

查看日志：
```bash
php artisan cron:manager log
```

检查系统支持情况：
```bash
php artisan cron:manager check
```

<?php

declare(strict_types=1);

namespace App\Core\Console;

use App\Console\Commands\OrdersPollCommand;
use App\Console\Commands\StatsRebuildCommand;
use App\Console\Commands\SyncSuppliersCommand;
use App\Core\Config\ConfigRepository;
use App\Core\Container;
use App\Core\Logger\Logger;

class Kernel
{
    /** @var array<string, class-string<CommandInterface>> */
    private array $commands = [];

    public function __construct(
        private readonly Container $container,
        private readonly ConfigRepository $config,
        private readonly Logger $logger
    ) {
        $this->registerDefaultCommands();
    }

    public function handle(array $argv): void
    {
        $commandName = $argv[1] ?? 'list';

        if ($commandName === 'list') {
            $this->output("Available commands:\n" . implode("\n", array_keys($this->commands)) . "\n");
            return;
        }

        if (! isset($this->commands[$commandName])) {
            $this->output("Command {$commandName} not found\n");
            return;
        }

        $class = $this->commands[$commandName];
        /** @var CommandInterface $command */
        $command = $this->container->make($class);
        $command->handle($argv);
    }

    private function registerDefaultCommands(): void
    {
        $this->commands['sync:suppliers'] = SyncSuppliersCommand::class;
        $this->commands['orders:poll'] = OrdersPollCommand::class;
        $this->commands['stats:rebuild'] = StatsRebuildCommand::class;
    }

    private function output(string $message): void
    {
        fwrite(STDOUT, $message);
    }
}

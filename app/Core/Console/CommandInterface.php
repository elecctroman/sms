<?php

declare(strict_types=1);

namespace App\Core\Console;

interface CommandInterface
{
    /**
     * @param array<int, string> $argv
     */
    public function handle(array $argv): void;
}

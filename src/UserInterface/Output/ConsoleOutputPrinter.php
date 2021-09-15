<?php

declare(strict_types=1);

namespace App\UserInterface\Output;

class ConsoleOutputPrinter implements OutputInterface
{
    public function print(string $print): void
    {
        print($print);
    }
}
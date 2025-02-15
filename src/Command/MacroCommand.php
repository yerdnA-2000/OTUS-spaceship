<?php

namespace App\Command;

use App\Command\Queue\CommandQueueInterface;
use App\Exception\CommandException;
use Exception;

class MacroCommand implements CommandInterface
{
    private CommandQueueInterface $commandQueue;

    public function __construct(CommandQueueInterface $commandQueue)
    {
        $this->commandQueue = $commandQueue;
    }

    /**
     * @throws CommandException
     * @throws Exception
     */
    public function execute(): void
    {
        while ($this->commandQueue->count() > 0) {
            $command = $this->commandQueue->remove();

            if (!$command instanceof CommandInterface) {
                throw new Exception('Command does not implement CommandInterface.');
            }

            try {
                $command->execute();
            } catch (Exception $e) {
                throw new CommandException('Macro command execution failed: ' . $e->getMessage(), 0, $e);
            }
        }
    }
}
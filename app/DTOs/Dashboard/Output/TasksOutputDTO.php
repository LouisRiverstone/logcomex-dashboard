<?php

namespace App\DTOs\Dashboard\Output;

class TasksOutputDTO
{
    /**
     * Create a new TasksOutputDTO
     *
     * @param array<string,mixed> $tasks
     */
    public function __construct(
        public readonly array $tasks
    ) {
        // Construtor com propriedades definidas
    }

    /**
     * Create a new TasksOutputDTO from an array
     *
     * @param array<string,mixed> $tasks
     * @return self
     */
    public static function fromArray(array $tasks): self
    {
        return new self($tasks);
    }

    /**
     * Convert the TasksOutputDTO to an array
     *
     * @return array<string,mixed>
     */
    public function toArray(): array
    {
        return $this->tasks;
    }
}

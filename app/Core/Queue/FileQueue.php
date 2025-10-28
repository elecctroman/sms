<?php

declare(strict_types=1);

namespace App\Core\Queue;

class FileQueue implements QueueInterface
{
    public function __construct(private readonly string $path)
    {
        if (! is_dir($this->path)) {
            mkdir($this->path, 0777, true);
        }
    }

    public function push(string $job, array $data = []): void
    {
        $payload = [
            'job' => $job,
            'data' => $data,
            'created_at' => time(),
        ];

        $file = $this->path . '/' . microtime(true) . '.json';
        file_put_contents($file, json_encode($payload, JSON_THROW_ON_ERROR));
    }

    public function pop(): ?array
    {
        $files = glob($this->path . '/*.json');
        if ($files === false || count($files) === 0) {
            return null;
        }

        sort($files);
        $file = $files[0];
        $payload = json_decode((string) file_get_contents($file), true, flags: JSON_THROW_ON_ERROR);
        unlink($file);

        return $payload;
    }
}

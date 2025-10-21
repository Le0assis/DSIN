<?php

declare(strict_types=1);

namespace Src\Infra;

use Src\Domain\Entities\PrimordialDuck;
use Src\Domain\Contracts\IPrimordialDuckRepository;

final class FileDuckRepository implements IPrimordialDuckRepository
{
    public function __construct(private string $filePath)
    {
        $dir = dirname($this->filePath);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        if (!file_exists($this->filePath)) {
            touch($this->filePath);
        }
    }

    public function save(PrimordialDuck $duck): void
    {
        $data = $duck->to_array();
        $json_line = json_encode($data, JSON_UNESCAPED_UNICODE) . "\n";
        file_put_contents($this->filePath, $json_line, FILE_APPEND);
    }

    public function find_all(): array
    {
        $ducks = [];

        foreach (file($this->filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
            $decoded = json_decode($line, true);
            if (is_array($decoded)) {
                $ducks[] = $decoded;
            }
        }

        return $ducks;
    }

    public function set_id(): int
    {

        $ducks = $this->find_all();

        if (empty($ducks)) {
            return 1;
        }

        $last = end($ducks);
        return ($last['id'] ?? 0) + 1;
    }

}

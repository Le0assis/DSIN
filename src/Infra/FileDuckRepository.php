<?

declare(strict_types=1);

namespace Scr\Infra;

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
        file_put_contents($this->filePath, json_encode(
            $data,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        ));
    }

    public function getAll(): array
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

    public function getId(): int
    {

        $ducks = $this->getAll();

        if (empty($ducks)) {
            return 1;
        }

        $last = end($ducks);
        return ($last['id'] ?? 0) + 1;
    }

}

<?

declare(strict_types=1);

namespace Scr\Infra;

use Src\Domain\Entities\PrimordialDuck;
use Src\Domain\IPrimordialDuckRepository;

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
        file_put_contents($this->filePath, json_encode(
            $duck, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}

<?

declare(stricts_types=1);

namespace Src\Domain\Contracts;
use Src\Domain\Entities\PrimordialDuck;
interface IPrimordialDuckRepository
{
    public function save(PrimordialDuck $duck): void;
}
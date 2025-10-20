<?

declare(strict_types=1);

namespace Src\Domain\Entities;
final class Conversor
{
    public array $errors = [];
    
    public function feet_to_cm ($feet)
    {
        if ($feet <= 0) {
            $this->errors[] = "Medida pés deve ser maior que 0";
            return null;
        }

        $cm = $feet * 30.48;

        return $cm;
    }

    public function pounds_to_grams ($pounds)
    {
        if ($pounds <= 0) {
           $this->errors[] = "Medida Libra deve ser maior que 0";
           return null;
        }

        $grams = $pounds * 453.59243;

        return $grams;
    }

    public function catch_errors (){
        return $this->errors;
    }

}

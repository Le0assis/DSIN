<?php

declare(strict_types=1);

namespace Src\Application\UseCases;

use Exception;
use Src\Application\Commands\RegisterDuck;
use Src\Infra\FileDuckRepository;

final class PrimordialDuckService
{
    public RegisterDuck $registrator;
    public FileDuckRepository $repository;

    public function __construct(string $file)
    {
        $this->repository = new FileDuckRepository($file);
        $this->registrator = new RegisterDuck();
    }

    public function register(
        array $loc_data,
        array $duck_data,
        array $sp_data
    ) {
        try{

        $this->registrator->create_duck($duck_data);
        
        echo "<p> passo aqui </p>";
        $this->registrator->create_loc($loc_data);

        if (!empty($sp_data)) {
            $this->registrator->create_super_power($sp_data);
        }
        $id = $this->repository->set_id();
        $this->registrator->duck->set_id($id);
        $this->repository->save($this->registrator->duck);

        return [
            'success' => true,
            'message' => 'Pato primordial criado com sucesso',
            'data' => $this->registrator->duck
        ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erro ao criarPato primordial',
            ];
        }

       

    }
}
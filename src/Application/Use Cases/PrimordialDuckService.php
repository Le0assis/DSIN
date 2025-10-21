<?php

declare(strict_types=1);
use Src\Application\RegisterDuck;
use Src\Domain\Contracts\IPrimordialDuckRepository;

final class PrimordialDuckService
{
    public function __construct (
        private IPrimordialDuckRepository $rp
    ){
        $this->repository = new RegisterDuck();
    }

    public function register(
        array $loc_data,
        array $duck_data,
        array $sp_data
    )
    {
        try{
            $this->repository->create_loc(array $loc_data);

            $this->respository->create_duck(array $duck_dabstract);

            if (!empty($sp_data)) {
                $this->repository->create_super_power($sp_data);
            }

            return $this->repository->duck;

        } catch (Exception $ex) {

        }



    }
}
<?php

namespace App\Services\Contracts;

interface AuthServiceInterface
{

    public function register(array $data): array;

    public function login(array $data): array;
    
}

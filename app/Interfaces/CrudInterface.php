<?php

namespace App\Interfaces;

interface CrudInterface
{
    public function create(): void;

    public function store(array $request): void;

    public function show(int $id): void;

    public function update(int $id, array $request): void;

    public function delete(int $id, array $request): void;
}
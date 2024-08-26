<?php

namespace App\Interfaces;

interface BlogInfoInterface
{
    public function index(): void;

    public function store(array $request): void;

    public function update(int $id, array $request): void;

    public function delete(int $id, array $request): void;
}
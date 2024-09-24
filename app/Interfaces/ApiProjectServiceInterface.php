<?php

namespace App\Interfaces;

interface ApiProjectServiceInterface
{
    /**
     * @return array
     */
    public function index(): array;

    /**
     * @param int $id
     *
     * @return array
     */
    public function get(int $id): array;

    /**
     * @param array $data
     *
     * @return array
     */
    public function create(array $data): array;

    /**
     * @param int $id
     * @param array $data
     *
     * @return array
     */
    public function update(int $id, array $data): array;

    /**
     * @param int $id
     *
     * @return array
     */
    public function delete(int $id): array;
}

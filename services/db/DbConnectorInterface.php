<?php

namespace App\Services\db;

interface DbConnectorInterface
{
    /**
     * Set current table
     *
     * @param string $tableName
     * @return DbConnectorInterface
     */
    public function table(string $tableName): static;

    /**
     * Select fields from table
     *
     * @param string|array $fields
     * @return $this
     */
    public function select(string|array $fields = '*'): static;

    /**
     * Get result from select
     *
     * @return array
     */
    public function get(): array;

    /**
     * Get first item from result (from select)
     *
     * @return array|null
     */
    public function first(): ?array;

    /**
     * Insert new data to table
     *
     * @param array $data
     * @return void
     */
    public function insert(array $data): void;

    /**
     * @param int $id
     * @param array $data
     * @return void
     */
    public function update(int $id, array $data): void;

    /**
     * @param int $id
     * @return void
     */
    public function delete(int $id): void;
}
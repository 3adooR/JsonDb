<?php

namespace App\Services\db;

use App\Services\JsonParser;
use App\Services\JsonWriter;
use Exception;

class JsonDb implements DbConnectorInterface
{
    /** @var string */
    private string $tableName;

    /** @var array */
    private array $tableContents;

    /** @var JsonParser */
    private JsonParser $jsonParser;

    /** @var JsonWriter */
    private JsonWriter $jsonWriter;

    public function __construct()
    {
        $this->jsonParser = new JsonParser();
        $this->jsonWriter = new JsonWriter();
    }

    /**
     * @inheritDoc
     */
    public function table(string $tableName): static
    {
        $this->setTableName($tableName);

        return $this;
    }

    /**
     * @return void
     * @throws Exception
     */
    public function loadTable(): void
    {
        $this->setTableContents(
            $this->jsonParser::parse($this->getJsonFileName())
        );
    }

    /**
     * @inheritDoc
     *
     * @throws Exception
     */
    public function select(string|array $fields = '*'): static
    {
        $this->loadTable();

        if (!is_array($fields)) {
            $fields = explode(',', $fields);
        }

        if ($fields === '*' || empty($fields)) {
            return $this;
        }

        $i = 0;
        $data = [];
        foreach ($this->getTableContents() as $row) {
            foreach ($fields as $field) {
                if (isset($row[$field])) {
                    $data[$i][$field] = $row[$field];
                }
            }
            $i++;
        }
        $this->setTableContents($data);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function get(): array
    {
        return $this->tableContents;
    }

    /**
     * @inheritDoc
     */
    public function first(): ?array
    {
        return $this->tableContents[0] ?? null;
    }

    /**
     * @inheritDoc
     *
     * @throws Exception
     */
    public function insert(array $data): void
    {
        $this->loadTable();
        $tableContents = $this->getTableContents();
        $indexes = $this->getIndexes();

        // check or set id of new row
        if (!empty($indexes)) {
            if (!empty($data['id'])) {
                $id = (int) $data['id'];
                if (in_array($id, $indexes)) {
                    throw new Exception('Duplicate ID');
                }
            } else {
                $id = max($indexes) + 1;
            }
            $data['id'] = $id;
        }

        // update table
        $tableContents[] = $data;
        $this->commit($tableContents);
    }

    /**
     * @inheritDoc
     *
     * @throws Exception
     */
    public function update(int $id, array $data): void
    {
        $this->loadTable();
        $tableContents = $this->getTableContents();

        foreach ($tableContents as $rowIndex => $row) {
            $rowId = $this->getRowIndex($row);
            if ($rowId === $id) {
                $tableContents[$rowIndex] = array_merge($row, $data);
                break;
            }
        }

        $this->commit($tableContents);
    }

    /**
     * @inheritDoc
     *
     * @throws Exception
     */
    public function delete(int $id): void
    {
        $this->loadTable();
        $tableContents = $this->getTableContents();
        $deleteRowIndex = null;

        foreach ($tableContents as $rowIndex => $row) {
            $rowId = $this->getRowIndex($row);
            if ($rowId === $id) {
                $deleteRowIndex = $rowIndex;
            }
        }
        if (empty($deleteRowIndex)) {
            throw new Exception('Can not find row with this index');
        }

        array_splice($tableContents, $deleteRowIndex, 1);
        $this->commit($tableContents);
    }

    /**
     * @param array $data
     * @return void
     */
    private function setTableContents(array $data): void
    {
        $this->tableContents = $data;
    }

    /**
     * @return array
     */
    private function getTableContents(): array
    {
        return $this->tableContents;
    }

    /**
     * Set table name
     *
     * @param string $tableName
     * @return void
     */
    private function setTableName(string $tableName): void
    {
        $this->tableName = $tableName;
    }

    /**
     * Get table name
     *
     * @return string
     */
    private function getTableName(): string
    {
        return $this->tableName;
    }

    /**
     * Get json file name
     *
     * @return string
     */
    private function getJsonFileName(): string
    {
        return sprintf('../db/%s.json', $this->getTableName());
    }

    /**
     * Return array of table indexes
     *
     * @return array
     */
    private function getIndexes(): array
    {
        $tableContents = $this->getTableContents();
        $indexes = [];
        if (isset($tableContents[0]['id'])) {
            foreach ($tableContents as $row) {
                $indexes[] = $this->getRowIndex($row);
            }
        }

        return $indexes;
    }

    /**
     * Get id of current row
     *
     * @param array $row
     * @return int|null
     */
    private function getRowIndex(array $row): ?int
    {
        return (int) $row['id'] ?? null;
    }

    /**
     * Commit changes
     *
     * @param array $tableContents
     * @return void
     * @throws Exception
     */
    private function commit(array $tableContents): void
    {
        try {
            $this->jsonWriter->write($this->getJsonFileName(), $tableContents);
            $this->loadTable();
        } catch (Exception $exception) {
            throw new Exception($exception);
        }
    }
}
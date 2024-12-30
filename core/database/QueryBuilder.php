<?php
namespace App\Core\Database;

use App\Core\Logger;
use mysqli;


class QueryBuilder
{
    private mysqli $mysqli;
    private string $sql = "SELECT ";
    public function __construct()
    {
        $this->mysqli ??= Connection::make($GLOBALS['config']['database']);
    }
    public function selectDistinct($columns)
    {
        $this->sql .= " DISTINCT ";
        return $this->select($columns);
    }
    public function select($columns)
    {
        if ($columns == "*") {
            $this->sql .= " * ";
            return $this;
        }
        $sql = " ";
        foreach ($columns as $key => $value) {
            if (!is_numeric($key)) {
                $sql .= "$key AS `$value`, ";
            } else {
                $sql .= "$value , ";
            }
        }
        $sql = rtrim($sql, ", ");
        $this->sql .= $sql;
        return $this;
    }
    public function from($table)
    {
        $this->sql .= " FROM `$table`";
        return $this;
    }
    public function join($table, $condition, $type = "INNER")
    {
        $this->sql .= " $type JOIN `$table` ON $condition";
        return $this;
    }

    public function where($column, $operator, $value)
    {
        $includeWhere = strpos($this->sql, "WHERE") === false;
        $prefix = $includeWhere ? " WHERE " : " ";
        if ($operator === "IN") {
            if (is_array($value)) {
                $value = "(" . implode(",", $value) . ")";
            } else {
                $value = "($value)";
            }
            $this->sql .= "$prefix $column $operator $value";
            return $this;
        }
        $parsedValue = self::parseValue($value);
        $this->sql .= "$prefix $column $operator $parsedValue";
        return $this;
    }
    private static function parseValue($value)
    {
        if (is_null($value) || $value === "NULL") {
            return "NULL";
        }
        if (is_string($value)) {
            return "'$value'";
        }
        return $value;
    }
    public function andWhere($column, $operator, $value)
    {
        $this->and();
        return $this->where($column, $operator, $value);
    }
    public function and()
    {
        $this->sql .= " AND ";
        return $this;
    }
    public function or()
    {
        $this->sql .= " OR ";
        return $this;
    }
    public function startGroup()
    {
        $this->sql .= " ( ";
        return $this;
    }
    public function endGroup()
    {
        $this->sql .= " ) ";
        return $this;
    }

    public function orWhere($column, $operator, $value)
    {
        $this->or();
        return $this->where($column, $operator, $value);

    }
    public function orderBy($column, $direction = "ASC")
    {
        $this->sql .= " ORDER BY $column $direction";
        return $this;
    }

    public function limit($limit, $offset = 0)
    {
        $this->sql .= " LIMIT $offset, $limit";
        return $this;
    }

    public function __toString()
    {
        return $this->sql;
    }
    public function get()
    {
        $result = $this->mysqli->query($this->sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    public function count()
    {
        $result = $this->mysqli->query($this->sql);
        return $result->num_rows;
    }


    public function first()
    {
        $result = $this->mysqli->query($this->sql);
        return $result->fetch_assoc();
    }
    public function parseOne($data, $options)
    {
        $root = $options["root"];
        $embeds = $options["embed"];

        $groups = [];
        foreach ($data as $column => $value) {
            $keyParts = explode(".", $column);
            if (count($keyParts) === 2) {
                $key = $keyParts[0];
                $subKey = $keyParts[1];
                if (!isset($groups[$key])) {
                    $groups[$key] = [];
                }
                $groups[$key][$subKey] = $value;
            }
        }

        $result = $groups[$root];

        foreach ($embeds as $embedKey) {
            $result[$embedKey] = $groups[$embedKey];
        }

        return $result;
    }
    public function parseMany($data, $options)
    {
        $results = [];
        $parsedData = [];
        foreach ($data as $row) {
            $parsedData[] = $this->parseOne($row, $options);
        }

        return $parsedData;
    }

}
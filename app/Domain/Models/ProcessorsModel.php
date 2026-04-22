<?php

namespace App\Domain\Models;

class ProcessorsModel extends BaseModel
{
    public function getAllProcessors(array $filters)
    {
        $filter_values = [];
        $sql = "SELECT i.*, m.name FROM image_processors i LEFT JOIN manufacturers m ON m.manufacturer_id = i.manufacturer_id WHERE 1 = 1 ";

        if (isset($filters["manufacturer"]) && $filters["manufacturer"] !== "") {
            $sql .= " AND m.name LIKE CONCAT('%', :manufacturer, '%')";
            $filter_values["manufacturer"] = $filters["manufacturer"];
        }
        if (isset($filters["generation_min"]) && $filters["generation_min"] > 0) {
            $sql .= " AND i.generation >= :generation_min";
            $filter_values["generation_min"] = $filters["generation_min"];
        }

        if (isset($filters["generation_max"]) && $filters["generation_max"] > 0) {
            $sql .= " AND i.generation <= :generation_max";
            $filter_values["generation_max"] = $filters["generation_max"];
        }

        if (isset($filters["name_desc"]) && ($filters["name_desc"])==true) {
            $sql .= " ORDER BY m.name DESC";
        }

        return $this->paginate($sql, $filter_values);
    }
}

<?php

namespace App\Domain\Models;

class ManufacturerModel extends BaseModel
{
    // public function getManufacturers() : array
    // {
    //     $sql = "SELECT * FROM manufacturers";
    //     return $this->fetchAll($sql);
    // }

    public function getManufacturer(int $id) : mixed
    {
        $sql = "SELECT * FROM manufacturers WHERE manufacturer_id = :id";
        return $this->fetchSingle($sql, ["id"=>$id]);
    }

    public function getManufacturerLenses(int $manufacturer_id) : mixed
    {
        $sql = "SELECT * FROM lenses WHERE manufacturer_id = :id";
        return $this->fetchSingle($sql, ["id"=>$manufacturer_id]);
    }

    public function getManufacturers(array $filters) : mixed
    {
        $filter_values = [];
        $sql = "SELECT m.* FROM manufacturers m WHERE 1 = 1 ";

        if (isset($filters["country"]) && !empty($filters["country"]))
        {
            $sql .= " AND country LIKE CONCAT('%', :country, '%')";
            $filter_values["country"] = $filters["country"];
        }

        if (isset($filters["founded_year"]) && !empty($filters["founded_year"]))
        {
            $sql .= " AND founded_year >= :founded_year";
            $filter_values["founded_year"] = $filters["founded_year"];
        }
        if (isset($filters["camera_count"]) && !empty($filters["camera_count"]))
        {
            $sql .= " LEFT JOIN camera_bodies cb ON m.manufacturer_id = cb.manufacturer_id
            GROUP BY m.manufacturer_id
            HAVING COUNT(cb.body_id) >= :camera_count";
            $filter_values["camera_count"] = $filters["camera_count"];
        }
        // $manufacturers = $this->fetchAll($sql, $filter_values);
        $manufacturers = $this->paginate($sql, $filter_values);
        return $manufacturers;
    }
}

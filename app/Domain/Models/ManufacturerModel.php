<?php

namespace App\Domain\Models;

class ManufacturerModel extends BaseModel
{
    // public function getManufacturers() : array
    // {
    //     $sql = "SELECT * FROM manufacturers";
    //     return $this->fetchAll($sql);
    // }

    public function getManufacturer(int $id): mixed
    {
        $sql = "SELECT * FROM manufacturers WHERE manufacturer_id = :id";
        return $this->fetchSingle($sql, ["id" => $id]);
    }

    public function getManufacturerLenses(int $manufacturer_id, array $filters): mixed
    {
        $filter_values = [];
        $filter_values["manufacturer_id"] = $manufacturer_id;
        $sql = "SELECT l.* FROM lenses l WHERE manufacturer_id = :manufacturer_id ";

        if (isset($filters["lens_type"]) && !empty($filters["lens_type"])) {
            $sql .= " AND lens_type LIKE CONCAT('%', :lens_type, '%')";
            $filter_values["lens_type"] = $filters["lens_type"];
        }

        if (isset($filters["aperture_max"]) && !empty($filters["aperture_max"])) {
            $sql .= " AND aperture_max = :aperture_max";
            $filter_values["aperture_max"] = $filters["aperture_max"];
        }

        if (isset($filters["aperture_min"]) && !empty($filters["aperture_min"])) {
            $sql .= " AND aperture_min = :aperture_min";
            $filter_values["aperture_min"] = $filters["aperture_min"];
        }

        return $this->paginate($sql, $filter_values);
    }

    public function getManufacturers(array $filters): mixed
    {
        $filter_values = [];
        $sql = "SELECT m.* FROM manufacturers m  LEFT JOIN camera_bodies cb ON m.manufacturer_id = cb.manufacturer_id  WHERE 1 = 1 ";

        if (isset($filters["country"]) && !empty($filters["country"])) {
            $sql .= " AND country LIKE CONCAT('%', :country, '%')";
            $filter_values["country"] = $filters["country"];
        }

        if (isset($filters["founded_year_after"]) && !empty($filters["founded_year_after"])) {
            $sql .= " AND founded_year >= :founded_year_after";
            $filter_values["founded_year_after"] = $filters["founded_year_after"];
        }

        if (isset($filters["camera_count_min"]) && !empty($filters["camera_count_min"])) {
            $sql .= "
            GROUP BY m.manufacturer_id
            HAVING COUNT(cb.body_id) >= :camera_count_min";
            $filter_values["camera_count_min"] = $filters["camera_count_min"];
        }
        if (isset($filters["name_des"]) && ($filters["name_des"])==true) {
            $sql .= " ORDER BY name DESC;";
        }
        // $manufacturers = $this->fetchAll($sql, $filter_values);
        $manufacturers = $this->paginate($sql, $filter_values);
        return $manufacturers;
    }

    public function getManufacturersNameDesc($filters): mixed
    {
        $sql = "SELECT m.* FROM manufacturers name DESC";
        $manufacturers = $this->paginate($sql, $filters);
        return $manufacturers;
    }
}
/*
SELECT m.* FROM manufacturers m
LEFT JOIN camera_bodies cb ON m.manufacturer_id = cb.manufacturer_id
            GROUP BY m.manufacturer_id
            HAVING COUNT(cb.body_id) >= 10
*/

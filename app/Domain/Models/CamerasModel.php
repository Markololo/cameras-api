<?php

namespace App\Domain\Models;

class CamerasModel extends BaseModel
{
    public function getCameraBatteries($id, array $filters)
    {
        $sql = "SELECT b.* FROM batteries b LEFT JOIN body_batteries bb ON b.battery_id = bb.battery_id WHERE bb.body_id = :id";
        $filter_values["id"] = $id;

        if (isset($filters["capacity_min"]) && $filters["capacity_min"] !== "") {
            $sql .= " AND capacity_mah >= :capacity_min ";
            $filter_values["capacity_min"] = $filters["capacity_min"];
        }
        if (isset($filters["voltage_min"]) && !empty($filters["voltage_min"])) {
            $sql .= " AND voltage >= :voltage_min";
            $filter_values["voltage_min"] = $filters["voltage_min"];
        }

        if (isset($filters["weight_max"]) && !empty($filters["weight_max"])) {
            $sql .= " AND weight_g <= :weight_max";
            $filter_values["weight_max"] = $filters["weight_max"];
        }

        return $this->paginate($sql, $filter_values);
    }
    public function getAllCameras(array $filters): array
    {
        $filter_values = [];
        $sql = "SELECT cb.*, b.has_ibis, body_type FROM camera_bodies cb LEFT JOIN body_specs b ON b.body_id = cb.body_id WHERE 1 = 1 ";

        if (isset($filters["has_ibis"]) && $filters["has_ibis"] !== "") {
            $sql .= " AND b.has_ibis = :has_ibis ";
            $filter_values["has_ibis"] = $filters["has_ibis"];
        }
        if (isset($filters["body_type"]) && !empty($filters["body_type"])) {
            $sql .= " AND body_type LIKE CONCAT('%', :body_type, '%')";
            $filter_values["body_type"] = $filters["body_type"];
        }

        if (isset($filters["release_date_after"]) && !empty($filters["release_date_after"])) {
            $sql .= " AND release_date > :release_date_after";
            $filter_values["release_date_after"] = $filters["release_date_after"];
        }

        return $this->paginate($sql, $filter_values);
    }

    public function getCamera(int $id): mixed
    {
        $sql = "SELECT * FROM camera_bodies WHERE body_id = :id";
        return $this->fetchSingle($sql, ["id" => $id]);
    }
}

<?php

namespace App\Domain\Models;

class MountsModel extends BaseModel
{
    /**
     //* For /mounts/{mount_id}/cameras
     * @param mixed $filters
     * @param mixed $id
     * @return array
     */
    public function getMountsCameras($filters, $mount_id)
    {
        $filter_values = [];
        $sql = "SELECT cb.* FROM camera_bodies cb WHERE cb.mount_id = :mount_id ";

        $filter_values["mount_id"] = $mount_id;

        if (isset($filters["price_min"]) && $filters["price_min"] > 0) {
            $sql .= " AND cb.price >= :price_min";
            $filter_values["price_min"] = $filters["price_min"];
        }

        if (isset($filters["price_max"]) && $filters["price_max"] > 0) {
            $sql .= " AND cb.price <= :price_max";
            $filter_values["price_max"] = $filters["price_max"];
        }
       if (isset($filters["body_type"]) && $filters["body_type"] !== "") {
            $sql .= " AND cb.body_type = :body_type";
            $filter_values["body_type"] = $filters["body_type"];
        }

        return $this->paginate($sql, $filter_values);
    }

    /**
     * For /mounts/{mount_id}/lenses
     * @param mixed $filters
     * @param mixed $mount_id
     * @return array
     */
    public function getMountsLenses($filters, $mount_id)
    {
        $filter_values = [];
        $filter_values["mount_id"] = $mount_id;
        $sql = "SELECT l.*, ml.lens_type, m.name AS manufacturer_name FROM lenses l LEFT JOIN lenses ml ON l.lens_id = ml.lens_id
        LEFT JOIN manufacturers m ON l.manufacturer_id = m.manufacturer_id
        WHERE ml.mount_id = :mount_id ";

        if (isset($filters["lens_type"]) && $filters["lens_type"] !== "") {
            $sql .= " AND ml.lens_type LIKE CONCAT('%', :lens_type, '%')";
            $filter_values["lens_type"] = $filters["lens_type"];
        }

        if (isset($filters["aperture_max"]) && $filters["aperture_max"] > 0) {
            $sql .= " AND l.aperture_max >= :aperture_max";
            $filter_values["aperture_max"] = $filters["aperture_max"];
        }

        if (isset($filters["manufacturer"]) && $filters["manufacturer"] !== "") {
            $sql .= " AND m.name LIKE CONCAT('%', :manufacturer, '%')";
            $filter_values["manufacturer"] = $filters["manufacturer"];
        }

        return $this->paginate($sql, $filter_values);
    }
}

/*
SELECT l.*, ml.lens_type, m.name FROM lenses l LEFT JOIN lenses ml ON l.lens_id = ml.lens_id WHERE ml.mount_id = 1
LEFT JOIN manufacturers m ON l.manufacturer_id = m.manufacturer_id AND m.name LIKE CONCAT('%', :manufacturer, '%')
*/

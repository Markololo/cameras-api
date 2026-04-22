<?php

namespace App\Domain\Models;

class LensesModel extends BaseModel
{
    /**
     * has_stabilization boolean Filter by image stabilization: true or false
     * min_focus_distance_max decimal Minimum focus distance (upper bound)
     * filter_size integer Filter by specific filter thread size
     * @param mixed $filters
     * @return array
     */
    public function getLensSpecs($filters, $id)
    {
        $filter_values = [];
        $sql = "SELECT s.* FROM lens_optical_specs s LEFT JOIN lenses l ON l.lens_id = s.lens_id
        WHERE s.lens_id = :id ";
        $filter_values["id"] = $id;

        if (isset($filters["has_stabilization"]) && $filters["has_stabilization"] !== "") {
            $sql .= " AND s.has_stabilization = :has_stabilization";
            $filter_values["has_stabilization"] = $filters["has_stabilization"];
        }
        if (isset($filters["min_focus_distance_max"]) && $filters["min_focus_distance_max"] > 0) {
            $sql .= " AND min_focus_distance_m <= :min_focus_distance_max";
            $filter_values["min_focus_distance_max"] = $filters["min_focus_distance_max"];
        }

        if (isset($filters["filter_size"]) && $filters["filter_size"] > 0) {
            $sql .= " AND l.filter_size_mm = :filter_size";
            $filter_values["filter_size"] = $filters["filter_size"];
        }

        return $this->paginate($sql, $filter_values);
    }
}

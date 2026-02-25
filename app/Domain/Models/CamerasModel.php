<?php

namespace App\Domain\Models;

class CamerasModel extends BaseModel
{
    public function getAllCameras() : array
    {
        $sql = "SELECT * FROM camera_bodies";
        return $this->fetchAll($sql);
    }

    public function getCamera(int $id) : mixed
    {
        $sql = "SELECT * FROM camera_bodies WHERE body_id = :id";
        return $this->fetchSingle($sql, ["id"=>$id]);
    }
}

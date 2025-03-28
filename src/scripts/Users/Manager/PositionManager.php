<?php

namespace User\Manager;

require_once dirname(__DIR__, 4) . '/vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as DB;

class PositionManager
{
    private object $positions;

    function __construct()
    {
        $this->fetch();
    }

    private function fetch(): void
    {
        $this->positions = DB::table("positions")
            ->orderBy("id")
            ->get();
    }

    public function getAll(): array
    {
        return $this->positions->toArray();
    }

    public function getById(int $id): object
    {
        $result = $this->positions
            ->where("id", $id)
            ->first();

        if ($result) {
            return $result;
        }
        return new stdClass();
    }
}

?>
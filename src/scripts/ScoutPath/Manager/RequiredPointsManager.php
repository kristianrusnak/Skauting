<?php

namespace ScoutPath\Manager;

require_once dirname(__DIR__, 4) . '/vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as DB;

class RequiredPointsManager
{
    private object $rp;

    function __construct()
    {
        $this->fetch();
    }

    private function fetch(): void
    {
        $this->rp = DB::table('required_points')
            ->orderBy('scout_path_id')
            ->orderBy('area_id')
            ->get();
    }

    public function getAll(): array
    {
        return $this->rp->toArray();
    }

    public function getAllByAreaAndScoutPathId(int $scout_path_id, int $area_id): object
    {
        $result = $this->rp
            ->where('area_id', $area_id)
            ->where('scout_path_id', $scout_path_id)
            ->first();

        if ($result) {
            return $result;
        }
        return new class {};
    }

    public function add($data): bool
    {
        $scout_path_id = $data['scout_path_id'] ?? 0;
        $area_id = $data['area_id'] ?? 0;
        $required_points = $data['required_points'] ?? null;
        $type_of_points = $data['type_of_points'] ?? "Uloha";
        $name = $data['name'] ?? "Nova sekcia";
        $icon = $data['icon'] ?? "task";

        if ($scout_path_id <= 0 || $area_id <= 0) {
            return false;
        }

        $data = [
            'scout_path_id' => $scout_path_id,
            'area_id' => $area_id,
            'required_points' => $required_points,
            'type_of_points' => $type_of_points,
            'name' => $name,
            'icon' => $icon
        ];

        DB::table('required_points')
            ->insert($data);

        $this->fetch();

        return true;
    }

    public function remove(int $scout_path_id): int
    {
        return DB::table('required_points')
            ->where('scout_path_id', $scout_path_id)
            ->delete();
    }

    public function updateRP(int $scout_path_id, int $area_id, string $row, string|int $newValue): int
    {
        return DB::table('required_points')
            ->where('scout_path_id', $scout_path_id)
            ->where('area_id', $area_id)
            ->update([
                $row => $newValue
            ]);
    }
}

?>
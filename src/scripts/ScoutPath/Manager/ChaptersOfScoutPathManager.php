<?php

namespace ScoutPath\Manager;

require_once dirname(__DIR__, 4) . '/vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as DB;

class ChaptersOfScoutPathManager
{
    private object $chapters;

    function __construct()
    {
        $this->fetch();
    }

    private function fetch(): void
    {
        $this->chapters = DB::table("chapters_of_scout_path")->get();
    }

    public function getAll(): array
    {
        return $this->chapters->toArray();
    }

    public function getAllByScoutPathId(int $scout_path_id): array
    {
        return $this->chapters
            ->where('scout_path_id', $scout_path_id)
            ->toArray();
    }

    public function getAllByAreaIdAndScoutPathId(int $scout_path_id, int $area_id): array
    {
        return $this->chapters
            ->where('scout_path_id', $scout_path_id)
            ->where('area_id', $area_id)
            ->toArray();
    }

    public function getById(int $id): null|object
    {
        $result = $this->chapters
            ->where('id', $id)
            ->first();

        if ($result) {
            return $result;
        }
        return null;
    }

    public function add($data): int
    {
        $name = $data['name'] ?? "";
        $mandatory = $data['mandatory'] ?? 0;
        $area_id = $data['area_id'] ?? 0;
        $scout_path_id = $data['scout_path_id'] ?? 0;

        if (empty($name) || $area_id <= 0 || $scout_path_id <= 0) {
            return 0;
        }

        $data = [
            'name' => $name,
            'mandatory' => $mandatory,
            'area_id' => $area_id,
            'scout_path_id' => $scout_path_id
        ];

        $id = DB::table('chapters_of_scout_path')
            ->insertGetId($data);

        $this->fetch();

        return $id;
    }

    public function remove(int $id): int
    {
        $effected = DB::table('chapters_of_scout_path')
            ->where('id', $id)
            ->delete();

        $this->fetch();

        return $effected;
    }

    public function update(int $id, string $row, string|int $newValue): bool
    {
        $effected = DB::table('chapters_of_scout_path')
            ->where('id', $id)
            ->update([
                $row => $newValue
            ]);

        $this->fetch();

        return $effected;
    }
}

?>
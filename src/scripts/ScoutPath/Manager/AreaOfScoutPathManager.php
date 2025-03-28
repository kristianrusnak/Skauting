<?php

namespace ScoutPath\Manager;

require_once dirname(__DIR__, 4) . '/vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as DB;

class AreaOfScoutPathManager
{
    /**
     * @var array
     */
    private object $areas;

    function __construct()
    {
        $this->fetch();
    }

    private function fetch(): void
    {
        $this->areas = DB::table("areas_of_progress")->get();
    }

    public function getAll(): array
    {
        return $this->areas->toArray();
    }

    public function getById($id): object
    {
        $result = $this->areas
            ->where('id', $id)
            ->first();

        if ($result) {
            return $result;
        }
        return new class{};
    }

    public function add(string $name, string $color): int
    {
        if (empty($name) || empty($color)) {
            return 0;
        }

        $data = [
            'name' => $name,
            'color' => $color
        ];

        $id = DB::table('areas_of_progress')
            ->insertGetId($data);

        $this->fetch();

        return $id;
    }

    public function remove(int $id): int
    {
        $effected = DB::table('areas_of_progress')
            ->where('id', $id)
            ->delete();

        $this->fetch();

        return $effected;
    }

    public function update(int $id, string $row, string $newValue): int
    {
        if (empty($newValue)) {
            return 0;
        }

        $effected = DB::table('areas_of_progress')
            ->where('id', $id)
            ->update([
                $row => $newValue
            ]);

        $this->fetch();

        return $effected;
    }
}

?>
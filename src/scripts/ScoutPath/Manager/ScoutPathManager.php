<?php

namespace ScoutPath\Manager;

require_once dirname(__DIR__, 4) . '/vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as DB;

class ScoutPathManager
{
    private object $path;

    function __construct()
    {
        $this->fetch();
    }

    private function fetch(): void
    {
        $this->path = DB::table("scout_path")->get();
    }

    public function getAll(): array
    {
        return $this->path->toArray();
    }

    public function get(int $id): null|object
    {
        $result = $this->path
            ->where('id', $id)
            ->first();

        if ($result) {
            return $result;
        }
        return null;
    }

    public function add(array $data): int
    {
        $name = $data['name'] ?? '';
        $image = $data['image'] ?? '';
        $color = $data['color'] ?? '';
        $required_points = $data['required_points'] ?? null;

        if (empty($name) || empty($image) || empty($color)) {
            return 0;
        }

        $data = [
            'name' => $name,
            'image' => $image,
            'color' => $color,
            'required_points' => $required_points
        ];

        $id = DB::table("scout_path")
            ->insertGetId($data);

        $this->fetch();

        return $id;
    }

    public function remove(int $id): int
    {
        $effected = DB::table("scout_path")
            ->where('id', $id)
            ->delete();

        $this->fetch();

        return $effected;
    }

    public function update(int $id, string $row, string|int $newValue): int
    {
        $effected = DB::table("scout_path")
            ->where('id', $id)
            ->update([
                $row => $newValue
            ]);

        $this->fetch();

        return $effected;
    }
}

?>
<?php

namespace MeritBadge\Manager;

require_once dirname(__DIR__, 4) . '/vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as DB;

class MeritBadgeLevelManager
{
    private object $levels;

    function __construct(){
        $this->fetch();
    }

    private function fetch(): void
    {
        $this->levels = DB::table("levels_of_merit_badge")
            ->orderBy('id')
            ->get();
    }

    public function containsId(int $id): bool
    {
        return $this->levels->where('id', $id)->count() > 0;
    }

    public function getAll(): array
    {
        return $this->levels->toArray();
    }

    public function getById(int $id): null|object
    {
        $result = $this->levels
            ->where('id', $id)
            ->first();

        if ($result) {
            return $result;
        }
        return null;
    }

    public function add(array $level): int
    {
        $name = $level['name'] ?? "";
        $image = $level['image'] ?? "";
        $color = $level['color'] ?? "";

        if (empty($name) || empty($image) || empty($color)) {
            return -1;
        }

        $data = [
            'name' => $name,
            'image' => $image,
            'color' => $color
        ];

        $id = DB::table("levels_of_merit_badge")
            ->insertGetId($data);

        $this->fetch();

        return $id;
    }

    public function remove(int $id): int
    {
        $effected = DB::table("levels_of_merit_badge")
            ->where('id', $id)
            ->delete();

        $this->fetch();

        return $effected;
    }

    public function update(int $id, string $row, string $newValue): int
    {
        if (empty($row) || empty($newValue)) {
            return -1;
        }

        $effected = DB::table("levels_of_merit_badge")
            ->where('id', $id)
            ->update([
                $row => $newValue
            ]);

        $this->fetch();

        return $effected;
    }
}

?>
<?php

namespace MeritBadge\Manager;

require_once dirname(__DIR__, 4) . '/vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as DB;
class CategoriesOfMeritBadgeManager
{
    private object $categories;

    function __construct()
    {
        $this->fetch();
    }

    private function fetch(): void
    {
        $this->categories = DB::table('categories_of_merit_badges')
            ->orderBy('id')
            ->get();
    }

    public function containsId(int $id): bool
    {
        return $this->categories->where('id', $id)->count() > 0;
    }

    public function getAll(): array
    {
        return $this->categories->toArray();
    }

    public function getById(int $id): object
    {
        $result = $this->categories
            ->where('id', $id)
            ->first();

        if ($result) {
            return $result;
        }
        return new class{};
    }

    public function add(string $name): int
    {
        if (empty($name)) {
            return -1;
        }

        $id = DB::table('categories_of_merit_badges')
            ->insertGetId([
                'name' => $name
            ]);

        $this->fetch();

        return $id;
    }

    public function remove(int $id): int
    {
        $effected = DB::table('categories_of_merit_badges')
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

        $effected = DB::table('categories_of_merit_badges')
            ->where('id', $id)
            ->update([
                $row => $newValue
            ]);

        $this->fetch();

        return $effected;
    }
}

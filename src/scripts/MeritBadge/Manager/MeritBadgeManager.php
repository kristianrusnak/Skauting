<?php

namespace MeritBadge\Manager;

require_once dirname(__DIR__, 4) . '/vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as DB;

class MeritBadgeManager
{
    private object $badges;

    public function __construct()
    {
        $this->fetch();
    }

    private function fetch(): void
    {
        $this->badges = DB::table('merit_badges as mb')
            ->orderBy('mb.category_id')
            ->orderBy('mb.name')
            ->get();
    }

    public function getAll(): array
    {
        return $this->badges->toArray();
    }

    public function getAllByCategoryId(int $category_id): array
    {
        return $this->badges
            ->where('category_id', $category_id)
            ->toArray();
    }

    public function getById(int $id): null|object
    {
        $result = $this->badges
            ->where('id', $id)
            ->first();

        if ($result) {
            return $result;
        }

        return null;
    }

    public function add(array $data): int
    {
        $name = $data['name'] ?? "";
        $image = $data['image'] ?? "";
        $color = $data['color'] ?? "";
        $category_id = $data['category'] ?? "";
        $additional_information_id = $additional_information_id ?? null;

        if (empty($name) || empty($image) || empty($color) || empty($category_id)) {
            return -1;
        }

        $data = [
            'name' => $name,
            'image' => $image,
            'color' => $color,
            'category_id' => $category_id,
            'additional_information_id' => $additional_information_id
        ];

        $id = DB::table("merit_badges")
            ->insertGetId($data);

        $this->fetch();

        return $id;
    }

    public function delete(int $id): int
    {
        $effected = DB::table("merit_badges")
            ->where('id', $id)
            ->delete();

        $this->fetch();

        return $effected;
    }

    public function update(int $id, string $row, string|int $newValue): int
    {
        $effected = DB::table("merit_badges")
            ->where('id', $id)
            ->update([
                $row => $newValue
            ]);

        $this->fetch();

        return $effected;
    }
}
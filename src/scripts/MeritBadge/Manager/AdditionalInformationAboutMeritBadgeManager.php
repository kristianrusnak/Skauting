<?php

namespace MeritBadge\Manager;

require_once dirname(__DIR__, 4) . '/vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as DB;

class AdditionalInformationAboutMeritBadgeManager
{
    private object $info;

    function __construct()
    {
           $this->fetch();
    }

    private function fetch(): void
    {
        $this->info = DB::table("additional_information")->get();
    }

    public function containsId(int $id): bool
    {
        return $this->info->where("id", $id)->count() > 0;
    }

    public function getAll(): array
    {
        return $this->info->toArray();
    }

    public function getById(int $id): object
    {
        $result = $this->info
            ->where("id", $id)
            ->first();

        if ($result) {
            return $result;
        }
        return new class{};
    }

    public function add(array $newInfo): int
    {
        $before = $newInfo["before"] ?? "";
        $between = $newInfo["between"] ?? "";
        $after = $newInfo["after"] ?? "";

        if (empty($before) && empty($between) && empty($after)) {
            return -1;
        }

        $insertData = [
            'before' => $before,
            'between' => $between,
            'after' => $after
        ];

        $id = DB::table("additional_information")
            ->insertGetId($insertData);

        $this->fetch();

        return $id;
    }

    public function remove(int $id): int
    {
        $effected = DB::table("additional_information")
            ->where("id", $id)
            ->delete();

        $this->fetch();

        return $effected;
    }

    public function update(int $id, string $row, int $newValue): int
    {
        if (empty($row) || empty($newValue)) {
            return -1;
        }

        $effected = DB::table("additional_information")
            ->where("id", $id)
            ->update([
                $row => $newValue
            ]);

        $this->fetch();

        return $effected;
    }
}

?>
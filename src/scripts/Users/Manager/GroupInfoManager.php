<?php

namespace User\Manager;

require_once dirname(__DIR__, 4) . '/vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as DB;

class GroupInfoManager
{
    public function getName(int $leader_id): string
    {
        $info = DB::table("group_info")
            ->select('name')
            ->where("leader_id", "=", $leader_id)
            ->first();

        if ($info) {
            return $info->name;
        }

        return "";
    }

    public function add(int $leader_id, string $name): bool
    {
        if (empty($name)) {
            return false;
        }

        return DB::table("group_info")
            ->insert([
                "leader_id" => $leader_id,
                "name" => $name
            ]);
    }

    public function remove(int $leader_id): int
    {
        return DB::table("group_info")
            ->where("leader_id", "=", $leader_id)
            ->delete();
    }

    public function update(int $leader_id, string $newName): int
    {
        if (empty($newName)) {
            return 0;
        }

        return DB::table("group_info")
            ->where("leader_id", "=", $leader_id)
            ->update([
                "name" => $newName
            ]);
    }
}
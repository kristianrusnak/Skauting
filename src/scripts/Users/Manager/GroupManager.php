<?php

namespace User\Manager;

require_once dirname(__DIR__, 4) . '/vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as DB;

class groupManager
{
    public function isMemberOfGroup(int $user_id):bool
    {
        return DB::table("groups")
            ->where("user_id", "=", $user_id)
            ->count() > 0;
    }

    public function getAllGroups(): array
    {
        return DB::table("groups")
            ->get()
            ->toArray();
    }

    public function getGroup(int $leader_id): array
    {
        return DB::table("groups")
            ->select('user_id')
            ->where("leader_id", "=", $leader_id)
            ->get()
            ->toArray();
    }

    public function getAllLeaders(): array
    {
        return DB::table("groups")
            ->select('leader_id')
            ->groupBy("leader_id")
            ->get()
            ->toArray();
    }

    public function addGroupMember(int $leader_id, int $member_id): bool
    {
        return DB::table("groups")
            ->insert([
                'leader_id' => $leader_id,
                'user_id' => $member_id
            ]);
    }

    public function removeGroup(int $leader_id): int
    {
        return DB::table("groups")
            ->where("leader_id", "=", $leader_id)
            ->delete();
    }

    public function removeMemberOfGroup(int $member_id): int
    {
        return DB::table("groups")
            ->where("user_id", "=", $member_id)
            ->delete();
    }
}
<?php

namespace User\Manager;

require_once dirname(__DIR__, 4) . '/vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as DB;

class UserManager
{

    public function verify(string $mail, string $password): object
    {
        $stored_password = DB::table("users")
            ->select("password")
            ->where("email", $mail)
            ->first();

        if ($stored_password && password_verify($password, $stored_password->password)) {
            return DB::table("users")
                ->where("email", $mail)
                ->first();
        }
        return new stdClass();
    }

    public function getAll(): array
    {
        return DB::table("users")
            ->get()
            ->toArray();
    }

    public function get(int $id): object
    {
        return DB::table("users")
            ->where("id", $id)
            ->first();
    }

    public function getAllScouts(): array
    {
        return DB::table("users")
            ->where("position_id", 1)
            ->get()
            ->toArray();
    }

    public function getAllPatrolLeaders(): array
    {
        return DB::table("users")
            ->where("position_id", 3)
            ->get()
            ->toArray();
    }

    public function getAllLeaders(): array
    {
        return DB::table("users")
            ->where("position_id", 4)
            ->get()
            ->toArray();
    }

    public function add(array $user): int
    {
        $name = $user['name'] ?? '';
        $email = $user['email'] ?? '';
        $image = $user['image'] ?? null;
        $password = $user['password'] ?? '';
        $position_id = $user['position_id'] ?? 1;

        if (empty($name) || empty($email) || empty($password)) {
            return 0;
        }

        $password = password_hash($password, PASSWORD_DEFAULT);

        $data = [
            'name' => $name,
            'email' => $email,
            'image' => $image,
            'password' => $password,
            'position_id' => $position_id
        ];

        $id = DB::table('users')
            ->insertGetId($data);

        return $id;
    }

    public function remove(int $id): int
    {
        return DB::table("users")
            ->where("id", $id)
            ->delete();
    }

    public function update(int $id, string $row, string|int $newValue): int
    {
        return DB::table("users")
            ->where("id", $id)
            ->update([
                $row => $newValue
            ]);
    }
}
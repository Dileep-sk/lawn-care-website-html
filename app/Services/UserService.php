<?php

namespace App\Services;

use App\Models\User;
use Exception;

class UserService
{
    public function getAllUsers($perPage = 10, $search = null)
    {
        try {
            $query = User::select('id', 'name', 'email', 'mobile_number', 'status', 'created_at')
                ->orderBy('id', 'desc');

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('mobile_number', 'like', "%{$search}%");
                });
            }

            return $query->paginate($perPage);
        } catch (Exception $e) {
            throw new Exception("Error fetching users: " . $e->getMessage());
        }
    }

    public function deleteUserById($id): bool
    {
        try {
            $user = User::find($id);

            if (!$user) {
                return false;
            }

            return $user->delete();
        } catch (Exception $e) {
            throw new Exception("Error deleting user: " . $e->getMessage());
        }
    }

    public function updateUserStatus($id, $status): bool
    {
        try {
            $user = User::find($id);

            if (!$user) {
                return false;
            }

            $user->status = $status;

            return $user->save();
        } catch (Exception $e) {
            throw new Exception("Error updating user status: " . $e->getMessage());
        }
    }
}

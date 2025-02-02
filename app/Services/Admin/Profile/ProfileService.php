<?php

namespace App\Services\Admin\Profile;

use App\Models\User;
use App\Mail\NewAdminEmail;
use App\Repositories\UserRepository;
use App\Repositories\AuthLogsRepository;
use Illuminate\Support\Facades\Mail;

class ProfileService
{
    protected $repository;
    protected $authLogs;

    public function __construct(UserRepository $repository, AuthLogsRepository $authLogs)
    {
        $this->repository = $repository;
        $this->authLogs = $authLogs;
    }

    public function getById(string $id)
    {
        return $this->repository->getById($id);
    }

    public function withMeta(string $id): User
    {
        return $this->repository->withMeta($id);
    }

    public function updateProfile(string $id, array $data): User
    {
        return $this->repository->updateProfile($id, $data);
    }

    public function updateEmail(string $id, array $data): User
    {
        $user = $this->repository->updateEmail($id, $data);
        if ($user) {
            $user->sendEmailVerificationNotification();
        }
        return $user;
    }

    public function updatePassword(string $id, array $data): User
    {
        $user = $this->repository->updatePassword($id, $data);
        Mail::to($user->email)->send(new NewAdminEmail($user, $data['password']));
        return $user;
    }

    public function activityLogs(string $id): User
    {
        return $this->repository->withActivities($id);
    }

    public function authLogs(string $id): User
    {
        return $this->repository->withAuthLogs($id);
    }
}

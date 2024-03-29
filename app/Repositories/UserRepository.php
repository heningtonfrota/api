<?php

namespace App\Repositories;

use App\DTO\Users\CreateUserDTO;
use App\DTO\Users\UpdateUserDTO;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class UserRepository
{
    public function __construct(
        protected User $user
    ) {}

    public function getPaginate(int $totalPerPage = 15, int $page = 1, string $filter = '') : LengthAwarePaginator
    {
        return $this->user->where(function ($query) use ($filter) {
            if (isset($filter)) {
                $query->where('name', 'LIKE', "%{$filter}%");
            }
        })
        ->with(['permissions'])
        ->paginate($totalPerPage, ['*'], 'page', $page);
    }

    public function createNew(CreateUserDTO $dto): User
    {
        $data = (array) $dto;
        $data['password'] = bcrypt($data['password']);

        return $this->user->create($data);
    }

    public function findById(string $id): ?User
    {
        return $this->user->find($id);
    }

    public function findByEmail(string $email): ?User
    {
        return $this->user->where('email', $email)->first();
    }

    public function update(UpdateUserDTO $dto): bool
    {
        if(!$user = $this->findById($dto->id)) {
            return false;
        }

        $data = (array) $dto;
        unset($data['password']);
        if ($dto->password !== null) {
            $data['password'] = bcrypt($dto->password);
        }

        return $user->update($data);
    }

    public function delete(string $id): bool
    {
        if(!$user = $this->findById($id)) {
            return false;
        }

        return $user->delete();
    }

    public function syncPermissions(string $id, array $permissions): ?bool
    {
        if(!$user = $this->findById($id)) {
            return null;
        }

        $user->permissions()->sync($permissions);

        return true;
    }

    public function getPermissionsByUserId(string $id)
    {
        return $this->findById($id)->permissions()->get();
    }

    public function hasPermission(User $user, string $namePermission): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->permissions()->where('name', $namePermission)->exists();
    }
}

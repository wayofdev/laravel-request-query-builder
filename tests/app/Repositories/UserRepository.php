<?php

declare(strict_types=1);

namespace WayOfDev\RQL\App\Repositories;

use WayOfDev\RQL\App\Entities\User;
use WayOfDev\RQL\Bridge\Cycle\Repository;

class UserRepository extends Repository implements UserRepositoryInterface
{
    public function findByUsername(string $username): ?User
    {
        return $this->findOne(['username' => $username]);
    }
}

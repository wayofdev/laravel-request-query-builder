<?php

declare(strict_types=1);

namespace WayOfDev\RQL\App\Repositories;

use Cycle\ORM\RepositoryInterface;
use WayOfDev\RQL\Bridge\Cycle\CriteriaManager;

interface UserRepositoryInterface extends RepositoryInterface, CriteriaManager
{
}

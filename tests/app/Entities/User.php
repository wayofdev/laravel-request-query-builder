<?php

declare(strict_types=1);

namespace WayOfDev\RQL\App\Entities;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use WayOfDev\RQL\App\Repositories\UserRepository;

#[Entity(
    repository: UserRepository::class,
    table: 'users',
)]
class User
{
    #[Column(type: 'primary')]
    public int $id;

    #[Column(type: 'string', nullable: true)]
    public ?string $email = null;

    #[Column(type: 'string', nullable: true)]
    public ?string $username;

    #[Column(type: 'string', nullable: true)]
    public ?string $name;

    #[Column(type: 'string', nullable: true)]
    public ?string $surname;

    #[Column(type: 'string', nullable: true)]
    public ?string $company = null;

    public function __construct(
        string $email,
        string $username,
        string $name,
        string $surname,
        string $company,
    ) {
        $this->email = $email;
        $this->username = $username;
        $this->name = $name;
        $this->surname = $surname;
        $this->company = $company;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function getCompany(): ?string
    {
        return $this->company;
    }
}

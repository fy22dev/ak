<?php

namespace App\Core\User\Domain;

use App\Common\EventManager\EventsCollectorTrait;
use App\Core\User\Domain\Event\UserCreatedEvent;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User
{
    use EventsCollectorTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer", options={"unsigned"=true}, nullable=false)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=300, nullable=false, unique=true)
     */
    private string $email;

    /**
     * @ORM\Column(type="boolean", nullable=false, options={"default":false})
     */
    private bool $isActive = false;

    public function __construct(string $email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Nieprawidłowy adres e-mail');
        }

        $this->id = null;
        $this->email = $email;

        $this->record(new UserCreatedEvent($this));
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function activate(): void
    {
        $this->isActive = true;
    }
}

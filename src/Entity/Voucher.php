<?php

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Post;
use Doctrine\DBAL\Types\Types;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\VoucherRepository;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Doctrine\Orm\Filter\BooleanFilter;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiFilter(BooleanFilter::class, properties: ['isExpired'])]
#[ApiResource(
    normalizationContext: ['groups' => ['voucher:read']],
    denormalizationContext: ['groups' => ['voucher:write']],
)]
#[Post(security : 'is_granted("ROLE_ADMIN")', securityMessage: 'Only admins can create vouchers')]
#[Put(security : 'is_granted("ROLE_ADMIN")')]
#[Delete(security : 'is_granted("ROLE_ADMIN")')]
#[Patch(security : 'is_granted("ROLE_ADMIN")')]
#[GetCollection()]
#[Get]
#[ORM\Entity(repositoryClass: VoucherRepository::class)]
class Voucher
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['voucher:read'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['voucher:read', 'voucher:write'])]
    private ?int $amount = null;

    #[Groups(['voucher:read', 'voucher:write'])]
    #[ORM\Column(length: 255)]
    private ?string $code = null;

    #[Groups(['voucher:read'])]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[Groups(['voucher:read'])]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $expiredAt = null;

    #[Groups(['voucher:read'])]
    #[ORM\Column]
    private ?bool $isExpired = null;

    #[Groups(['voucher:read'])]
    #[ORM\OneToOne(mappedBy: 'voucher', cascade: ['persist', 'remove'])]
    private ?Order $command = null;

    public function __construct()
    {
        $this->isExpired = false;
        $this->createdAt = new \DateTime();
        $this->expiredAt = new \DateTime('+1 week');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getExpiredAt(): ?\DateTimeInterface
    {
        return $this->expiredAt;
    }

    public function setExpiredAt(\DateTimeInterface $expiredAt): self
    {
        $this->expiredAt = $expiredAt;

        return $this;
    }

    public function isIsExpired(): ?bool
    {
        return $this->isExpired;
    }

    public function setIsExpired(bool $isExpired): self
    {
        $this->isExpired = $isExpired;

        return $this;
    }

    public function getCommand(): ?Order
    {
        return $this->command;
    }

    public function setCommand(?Order $command): self
    {
        // unset the owning side of the relation if necessary
        if ($command === null && $this->command !== null) {
            $this->command->setVoucher(null);
        }

        // set the owning side of the relation if necessary
        if ($command !== null && $command->getVoucher() !== $this) {
            $command->setVoucher($this);
        }

        $this->command = $command;

        return $this;
    }
}

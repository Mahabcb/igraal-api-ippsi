<?php

namespace App\Entity;

use App\State\ApplyVoucher;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Post;
use Doctrine\DBAL\Types\Types;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiFilter;
use App\Repository\OrderRepository;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiFilter(OrderFilter::class, properties: ['createdAt', 'DESC'])]
#[ApiResource(
    normalizationContext: ['groups' => ['order:read']],
    denormalizationContext: ['groups' => ['order:write']],
    operations : [
        new Get,
        new GetCollection,
        new Put(processor: ApplyVoucher::class),
        new Post(processor: ApplyVoucher::class),
        new Patch(processor: ApplyVoucher::class),
        new Delete,
    ],
)]
#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['order:read'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['order:read'])]
    private ?int $amount = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['order:read'])]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\OneToOne(inversedBy: 'command', cascade: ['persist', 'remove'])]
    #[Groups(['order:read', 'order:write'])]
    private ?Voucher $voucher = null;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getVoucher(): ?Voucher
    {
        return $this->voucher;
    }

    public function setVoucher(?Voucher $voucher): self
    {
        $this->voucher = $voucher;

        return $this;
    }
}

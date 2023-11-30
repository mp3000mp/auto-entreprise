<?php

namespace App\Entity;

interface FileInterface
{
    public function getId(): ?int;

    public function getCreatedAt(): \DateTime;

    public function getCreatedBy(): User;

    public function setCreatedBy(User $createdBy): self;

    public function getPath(): string;

    public function setPath(string $path): self;

    public function getName(): string;

    public function setName(string $name): self;

    public function getExtension(): string;

    public function setExtension(string $extension): self;
}

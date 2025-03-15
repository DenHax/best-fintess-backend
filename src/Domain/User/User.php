<?php

namespace App\Domain\User;

class User
{
    private ?string $user_uuid;
    private string $user_surname;
    private string $user_firstname;
    private string $user_gender;
    private int $user_age;
    private int $user_height;
    private int $user_weight;
    private bool $user_is_trainer;
    private string $user_phone;
    private string $user_hash_password;
    private string $user_avatar_path;

    public function __construct(
        ?string $user_uuid,
        string $user_surname,
        string $user_firstname,
        string $user_gender,
        int $user_age,
        int $user_height,
        int $user_weight,
        bool $user_is_trainer,
        string $user_phone,
        string $user_hash_password,
        string $user_avatar_path
    ) {
        $this->user_uuid = $user_uuid;
        $this->user_surname = $user_surname;
        $this->user_firstname = $user_firstname;
        $this->user_gender = $user_gender;
        $this->user_age = $user_age;
        $this->user_height = $user_height;
        $this->user_weight = $user_weight;
        $this->user_is_trainer = $user_is_trainer;
        $this->user_phone = $user_phone;
        $this->user_hash_password = $user_hash_password;
        $this->user_avatar_path = $user_avatar_path;
    }

    public function getUserUuid(): ?string
    {
        return $this->user_uuid;
    }

    public function getUserSurname(): string
    {
        return $this->user_surname;
    }

    public function getUserFirstname(): string
    {
        return $this->user_firstname;
    }

    public function getUserGender(): string
    {
        return $this->user_gender;
    }

    public function getUserAge(): int
    {
        return $this->user_age;
    }

    public function getUserHeight(): int
    {
        return $this->user_height;
    }

    public function getUserWeight(): int
    {
        return $this->user_weight;
    }

    public function getUserPhone(): string
    {
        return $this->user_phone ;
    }

    public function getUserHashPassword(): string
    {
        return $this->user_hash_password;
    }

    public function getUserIsTrainer(): bool
    {
        return $this->user_is_trainer;
    }

    public function getUserAvatarPath(): string
    {
        return $this->user_avatar_path;
    }

    public function setUserUuid(string $user_uuid): void
    {
        $this->user_uuid = $user_uuid;
    }

    public function setUserSurname(string $user_surname): void
    {
        $this->user_surname = $user_surname;
    }

    public function setUserFirstname(string $user_firstname): void
    {
        $this->user_firstname = $user_firstname;
    }

    public function setUserGender(string $user_gender): void
    {
        $this->user_gender = $user_gender;
    }

    public function setUserAge(int $user_age): void
    {
        $this->user_age = $user_age;
    }

    public function setUserHeight(int $user_height): void
    {
        $this->user_height = $user_height;
    }

    public function setUserWeight(int $user_weight): void
    {
        $this->user_weight = $user_weight;
    }

    public function setUserPhone(string $user_phone): void
    {
        $this->user_phone = $user_phone;
    }

    public function setUserHashPassword(string $user_hash_password): void
    {
        $this->user_hash_password = $user_hash_password;
    }

    public function setUserIsTrainder(string $user_is_trainer): void
    {
        $this->user_is_trainer = $user_is_trainer;
    }

    public function setUserAvatarPath(string $user_avatar_path): void
    {
        $this->user_avatar_path = $user_avatar_path;
    }
}

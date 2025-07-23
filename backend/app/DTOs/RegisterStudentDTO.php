<?php
namespace App\DTOs;

class RegisterStudentDTO
{
  public function __construct(
        public readonly string $email,
        public  string $password,
        public  string $first_name,
        public  string $last_name,
        public  string $phone_number,
        public  ?string $second_phone_number,
        public  ?string $parent_phone_number,
        public  string $preferred_contact_method,
        public  ?string $year_of_study,
        public  ?string $date_of_birth,
        public  ?string $address,
    ) {}
    public static function fromRequest(array $data): self
    {
        return new self(
            email: $data['email'],
            password: $data['password'],
            first_name: $data['first_name'],
            last_name: $data['last_name'],
            phone_number: $data['phone_number'],
            second_phone_number: $data['second_phone_number'] ?? null,
            parent_phone_number: $data['parent_phone_number'] ?? null,
            preferred_contact_method: $data['preferred_contact_method'] ?? 'whatsapp',
            year_of_study: $data['year_of_study'] ?? null,
            date_of_birth: $data['date_of_birth'] ?? null,
            address: $data['address'] ?? null
        );
    }
}

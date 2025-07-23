<?php
namespace App\Services;
use App\DTOs\RegisterStudentDTO;
use App\Models\User;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class RegisterStudentService
{
    public function register(RegisterStudentDTO $dto): User
    {
        DB::beginTransaction();
        try {
            // Create the user
            $user = User::create([
                'email' => $dto->email,
                'password' => Hash::make($dto->password),
                'first_name' => $dto->first_name,
                'last_name' => $dto->last_name,
            ]);

             $user->assignRole('student'); 

            // Create the student profile
            Student::create([
                'user_id' => $user->id,
                'phone_number' => $dto->phone_number,
                'second_phone_number' => $dto->second_phone_number,
                'parent_phone_number' => $dto->parent_phone_number,
                'preferred_contact_method' => $dto->preferred_contact_method,
                'year_of_study' => $dto->year_of_study,
                'date_of_birth' => $dto->date_of_birth,
                'address' => $dto->address,
            ]);

            DB::commit();
            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Registration failed: ' . $e->getMessage());
        }
    }
}

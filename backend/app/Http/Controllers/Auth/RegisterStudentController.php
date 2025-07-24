<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\RegisterStudentRequest;
use App\Services\RegisterStudentService;
use App\DTOs\RegisterStudentDTO;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use App\Helpers\ApiHelper;

class RegisterStudentController extends Controller
{
    public function __construct(private RegisterStudentService $registerStudentService){}
    public function __invoke(RegisterStudentRequest $request): JsonResponse
    {
        try{
        $dto = RegisterStudentDTO::fromRequest($request->validated());
            $user = $this->registerStudentService->register($dto);
            return ApiHelper::success('student.registered', [
                'user' => new UserResource($user)
            ]);

    }catch (\Exception $e) {
            return ApiHelper::apiError('student.registration_failed', [], 500);
        }
        }
}

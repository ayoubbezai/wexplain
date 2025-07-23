<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterStudentRequest;
use App\Services\RegisterStudentService;
use App\DTOs\RegisterStudentDTO;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;

class RegisterStudentController extends Controller
{
    public function __construct(private RegisterStudentService $registerStudentService){}
    public function __invoke(RegisterStudentRequest $request): JsonResponse
    {
        $dto = RegisterStudentDTO::fromRequest($request->validated());
            $user = $this->registerStudentService->register($dto);
            return response()->json(new UserResource($user), 201);

    }
}

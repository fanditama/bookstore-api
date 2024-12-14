<?php

namespace App\Http\Controllers;

use App\Http\Requests\BankCreateRequest;
use App\Http\Resources\BankResource;
use App\Http\Resources\PaymentResource;
use App\Models\Bank;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BankController extends Controller
{
    public function create(BankCreateRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = Auth::user();

        $bank = new Bank($data);
        $bank->user_id = $user->id;
        $bank->save();

        return (new BankResource($bank))->response()->setStatusCode(201);
    }
}

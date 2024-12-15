<?php

namespace App\Http\Controllers;

use App\Http\Requests\BankCreateRequest;
use App\Http\Requests\BankUpdateRequest;
use App\Http\Resources\BankCollection;
use App\Http\Resources\BankResource;
use App\Http\Resources\PaymentResource;
use App\Models\Bank;
use App\Models\Book;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Exceptions\HttpResponseException;
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

    public function get(int $id): BankResource
    {
        $user = Auth::user();
        $bank = Bank::where('id', $id)->where('user_id', $user->id)->first();

        if (!$bank) {
            throw new HttpResponseException(response()->json([
                'errors' => [
                    'message' =>[
                        'not found'
                    ]
                ]
            ])->setStatusCode(404));
        }

        return new BankResource($bank);
    }

    public function update(int $id, BankUpdateRequest $request): BankResource
    {
        $user = Auth::user();
        $bank = Bank::where('id', $id)->where('user_id', $user->id)->first();

        if (!$bank) {
            throw new HttpResponseException(response()->json([
                'errors' => [
                    'message' =>[
                        'not found'
                    ]
                ]
            ])->setStatusCode(404));
        }

        $data = $request->validated();
        $bank->fill($data);
        $bank->save();

        return new BankResource($bank);
    }

    public function delete(int $id): JsonResponse
    {
        $user = Auth::user();
        $bank = Bank::where('id', $id)->where('user_id', $user->id)->first();

        if (!$bank) {
            throw new HttpResponseException(response()->json([
                'errors' => [
                    'message' =>[
                        'not found'
                    ]
                ]
            ])->setStatusCode(404));
        }

        $bank->delete();
        return response()->json([
            'data' => true
        ])->setStatusCode(200);
    }

    public function search(Request $request): BankCollection
    {
        $user = Auth::user();
        $page = $request->input('page', 1);
        $size = $request->input('size', 10);

        $banks = Bank::query()->where('user_id', $user->id);

        $banks = $banks->where(function (Builder $builder) use ($request) {

            // jika user input identity
            $identity = $request->input('identity');
            if ($identity) {
                // hasil pencarian
                $builder->orWhere('name', 'like', '%' . $identity . '%');
                $builder->orWhere('account_name', 'like', '%' . $identity . '%');
            }

            // jika user input number
            $number = $request->input('number');
            if ($number) {
                // hasil pencarian
                $builder->orWhere('account_number', 'like', '%' . $number . '%');
            }

            // jika user input image
            $image = $request->input('image');
            if ($image) {
                // hasil pencarian
                $builder->orWhere('image', 'like', '%' . $image . '%');
            }
        });

        $banks = $banks->paginate(perPage: $size, page: $page);

        return new BankCollection($banks);
    }
}

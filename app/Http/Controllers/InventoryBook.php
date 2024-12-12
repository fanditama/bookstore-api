<?php

namespace App\Http\Controllers;

use App\Http\Requests\InventoryBookCreateRequest;
use App\Http\Resources\BookResource;
use App\Http\Resources\InventoryBookResource;
use App\Models\Book;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventoryBook extends Controller
{
    public function create(int $idBook, InventoryBookCreateRequest $request): JsonResponse
    {
        $user = Auth::user();
        $book = Book::where('user_id', $user->id)->where('id', $idBook)->first();

        if (!$book) {
            throw new HttpResponseException(response()->json([
                'errors' => [
                    'message' => [
                        'not found'
                    ]
                ]
            ])->setStatusCode(404));
        }

        $data = $request->validated();
        $inventoryBook = new \App\Models\InventoryBook($data);
        $inventoryBook->book_id = $book->id;
        $inventoryBook->save();

        return (new InventoryBookResource($inventoryBook))->response()->setStatusCode(201);
    }

    public function get(int $idBook, int $idInventoryBook) : InventoryBookResource
    {
        $user = Auth::user();
        $book = Book::where('user_id', $user->id)->where('id', $idBook)->first();

        if (!$book) {
            throw new HttpResponseException(response()->json([
                'errors' => [
                    'message' => [
                        'not found'
                    ]
                ]
            ])->setStatusCode(404));
        }

        $inventoryBook = \App\Models\InventoryBook::where('book_id', $book->id)->where('id', $idInventoryBook)->first();
        
        if (!$inventoryBook) {
            throw new HttpResponseException(response()->json([
                'errors' => [
                    'message' => [
                        'not found'
                    ]
                ]
            ])->setStatusCode(404));
        }

        return new InventoryBookResource($inventoryBook);
    }
}

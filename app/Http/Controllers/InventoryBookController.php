<?php

namespace App\Http\Controllers;

use App\Http\Requests\InventoryBookCreateRequest;
use App\Http\Requests\InventoryBookUpdateRequest;
use App\Http\Resources\BookResource;
use App\Http\Resources\InventoryBookResource;
use App\Models\Book;
use App\Models\InventoryBook;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventoryBookController extends Controller
{
    private function getBook(User $user, int $idBook): Book
    {
        $book = Book::where('user_id', $user->id)->where('id', $idBook)->first();
        if (!$book) {
            throw new HttpResponseException(response()->json([
                'errors' => [
                    "message" => [
                        "not found"
                    ]
                ]
            ])->setStatusCode(404));
        }
        return $book;
    }

    private function getInventoryBook(Book $book, int $idInventoryBook): InventoryBook
    {
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
        return $inventoryBook;
    }

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
        $book = $this->getBook($user, $idBook);
        $idInventoryBook = $this->getInventoryBook($book, $idInventoryBook);

        return new InventoryBookResource($idInventoryBook);
    }

    public function update(int $idBook, int $idInventoryBook, InventoryBookUpdateRequest $request): InventoryBookResource
    {
        $user = Auth::user();
        $book = $this->getBook($user, $idBook);
        $inventoryBook = $this->getInventoryBook($book, $idInventoryBook);

        $data = $request->validated();
        $inventoryBook->fill($data);
        $inventoryBook->save();

        return new InventoryBookResource($inventoryBook);
    }

    public function delete(int $idBook, int $idInventoryBook): JsonResponse
    {
        $user = Auth::user();
        $book = $this->getBook($user, $idBook);
        $inventoryBook = $this->getInventoryBook($book, $idInventoryBook);

        $inventoryBook->delete();

        return response()->json([
            'data' => true
        ])->setStatusCode(200);
    }

    
    public function list(int $idBook): JsonResponse
    {
        $user = Auth::user();
        $book = $this->getBook($user, $idBook);

        $inventoryBook = InventoryBook::where('book_id', $book->id)->get();
        return (InventoryBookResource::collection($inventoryBook))->response()->setStatusCode(200);
    }
}

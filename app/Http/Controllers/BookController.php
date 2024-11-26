<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookCreateRequest;
use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    public function create(BookCreateRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = Auth::user();

        $book = new Book($data);
        $book->user_id = $user->id;
        $book->save();

        return (new BookResource($book))->response()->setStatusCode(201);
    }
}

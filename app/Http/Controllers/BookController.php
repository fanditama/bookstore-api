<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookCreateRequest;
use App\Http\Requests\BookUpdateRequest;
use App\Http\Resources\BookCollection;
use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Exceptions\HttpResponseException;
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

    public function get(int $id): BookResource
    {
        $user = Auth::user();
        $book = Book::where('id', $id)->where('user_id', $user->id)->first();

        if (!$book) {
            throw new HttpResponseException(response()->json([
                'errors' => [
                    "message" => [
                        "not found"
                    ]
                ]
            ])->setStatusCode(404));
        }

        return new BookResource($book);
    }

    public function update(int $id, BookUpdateRequest $request): BookResource
    {
        $user = Auth::user();
        $book = Book::where('id', $id)->where('user_id', $user->id)->first();

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
        $book->fill($data);
        $book->save();

        return new BookResource($book);
    }

    public function delete(int $id): jsonResponse
    {
        $user = Auth::user();
        $book = Book::where('id', $id)->where('user_id', $user->id)->first();

        if (!$book) {
            throw new HttpResponseException(response()->json([
                'errors' => [
                    'message' => [
                        'not found'
                    ]
                ]
            ])->setStatusCode(404));
        }

        $book->delete();
        return response()->json([
           'data' => true
        ])->setStatusCode(200);
    }

    public function search(Request $request): BookCollection 
    {
        $user = Auth::user();
        $page = $request->input('page', 1);
        $size = $request->input('size', 10);

        $books = Book::query()->where('user_id', $user->id);

        $books = $books->where(function (Builder $builder) use ($request) {
            $name = $request->input('name');

            // jika user input name
            if ($name) {
                $builder->where(function (Builder $builder) use ($name) {
                    $builder->orWhere('title', 'like', '%' . $name . '%');
                    $builder->orWhere('author', 'like', '%' . $name . '%');
                });
            }

            $publish = $request->input('publish');

            // jika user input publisher
            if ($publish) {
                $builder->where(function (Builder $builder) use ($publish) {
                    $builder->orWhere('publisher', 'like', '%' . $publish . '%');
                    $builder->orWhere('publication_year', 'like', '%' . $publish . '%');
                });
            }
            
            $genre = $request->input('genre');

            // jika user input genre
            if ($genre) {
                $builder->where(function (Builder $builder) use ($genre) {
                    $builder->orWhere('genre', 'like', '%' . $genre . '%');
                });
            }
        });

        $books = $books->paginate(perPage: $size, page: $page);

        return new BookCollection($books);
    }
}

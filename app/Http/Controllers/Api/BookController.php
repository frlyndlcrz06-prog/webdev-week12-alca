<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index(Request $request)
    {
        return BookResource::collection(
            $request->user()->books()->orderBy('id')->paginate(10)
        );
    }

    public function show(Request $request, string $book): BookResource
    {
        return new BookResource($this->findOwned($request, $book));
    }

    public function store(StoreBookRequest $request): JsonResponse
    {
        $book = $request->user()->books()->create($request->validated());

        return (new BookResource($book))->response()->setStatusCode(201);
    }

    public function update(UpdateBookRequest $request, string $book): BookResource
    {
        $model = $this->findOwned($request, $book);
        $model->update($request->validated());

        return new BookResource($model);
    }

    public function destroy(Request $request, string $book): JsonResponse
    {
        $this->findOwned($request, $book)->delete();

        return response()->json(['message' => 'Book deleted.']);
    }

    // Only searches the signed-in user's books, so another user's book "does not exist"
    private function findOwned(Request $request, string $id): Book
    {
        return $request->user()->books()->findOrFail($id);
    }
}
<?php

namespace App\Http\Controllers\Api;

use App\Book;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CreateBookRequest;
use App\Http\Requests\Api\IndexBooksRequest;
use App\Http\Requests\Api\UpdateBookRequest;
use App\Http\Resources\BookResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BookController extends Controller
{
    /**
     * List books
     *
     * @OA\Get(
     *     path="/api/book",
     *     operationId="book.index",
     *     tags={"BookController"},
     *     description="List books",
     *     @OA\Parameter(
     *          name="book_name",
     *          description="Name of book to find",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string",
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="authors[]",
     *          description="array of author ids",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="array",
     *              @OA\Items(
     *                  type="integer"
     *              )
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="categories[]",
     *          description="array of category ids",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="array",
     *              @OA\Items(
     *                  type="integer"
     *              )
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="page",
     *          description="page, default = 1",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="integer",
     *          )
     *      ),
     *     @OA\Response(
     *          response=200,
     *          description="Books list",
     *          @OA\JsonContent(),
     *       ),
     *     @OA\Response(
     *          response=401,
     *          description="unauthorized",
     *          @OA\JsonContent(),
     *       ),
     *     security={ {"bearer": {}} },
     * )
     * @param IndexBooksRequest $request
     * @return AnonymousResourceCollection
     */
    public function index(IndexBooksRequest $request)
    {
        $query = Book::query();
        if (!empty($request->book_name)) {
            $query->where('name', 'like', '%' . $request->book_name . '%');
        }

        if (!empty($request->authors)) {
            $authors = $request->authors;
            $query->whereHas('authors', function ($q) use ($authors) {
                 $q->whereIn('authors.id', $authors);
            });
        }

        if (!empty($request->categories)) {
            $categories = $request->categories;
            $query->whereHas('categories', function ($q) use ($categories) {
                 $q->whereIn('categories.id', $categories);
            });
        }
        $books = $query->paginate(15);

        return BookResource::collection($books);
    }

    /**
     * store new book
     *
     * @OA\Post(
     *     path="/api/book",
     *     operationId="book.store",
     *     tags={"BookController"},
     *     description="store book",
     *     @OA\Parameter(
     *          name="name",
     *          description="Book name",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string",
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="authors[]",
     *          description="array of author ids",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="array",
     *              @OA\Items(
     *                  type="integer"
     *              )
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="categories[]",
     *          description="array of category ids",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="array",
     *              @OA\Items(
     *                  type="integer"
     *              )
     *          )
     *      ),
     *     @OA\Response(
     *          response=201,
     *          description="Book created",
     *          @OA\JsonContent(),
     *       ),
     *     @OA\Response(
     *          response=401,
     *          description="unauthorized",
     *          @OA\JsonContent(),
     *       ),
     *     @OA\Response(
     *          response=422,
     *          description="validation error",
     *          @OA\JsonContent(),
     *       ),
     *     security={ {"bearer": {}} },
     * )
     * @param CreateBookRequest $request
     * @return BookResource
     */
    public function store(CreateBookRequest $request)
    {
        $book = Book::create($request->validated());

        $book->authors()->sync($request->get('authors', []));
        $book->categories()->sync($request->get('categories', []));
        $book->load('authors', 'categories');

        return new BookResource($book);
    }

    /**
     * Show book
     *
     * @OA\Get(
     *     path="/api/book/{bookId}",
     *     operationId="book.show",
     *     tags={"BookController"},
     *     description="show book",
     *     @OA\Parameter(
     *          name="bookId",
     *          description="Book id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string",
     *          )
     *      ),
     *     @OA\Response(
     *          response=200,
     *          description="Book returned",
     *          @OA\JsonContent(),
     *       ),
     *     @OA\Response(
     *          response=401,
     *          description="unauthorized",
     *          @OA\JsonContent(),
     *       ),
     *     @OA\Response(
     *          response=404,
     *          description="not found",
     *          @OA\JsonContent(),
     *       ),
     *     security={ {"bearer": {}} },
     * )
     * @param Book $book
     * @return BookResource
     */
    public function show(Book $book)
    {
        return new BookResource($book);
    }

    /**
     * store new book
     *
     * @OA\Put(
     *     path="/api/book/{bookId}",
     *     operationId="book.update",
     *     tags={"BookController"},
     *     description="update book",
     *     @OA\Parameter(
     *          name="bookId",
     *          description="Book id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string",
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="name",
     *          description="Book name",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string",
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="authors[]",
     *          description="array of author ids",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="array",
     *              @OA\Items(
     *                  type="integer"
     *              )
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="categories[]",
     *          description="array of category ids",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="array",
     *              @OA\Items(
     *                  type="integer"
     *              )
     *          )
     *      ),
     *     @OA\Response(
     *          response=201,
     *          description="Book created",
     *          @OA\JsonContent(),
     *       ),
     *     @OA\Response(
     *          response=401,
     *          description="unauthorized",
     *          @OA\JsonContent(),
     *       ),
     *     @OA\Response(
     *          response=422,
     *          description="validation error",
     *          @OA\JsonContent(),
     *       ),
     *     security={ {"bearer": {}} },
     * )
     * @param UpdateBookRequest $request
     * @param Book $book
     * @return BookResource
     */
    public function update(UpdateBookRequest $request, Book $book)
    {
        $book->update($request->validated());

        $book->authors()->sync($request->get('authors', []));
        $book->categories()->sync($request->get('categories', []));
        $book->load('authors', 'categories');

        return new BookResource($book);
    }

    /**
     * delete book
     *
     * @OA\Delete(
     *     path="/api/book/{bookId}",
     *     operationId="book.delete",
     *     tags={"BookController"},
     *     description="delete book",
     *     @OA\Parameter(
     *          name="bookId",
     *          description="Book id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string",
     *          )
     *      ),
     *     @OA\Response(
     *          response=204,
     *          description="Book deleted",
     *          @OA\JsonContent(),
     *       ),
     *     @OA\Response(
     *          response=401,
     *          description="unauthorized",
     *          @OA\JsonContent(),
     *       ),
     *     security={ {"bearer": {}} },
     * )
     * @param Book $book
     * @return JsonResponse
     */
    public function destroy(Book $book)
    {
        try{
            $book->delete();
        } catch (\Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 500);
        }

        return response()->json(['message' => 'successfully deleted'], 204);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Author;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CreateAuthorRequest;
use App\Http\Resources\AuthorResource;
use Illuminate\Http\JsonResponse;

class AuthorController extends Controller
{
    /**
     * List authors
     *
     * @OA\Get(
     *     path="/api/author",
     *     operationId="author.index",
     *     tags={"AuthorController"},
     *     description="List authors",
     *     @OA\Response(
     *          response=200,
     *          description="Authors list",
     *          @OA\JsonContent(),
     *       ),
     *     @OA\Response(
     *          response=401,
     *          description="unauthorized",
     *          @OA\JsonContent(),
     *       ),
     *     security={ {"bearer": {}} },
     * )
     */
    public function index()
    {
        $authors = Author::query()->paginate(15);

        return AuthorResource::collection($authors);
    }

    /**
     * store new author
     *
     * @OA\Post(
     *     path="/api/author",
     *     operationId="author.store",
     *     tags={"AuthorController"},
     *     description="store author",
     *     @OA\Parameter(
     *          name="name",
     *          description="Author name",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string",
     *          )
     *      ),
     *     @OA\Response(
     *          response=201,
     *          description="Author created",
     *          @OA\JsonContent(),
     *       ),
     *     @OA\Response(
     *          response=401,
     *          description="unauthorized",
     *          @OA\JsonContent(),
     *       ),
     *     security={ {"bearer": {}} },
     * )
     * @param CreateAuthorRequest $request
     * @return AuthorResource
     */
    public function store(CreateAuthorRequest $request)
    {
        $author = Author::create($request->validated());

        return new AuthorResource($author);
    }

    /**
     * show author
     *
     * @OA\Get(
     *     path="/api/author/{authorId}",
     *     operationId="author.show",
     *     tags={"AuthorController"},
     *     description="show author",
     *     @OA\Parameter(
     *          name="authorId",
     *          description="Author id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string",
     *          )
     *      ),
     *     @OA\Response(
     *          response=200,
     *          description="Author info returned",
     *          @OA\JsonContent(),
     *       ),
     *     @OA\Response(
     *          response=401,
     *          description="unauthorized",
     *          @OA\JsonContent(),
     *       ),
     *     security={ {"bearer": {}} },
     * )
     * @param Author $author
     * @return AuthorResource
     */
    public function show(Author $author)
    {
        return new AuthorResource($author);
    }

    /**
     * delete author
     *
     * @OA\Delete(
     *     path="/api/author/{authorId}",
     *     operationId="author.delete",
     *     tags={"AuthorController"},
     *     description="delete author",
     *     @OA\Parameter(
     *          name="authorId",
     *          description="Author id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string",
     *          )
     *      ),
     *     @OA\Response(
     *          response=204,
     *          description="Author deleted",
     *          @OA\JsonContent(),
     *       ),
     *     @OA\Response(
     *          response=401,
     *          description="unauthorized",
     *          @OA\JsonContent(),
     *       ),
     *     security={ {"bearer": {}} },
     * )
     * @param Author $author
     * @return JsonResponse
     */
    public function destroy(Author $author)
    {
        try{
            $author->delete();
        } catch (\Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 500);
        }

        return response()->json(['message' => 'successfully deleted'], 204);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Category;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CreateCategoryRequest;
use App\Http\Resources\CategoryResource;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    /**
     * List categories
     *
     * @OA\Get(
     *     path="/api/category",
     *     operationId="category.index",
     *     tags={"CategoryController"},
     *     description="List categories",
     *     @OA\Response(
     *          response=200,
     *          description="Categories list",
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
        $categories = Category::query()->paginate(15);

        return CategoryResource::collection($categories);
    }

    /**
     * store new category
     *
     * @OA\Post(
     *     path="/api/category",
     *     operationId="category.store",
     *     tags={"CategoryController"},
     *     description="store category",
     *     @OA\Parameter(
     *          name="name",
     *          description="Category name",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string",
     *          )
     *      ),
     *     @OA\Response(
     *          response=201,
     *          description="Category created",
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
     * @param CreateCategoryRequest $request
     * @return CategoryResource
     */
    public function store(CreateCategoryRequest $request)
    {
        $category = Category::create($request->validated());

        return new CategoryResource($category);
    }

    /**
     * Show category
     *
     * @OA\Get(
     *     path="/api/category/{categoryId}",
     *     operationId="category.show",
     *     tags={"CategoryController"},
     *     description="show category",
     *     @OA\Parameter(
     *          name="categoryId",
     *          description="Category id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string",
     *          )
     *      ),
     *     @OA\Response(
     *          response=200,
     *          description="Category returned",
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
     * @param Category $category
     * @return CategoryResource
     */
    public function show(Category $category)
    {
        return new CategoryResource($category);
    }

    /**
     * delete category
     *
     * @OA\Delete(
     *     path="/api/category/{categoryId}",
     *     operationId="category.delete",
     *     tags={"CategoryController"},
     *     description="delete category",
     *     @OA\Parameter(
     *          name="categoryId",
     *          description="Category id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string",
     *          )
     *      ),
     *     @OA\Response(
     *          response=204,
     *          description="Category deleted",
     *          @OA\JsonContent(),
     *       ),
     *     @OA\Response(
     *          response=401,
     *          description="unauthorized",
     *          @OA\JsonContent(),
     *       ),
     *     security={ {"bearer": {}} },
     * )
     * @param Category $category
     * @return JsonResponse
     */
    public function destroy(Category $category)
    {
        try{
            $category->delete();
        } catch (\Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 500);
        }

        return response()->json(['message' => 'successfully deleted'], 204);
    }
}

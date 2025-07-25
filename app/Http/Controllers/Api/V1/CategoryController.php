<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\MoveCategoryRequest;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Resources\Category\CategoryCollection;
use App\Http\Resources\Category\CategoryResource;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class CategoryController extends Controller
{
    public function __construct(protected CategoryService $categoryService)
    {
    }

    public function store(StoreCategoryRequest $request): CategoryResource|JsonResponse
    {
        try {
            $category = $this->categoryService->createCategory($request->validated());
            return new CategoryResource($category);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation Error.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to create category: ' . $e->getMessage()], 500);
        }
    }


    public function children(int $categoryId): CategoryCollection|JsonResponse
    {
        try {
            $category = $this->categoryService->getCategoryChildren($categoryId);
            // The children relationship is loaded via the Category model and HasRecursiveRelationships.
            return new CategoryCollection($category->children);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function descendants(int $categoryId): CategoryCollection|JsonResponse
    {
        try {
            $category = $this->categoryService->getCategoryDescendants($categoryId);
            // The descendants relationship is loaded via the Category model and HasRecursiveRelationships.
            return new CategoryCollection($category->descendants);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function ancestors(int $categoryId): CategoryCollection|JsonResponse
    {
        try {
            $category = $this->categoryService->getCategoryAncestors($categoryId);
            // The ancestors relationship is loaded via the Category model and HasRecursiveRelationships.
            return new CategoryCollection($category->ancestors);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    /**
     * Move a category (and subtree) to a new parent.
     */
    public function move(MoveCategoryRequest $request, int $categoryId): CategoryResource|JsonResponse
    {
        try {
            $newParentId = $request->validated('new_parent_id');
            $category = $this->categoryService->getCategoryChildren($categoryId);
            //TODO: check not existing category
            $updatedCategory = $this->categoryService->moveCategory($category, $newParentId);
            return new CategoryResource($updatedCategory);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation Error.',
                'errors' => $e->errors()
            ], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to move category: ' . $e->getMessage()], 500);
        }
    }
}

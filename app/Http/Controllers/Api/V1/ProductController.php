<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Resources\Product\ProductCollection;
use App\Http\Resources\Product\ProductResource;
use App\Http\Resources\Product\ProductVersionResource;
use App\Models\Product;
use App\Services\Contracts\ProductServiceInterface;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;

    public function __construct(protected ProductServiceInterface $productService)
    {
    }

    public function index(Request $request): ProductCollection
    {
        $user = $request->user();
        $sortBy = $request->query('sort_by', 'availability');
        $sortOrder = $request->query('sort_order', 'asc');

        $this->authorize('viewAny', Product::class);

        $products = $this->productService->getAllProductsForUser($user->id, $sortBy, $sortOrder);

        return new ProductCollection($products);
    }

    public function store(StoreProductRequest $request): ProductResource
    {
        $user = $request->user();

        $this->authorize('create', Product::class);

        $product = $this->productService->createProduct($request->validated(), $user->id);

        return new ProductResource($product->load('category', 'attributes'));
    }

    public function show(int $productId): ProductResource|JsonResponse
    {
        try {
            $product = $this->productService->getProductForUser(
                productId: $productId,
                userId: auth()->id());

            $this->authorize('view', $product);
            return new ProductResource($product->load('category', 'attributes'));
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        } catch (AuthorizationException $e) {
            return response()->json(['message' => $e->getMessage()], 403);
        }
    }

    public function update(UpdateProductRequest $request, int $productId): ProductResource|JsonResponse
    {
        try {
            $product = $this->productService->getProductForUser(
                productId: $productId,
                userId: auth()->id()
            );

            $this->authorize('update', $product);

            $product = $this->productService->updateProduct($product, $request->validated(), auth()->id());

            return new ProductResource($product->load('category', 'attributes'));
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        } catch (AuthorizationException $e) {
            return response()->json(['message' => $e->getMessage()], 403);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 503);
        }
    }

    public function history(int $productId): ResourceCollection|JsonResponse
    {
        try {
            $product = $this->productService->getProductForUser(
                productId: $productId,
                userId: auth()->id()
            );
            $this->authorize('view', $product);
            $versions = $this->productService->getProductHistory($product);
            return ProductVersionResource::collection($versions);
        } catch (AuthorizationException $e) {
            return response()->json(['message' => $e->getMessage()], 403);
        }
    }

    public function showVersion(int $productId, int $versionId): ProductVersionResource|JsonResponse
    {
        try {
            $product = $this->productService->getProductForUser(
                productId: $productId,
                userId: auth()->id()
            );

            $this->authorize('view', $product);
            $version = $this->productService->getProductVersionDetail($product, $versionId);
            return new ProductVersionResource($version);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        } catch (AuthorizationException $e) {
            return response()->json(['message' => $e->getMessage()], 403);
        }
    }
}

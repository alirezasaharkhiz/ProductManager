<?php

namespace App\Services;

//use App\Mail\ProductUpdatedNotification;
use App\Models\Product;
use App\Models\ProductVersion;
use App\Repositories\Contracts\AttributeRepositoryInterface;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Services\Contracts\ProductServiceInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Arr;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductService implements ProductServiceInterface // Implement the interface
{
    public function __construct(
        protected ProductRepositoryInterface  $productRepository,
        protected CategoryRepositoryInterface  $categoryRepository,
        protected AttributeRepositoryInterface $attributeRepository
    ) {
    }

    /**
     * Get all products for a specific user.
     */
    public function getAllProductsForUser(int $userId, string $sortBy = 'availability', string $sortOrder = 'asc'): LengthAwarePaginator
    {
        return $this->productRepository->getProductsForUser($userId, $sortBy, $sortOrder);
    }

    /**
     * Get a single product for a specific user.
     */
    public function getProductForUser(int $productId, int $userId): ?Product
    {
        $product = $this->productRepository->findForUser($productId, $userId);

        if (!$product) {
            throw new ModelNotFoundException('Product not found or you do not own it.');
        }

        return $product;
    }

    /**
     * Create a new product.
     */
    public function createProduct(array $data, int $userId): Product
    {
        $category = $this->categoryRepository->find($data['category_id']);
        if (!$category) {
            throw ValidationException::withMessages(['category_id' => 'The selected category does not exist.']);
        }

        // Separate product data from attributes data
        $productData = Arr::except($data, ['attributes']);
        $productData['user_id'] = $userId;

        return DB::transaction(function () use ($productData, $data) {
            $product = $this->productRepository->create($productData);

            if (isset($data['attributes']) && is_array($data['attributes'])) {
                $this->syncProductAttributes($product, $data['attributes']);
            }

            return $product;
        });
    }

    /**
     * Update an existing product.
     */
    public function updateProduct(Product $product, array $data, int $userId): Product
    {
        // (policy will also check this, but good to have service check)
        if ($product->user_id !== $userId) {
            throw ValidationException::withMessages(['product' => 'You do not have permission to update this product.']);
        }

        if (isset($data['category_id'])) {
            $category = $this->categoryRepository->find($data['category_id']);
            if (!$category) {
                throw ValidationException::withMessages(['category_id' => 'The selected category does not exist.']);
            }
        }

        // Separate product data from attributes data
        $productData = Arr::except($data, ['attributes']);

        return DB::transaction(function () use ($product, $productData, $data) {
            $this->productRepository->update($product, $productData);

            if (isset($data['attributes']) && is_array($data['attributes'])) {
                $this->syncProductAttributes($product, $data['attributes']);
            }

            // Reload product to get updated attributes
            $product->load('attributes');

            return $product;
        });
    }

    /**
     * Sync product attributes.
     */
    protected function syncProductAttributes(Product $product, array $attributesData): void
    {
        $validAttributeIds = $this->attributeRepository->getAllAttributeIds()->pluck('id')->toArray();
        $attributesToSync = [];

        foreach ($attributesData as $attr) {
            if (!isset($attr['attribute_id']) || !isset($attr['value'])) {
                throw ValidationException::withMessages(['attributes' => 'Each attribute must have an "attribute_id" and a "value".']);
            }
            if (!in_array($attr['attribute_id'], $validAttributeIds)) {
                throw ValidationException::withMessages(['attributes' => 'Attribute ID ' . $attr['attribute_id'] . ' is not valid.']);
            }
            $attributesToSync[] = $attr;
        }

        $this->productRepository->syncAttributes($product, $attributesToSync);
    }

    /**
     * Get the version history for a product.
     */
    public function getProductHistory(Product $product): LengthAwarePaginator
    {
        return $this->productRepository->getProductVersions($product)->paginate(15);
    }

    /**
     * Get details for a specific product version.
     */
    public function getProductVersionDetail(Product $product, int $versionId): ?ProductVersion
    {
        $version = $this->productRepository->findProductVersion($product, $versionId);

        if (!$version) {
            throw new ModelNotFoundException('Product version not found for this product.');
        }

        return $version;
    }
}

<?php

namespace App\Repositories\Contracts;

use App\Models\Product;
use App\Models\ProductVersion;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Relations\HasMany;

interface ProductRepositoryInterface
{
    /**
     * Get products for a specific user with optional sorting.
     *
     * @param int $userId
     * @param string $sortBy
     * @param string $sortOrder
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getProductsForUser(int $userId, string $sortBy, string $sortOrder, int $perPage = 15): LengthAwarePaginator;

    /**
     * Find a product by ID for a specific user.
     *
     * @param int $productId
     * @param int $userId
     * @return Product|null
     */
    public function findForUser(int $productId, int $userId): ?Product;

    /**
     * Create a new product.
     *
     * @param array $data
     * @return Product
     */
    public function create(array $data): Product;

    /**
     * Update an existing product.
     *
     * @param Product $product
     * @param array $data
     * @return bool
     */
    public function update(Product $product, array $data): bool;

    /**
     * Sync attributes for a product.
     *
     * @param Product $product
     * @param array $attributesData
     * @return void
     */
    public function syncAttributes(Product $product, array $attributesData): void;

    /**
     * Get all versions for a given product.
     *
     * @param Product $product
     * @return HasMany
     */
    public function getProductVersions(Product $product): HasMany;

    /**
     * Find a specific version for a given product.
     *
     * @param Product $product
     * @param int $versionId
     * @return ProductVersion|null
     */
    public function findProductVersion(Product $product, int $versionId): ?ProductVersion;
}

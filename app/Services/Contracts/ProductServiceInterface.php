<?php

namespace App\Services\Contracts;

use App\Models\Product;
use App\Models\ProductVersion;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;

interface ProductServiceInterface
{
    /**
     * Get all products for a specific user.
     *
     * @param int $userId
     * @param string $sortBy
     * @param string $sortOrder
     * @return LengthAwarePaginator
     */
    public function getAllProductsForUser(int $userId, string $sortBy, string $sortOrder): LengthAwarePaginator;

    /**
     * Get a single product for a specific user.
     *
     * @param int $productId
     * @param int $userId
     * @return Product|null
     * @throws ModelNotFoundException
     */
    public function getProductForUser(int $productId, int $userId): ?Product;

    /**
     * Create a new product.
     *
     * @param array $data
     * @param int $userId
     * @return Product
     * @throws ValidationException
     */
    public function createProduct(array $data, int $userId): Product;

    /**
     * Update an existing product.
     *
     * @param Product $product
     * @param array $data
     * @param int $userId
     * @return Product
     * @throws ValidationException
     */
    public function updateProduct(Product $product, array $data, int $userId): Product;

    /**
     * Get the version history for a product.
     *
     * @param Product $product
     * @return LengthAwarePaginator
     */
    public function getProductHistory(Product $product): LengthAwarePaginator;

    /**
     * Get details for a specific product version.
     *
     * @param Product $product
     * @param int $versionId
     * @return ProductVersion|null
     * @throws ModelNotFoundException
     */
    public function getProductVersionDetail(Product $product, int $versionId): ?ProductVersion;
}

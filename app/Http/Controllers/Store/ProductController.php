<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Http\Requests\Store\ProductIndexRequest;
use App\Http\Requests\Store\RelatedProductsRequest;
use App\Http\Resources\Store\ProductResource;
use App\Models\Store\StoreProduct;
use Exception;
use Throwable;

class ProductController extends Controller
{
    /**
     * List products with pagination, search, and filter.
     */
    public function index(ProductIndexRequest $request)
    {
        $search = $request->search;
        $category = $request->category;
        $tag = $request->tag;
        $minPrice = $request->min_price;
        $maxPrice = $request->max_price;
        $stockStatus = $request->stock_status;
        $perPage = $request->per_page;

        $products = StoreProduct::query()
            ->with(['category', 'tags'])
            ->when($search, function ($query, $searchKey) {
                $query->where('name', 'like', "%{$searchKey}%")
                    ->orWhere('description', 'like', "%{$searchKey}%");
            })
            ->when($category, function ($query, $categoryValue) {
                // Support both slug and UUID
                $query->whereHas('category', function ($categoryQuery) use ($categoryValue) {
                    $categoryQuery->where('slug', $categoryValue)
                        ->orWhere('id', $categoryValue);
                });
            })
            ->when($tag, function ($query, $tagValues) {
                // Support both single value and array, both slug and UUID
                if (is_string($tagValues)) {
                    $tagValues = [$tagValues];
                }
                $query->whereHas('tags', function ($tagsQuery) use ($tagValues) {
                    $tagsQuery->whereIn('slug', $tagValues)
                        ->orWhereIn('id', $tagValues);
                });
            })
            ->when($minPrice, function ($query, $minPrice) {
                $query->where('price', '>=', $minPrice);
            })
            ->when($maxPrice, function ($query, $maxPrice) {
                $query->where('price', '<=', $maxPrice);
            })
            ->when($stockStatus, function ($query, $stockStatus) {
                $query->where('stock_status', $stockStatus);
            })
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return $this->respondWithPagination($products, ProductResource::class, [
            'pagination' => [
                'page' => $products->currentPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
                'total_pages' => $products->lastPage(),
            ],
        ]);
    }

    /**
     * Get single product detail.
     */
    public function detail(string $id)
    {
        try {
            $product = StoreProduct::query()
                ->with(['category', 'tags'])
                ->where('is_active', true)
                ->find($id);

            if (! $product) {
                throw new Exception('PRODUCT_NOT_FOUND', 404);
            }

            return $this->respondWithItem(ProductResource::make($product));
        } catch (Throwable $th) {
            return $this->respondWithError($th->getMessage(), $th->getCode());
        }
    }

    /**
     * Get related products based on category and tags.
     */
    public function relatedProducts(RelatedProductsRequest $request, string $id)
    {
        try {
            $product = StoreProduct::query()
                ->with(['category', 'tags'])
                ->where('is_active', true)
                ->find($id);

            if (! $product) {
                throw new Exception('PRODUCT_NOT_FOUND', 404);
            }

            $limit = $request->limit;
            $page = $request->page;

            // Get related products: same category or same tags, exclude current product
            $relatedProducts = StoreProduct::query()
                ->with(['category', 'tags'])
                ->where('id', '!=', $id)
                ->where('is_active', true)
                ->where(function ($query) use ($product) {
                    // Same category
                    $query->where('category_id', $product->category_id)
                        // Or has same tags
                        ->orWhereHas('tags', function ($tagsQuery) use ($product) {
                            $tagsQuery->whereIn('id', $product->tags->pluck('id')->toArray());
                        });
                })
                ->orderBy('created_at', 'desc')
                ->paginate($limit, ['*'], 'page', $page);

            return $this->respondWithPagination($relatedProducts, ProductResource::class, [
                'pagination' => [
                    'page' => $relatedProducts->currentPage(),
                    'limit' => $limit,
                    'total' => $relatedProducts->total(),
                    'total_pages' => $relatedProducts->lastPage(),
                ],
            ]);
        } catch (Throwable $th) {
            return $this->respondWithError($th->getMessage(), $th->getCode());
        }
    }
}

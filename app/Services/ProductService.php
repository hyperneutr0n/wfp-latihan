<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductService
{
    /**
     * Get top 5 most bought products by total quantity sold
     * 
     * @return array
     */
    public function getTopMostBoughtProducts()
    {
        return DB::select("
            SELECT 
                p.id,
                p.name,
                p.price,
                SUM(ip.quantity) as total_quantity_sold
            FROM products p
            INNER JOIN invoice_product ip ON p.id = ip.product_id
            GROUP BY p.id, p.name, p.price
            ORDER BY total_quantity_sold DESC
            LIMIT 5
        ");
    }

    /**
     * Get product with the smallest average sales quantity
     * 
     * @return object|null
     */
    public function getProductWithSmallestAverageSales()
    {
        $result = DB::select("
            SELECT 
                p.id,
                p.name,
                p.price,
                AVG(ip.quantity) as avg_sales_quantity
            FROM products p
            INNER JOIN invoice_product ip ON p.id = ip.product_id
            GROUP BY p.id, p.name, p.price
            ORDER BY avg_sales_quantity ASC
            LIMIT 1
        ");
        
        return $result ? $result[0] : null;
    }
}

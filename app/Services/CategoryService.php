<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class CategoryService
{
    /**
     * Get top 1 most bought category by total quantity sold
     * 
     * @return object|null
     */
    public function getTopMostBoughtCategory()
    {
        $result = DB::select("
            SELECT 
                c.id,
                c.name,
                SUM(ip.quantity) as total_quantity_sold
            FROM categories c
            INNER JOIN products p ON c.id = p.category_id
            INNER JOIN invoice_product ip ON p.id = ip.product_id
            GROUP BY c.id, c.name
            ORDER BY total_quantity_sold DESC
            LIMIT 1
        ");
        
        return $result ? $result[0] : null;
    }
}

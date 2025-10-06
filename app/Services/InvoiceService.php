<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class InvoiceService
{
    /**
     * Get average total purchases from the last 3 months
     * 
     * @return float
     */
    public function getAverageTotalPurchasesLast3Months()
    {
        $result = DB::select("
            SELECT AVG(invoice_totals.total) as avg_total
            FROM (
                SELECT 
                    i.id,
                    SUM(ip.quantity * ip.price) as total
                FROM invoices i
                INNER JOIN invoice_product ip ON i.id = ip.invoice_id
                WHERE i.date >= DATE_SUB(NOW(), INTERVAL 3 MONTH)
                GROUP BY i.id
            ) invoice_totals
        ");
        
        return $result[0]->avg_total ?? 0;
    }
}

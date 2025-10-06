<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class CustomerService
{
    /**
     * Get top 3 customers with highest total purchase amount
     * 
     * @return array
     */
    public function getTopSpenders()
    {
        return DB::select("
            SELECT 
                c.id,
                c.name,
                c.sex,
                c.city,
                SUM(ip.quantity * ip.price) as total_spent
            FROM customers c
            INNER JOIN invoices i ON c.id = i.customer_id
            INNER JOIN invoice_product ip ON i.id = ip.invoice_id
            GROUP BY c.id, c.name, c.sex, c.city
            ORDER BY total_spent DESC
            LIMIT 3
        ");
    }

    /**
     * Get top buyer with highest total number of purchased items
     * 
     * @return object|null
     */
    public function getTopBuyerByQuantity()
    {
        $result = DB::select("
            SELECT 
                c.id,
                c.name,
                c.sex,
                c.city,
                SUM(ip.quantity) as total_items_bought
            FROM customers c
            INNER JOIN invoices i ON c.id = i.customer_id
            INNER JOIN invoice_product ip ON i.id = ip.invoice_id
            GROUP BY c.id, c.name, c.sex, c.city
            ORDER BY total_items_bought DESC
            LIMIT 1
        ");
        
        return $result ? $result[0] : null;
    }

    /**
     * Get names of customers who have invoices with total purchases greater than 
     * the average total purchases in the month of the invoice
     * 
     * @return array
     */
    public function getCustomersAboveMonthlyAverage()
    {
        return DB::select("
            SELECT DISTINCT c.name
            FROM customers c
            INNER JOIN invoices i ON c.id = i.customer_id
            INNER JOIN (
                SELECT 
                    i1.id as invoice_id,
                    SUM(ip1.quantity * ip1.price) as invoice_total
                FROM invoices i1
                INNER JOIN invoice_product ip1 ON i1.id = ip1.invoice_id
                GROUP BY i1.id
            ) invoice_totals ON i.id = invoice_totals.invoice_id
            WHERE invoice_totals.invoice_total > (
                SELECT AVG(monthly_totals.avg_invoice_total)
                FROM (
                    SELECT AVG(ip2.quantity * ip2.price) as avg_invoice_total
                    FROM invoices i2
                    INNER JOIN invoice_product ip2 ON i2.id = ip2.invoice_id
                    WHERE YEAR(i2.date) = YEAR(i.date) 
                      AND MONTH(i2.date) = MONTH(i.date)
                    GROUP BY i2.id
                ) monthly_totals
            )
        ");
    }

    /**
     * Get the largest total purchase from each female customer
     * 
     * @return array
     */
    public function getLargestPurchaseByFemaleCustomers()
    {
        return DB::select("
            SELECT 
                c.id,
                c.name,
                MAX(invoice_totals.total) as largest_purchase
            FROM customers c
            INNER JOIN (
                SELECT 
                    i.customer_id,
                    SUM(ip.quantity * ip.price) as total
                FROM invoices i
                INNER JOIN invoice_product ip ON i.id = ip.invoice_id
                GROUP BY i.id, i.customer_id
            ) invoice_totals ON c.id = invoice_totals.customer_id
            WHERE c.sex = 'female'
            GROUP BY c.id, c.name
            ORDER BY largest_purchase DESC
        ");
    }
}

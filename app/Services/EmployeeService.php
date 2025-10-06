<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class EmployeeService
{
    /**
     * Get employee who has served sales with the largest average every month
     * 
     * @return array
     */
    public function getEmployeeWithLargestMonthlyAverage()
    {
        return DB::select("
            SELECT 
                e.id,
                e.name,
                e.role,
                YEAR(i.date) as year,
                MONTH(i.date) as month,
                AVG(invoice_totals.total) as avg_monthly_sales
            FROM employees e
            INNER JOIN invoices i ON e.id = i.employee_id
            INNER JOIN (
                SELECT 
                    i1.id,
                    SUM(ip1.quantity * ip1.price) as total
                FROM invoices i1
                INNER JOIN invoice_product ip1 ON i1.id = ip1.invoice_id
                GROUP BY i1.id
            ) invoice_totals ON i.id = invoice_totals.id
            GROUP BY e.id, e.name, e.role, YEAR(i.date), MONTH(i.date)
            ORDER BY avg_monthly_sales DESC
        ");
    }

    /**
     * Get list of employees eligible for annual bonus
     * Annual bonus = (10% x highest monthly sales) + (5% x sales above yearly average)
     * 
     * @param int $year
     * @return array
     */
    public function getEmployeesEligibleForAnnualBonus($year = null)
    {
        if (!$year) {
            $year = date('Y');
        }
        
        $yearlyAverage = DB::select("
            SELECT AVG(ip.quantity * ip.price) as yearly_avg
            FROM invoices i
            INNER JOIN invoice_product ip ON i.id = ip.invoice_id
            WHERE YEAR(i.date) = ?
        ", [$year]);
        
        $avgSales = $yearlyAverage[0]->yearly_avg ?? 0;
        
        return DB::select("
            SELECT 
                e.id,
                e.name,
                e.role,
                MAX(monthly_sales.monthly_total) as highest_monthly_sales,
                SUM(monthly_sales.monthly_total) as total_yearly_sales,
                ? as yearly_average,
                (MAX(monthly_sales.monthly_total) * 0.10) as base_bonus,
                CASE 
                    WHEN SUM(monthly_sales.monthly_total) > ? 
                    THEN (SUM(monthly_sales.monthly_total) - ?) * 0.05 
                    ELSE 0 
                END as excess_bonus,
                (
                    (MAX(monthly_sales.monthly_total) * 0.10) + 
                    CASE 
                        WHEN SUM(monthly_sales.monthly_total) > ? 
                        THEN (SUM(monthly_sales.monthly_total) - ?) * 0.05 
                        ELSE 0 
                    END
                ) as total_annual_bonus
            FROM employees e
            INNER JOIN (
                SELECT 
                    i.employee_id,
                    YEAR(i.date) as year,
                    MONTH(i.date) as month,
                    SUM(ip.quantity * ip.price) as monthly_total
                FROM invoices i
                INNER JOIN invoice_product ip ON i.id = ip.invoice_id
                WHERE YEAR(i.date) = ?
                GROUP BY i.employee_id, YEAR(i.date), MONTH(i.date)
            ) monthly_sales ON e.id = monthly_sales.employee_id
            GROUP BY e.id, e.name, e.role
            HAVING total_annual_bonus > 0
            ORDER BY total_annual_bonus DESC
        ", [$avgSales, $avgSales, $avgSales, $avgSales, $avgSales, $year]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\ExpensesCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExpensesCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $expenseCategories = [
          'Advertising',
          'Internship Allowance',
          'Other Allowance',
          'Commission',
          'Event / Social',
          'F&B (For Customer)',
          'Printing / Stationery',
          'Office Maintenance / Repair',
          'Office Refreshment',
          'Others',
          'Petrol / Toll',
          'Refund',
          'Sponsor / Donation',
          'Utilities',
          'Travel',
          'Employee Welfare',
          'Pray / CNY / Festival',
          'Vehicle Maintenance',
          'Contractor',
          'Employee',
          'Advanced Payment',
          'Employee Bonus',
          'F&B (For Employee)',
          'Gift (For Employee)',
          'Gift (For Customer)',
          'Medical Supplies',
          'Office Equipment',
          'Furniture & Fittings',
          'Transport Allowance'
      ];

      foreach ($expenseCategories as $categoryName) {
          
          $existingCategory = ExpensesCategory::where('expenses_category_name', $categoryName)->first();

          if (!$existingCategory) {
            ExpensesCategory::create(['expenses_category_name' => $categoryName]);
          }
      }
    }
}

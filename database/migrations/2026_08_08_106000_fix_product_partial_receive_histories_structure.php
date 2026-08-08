<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, drop the existing table if it has issues
        // Check if columns exist and modify them
        if (Schema::hasTable('product_partial_receive_histories')) {
            // Modify existing BIGINT columns to INT UNSIGNED
            DB::statement('ALTER TABLE product_partial_receive_histories MODIFY transaction_id INT UNSIGNED NULL');
            DB::statement('ALTER TABLE product_partial_receive_histories MODIFY product_id INT UNSIGNED NULL');
            DB::statement('ALTER TABLE product_partial_receive_histories MODIFY purchase_line_id INT UNSIGNED NULL');
            DB::statement('ALTER TABLE product_partial_receive_histories MODIFY user_id INT UNSIGNED NULL');

            // Add foreign keys if they don't exist yet
            $this->addForeignKeyIfNotExists('product_partial_receive_histories', 'transaction_id', 'transactions');
            $this->addForeignKeyIfNotExists('product_partial_receive_histories', 'product_id', 'products');
            $this->addForeignKeyIfNotExists('product_partial_receive_histories', 'purchase_line_id', 'purchase_lines');
            $this->addForeignKeyIfNotExists('product_partial_receive_histories', 'user_id', 'users');
        }
    }

    /**
     * Add foreign key if it doesn't exist
     */
    private function addForeignKeyIfNotExists($table, $column, $foreign_table)
    {
        $result = DB::select("
            SELECT CONSTRAINT_NAME
            FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
            WHERE TABLE_NAME = ? AND COLUMN_NAME = ? AND REFERENCED_TABLE_NAME IS NOT NULL
        ", [$table, $column]);

        if (empty($result)) {
            $constraint_name = "{$table}_{$column}_foreign";
            try {
                DB::statement("
                    ALTER TABLE {$table}
                    ADD CONSTRAINT {$constraint_name}
                    FOREIGN KEY ({$column}) REFERENCES {$foreign_table}(id) ON DELETE CASCADE
                ");
            } catch (\Exception $e) {
                // Constraint might already exist or other error
                \Log::info("Foreign key constraint creation info: " . $e->getMessage());
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Foreign keys will be auto-dropped when table is dropped
        // This migration only modifies structure, doesn't drop table
    }
};

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
        // For MySQL, we need to use raw statement to modify enum
        // This approach works for both adding and removing enum values
        DB::statement("ALTER TABLE transactions MODIFY status ENUM('received', 'pending', 'ordered', 'draft', 'final', 'in_transit', 'completed', 'partial_received')");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE transactions MODIFY status ENUM('received', 'pending', 'ordered', 'draft', 'final', 'in_transit', 'completed')");
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('expense_items', function (Blueprint $table) {
            $table->string('vat_rate', 10)->change(); // Zmieniamy na string
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('expense_items', function (Blueprint $table) {
            $table->enum('vat_rate', ['ZW', '23', '8', '7', '5', '0', 'NP', 'Inne'])->change(); // Przywrócenie enuma
        });
    }
};

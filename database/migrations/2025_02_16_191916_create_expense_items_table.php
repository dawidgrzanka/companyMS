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
        Schema::create('expense_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('expense_id')->constrained()->onDelete('cascade'); // Połączenie z wydatkiem
            $table->string('name'); // Nazwa produktu/usługi
            $table->decimal('quantity'); // Ilość
            $table->string('unit'); // Jednostka (np. szt., m², kg)
            $table->decimal('net_price', 10, 2); // Cena netto
            $table->enum('vat_rate', ['ZW', '23', '8', '7', '5', '0', 'NP', 'Inne']); // VAT %
            $table->decimal('net_value', 10, 2); // Wartość netto (ilość * cena netto)
            $table->decimal('gross_value', 10, 2); // Wartość brutto (wartość netto + VAT)

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expense_items');
    }
};

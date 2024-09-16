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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name'); //Nazwa produktu
            $table->decimal('purchase_price_netto', 8, 2); //Cena netto zakupu
            $table->decimal('purchase_price_brutto', 8, 2); //Cena brutto zakupu
            $table->decimal('sale_price_netto', 8, 2); //Cena netto sprzedaży
            $table->decimal('sale_price_brutto', 8, 2); //Cena brutto sprzedaży
            $table->decimal('margin', 5, 2); //Marża
            $table->integer('stock'); // ilość w magazynie
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

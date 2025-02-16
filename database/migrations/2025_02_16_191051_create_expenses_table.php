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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['Faktura', 'Faktura Proforma', 'Własny dokument nieksięgowy', 'Paragon', 'Paragon z NIP']); //Rodzaj dokumentu sprzedaży
            $table->string('number')->uniqe(); //Numer dokumentu
            $table->date('issue_date'); //Data wystawienia
            $table->string('issue_place'); //Miejsce wystawienia
            $table->date('sale_date'); //Data sprzedaży

            //Dane sprzedawcy (firma / osoba prywatna)
            $table->enum('seller_type', ['Firma', 'Osoba prywatna']);
            $table->string('seller_name');
            $table->string('seller_nip')->nullable();
            $table->string('seller_street');
            $table->string('seller_postal_code');
            $table->string('seller_city');
            $table->string('seller_bank_account')->nullable();
            $table->string('seller_bank_name')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};

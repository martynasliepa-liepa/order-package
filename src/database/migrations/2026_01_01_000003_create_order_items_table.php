<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            // rysys su pagrindiniu uzsakymu
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            // rysys su isorine prekės lentele gali buti null
            $table->unsignedBigInteger('product_id')->nullable()->index();
            // prekes duomenys
            $table->string('product_name'); 
            $table->integer('quantity')->default(1);
            $table->decimal('price', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('order_items');
    }
};
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            
            // rysys su vartotoju gali buti null
            $table->unsignedBigInteger('user_id')->nullable()->index();
            
            // vartotojo duomenys
            $table->string('customer_first_name');
            $table->string('customer_last_name');
            $table->string('customer_email')->nullable();
            $table->string('customer_phone')->nullable();
            
            // risys su busenos lentele butinas
            $table->foreignId('order_status_id')->constrained('order_statuses')->onDelete('restrict');
            
            // papildomi duomenys
            $table->decimal('total_amount', 10, 2)->default(0.00);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('orders');
    }
};
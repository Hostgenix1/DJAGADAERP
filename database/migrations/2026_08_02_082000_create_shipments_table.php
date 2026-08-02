<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->string('number')->unique();
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('invoice_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->string('carrier')->nullable();
            $table->string('tracking_number')->nullable();
            $table->enum('shipping_method', ['air','sea','land','courier'])->default('air');
            $table->string('origin')->nullable();
            $table->string('destination')->nullable();
            $table->enum('status', ['preparing','in_transit','customs','delivered','cancelled'])->default('preparing');
            $table->datetime('shipped_at')->nullable();
            $table->datetime('estimated_arrival')->nullable();
            $table->datetime('delivered_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
    public function down(): void { Schema::dropIfExists('shipments'); }
};
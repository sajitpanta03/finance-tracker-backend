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
            $table->decimal('amount');
            $table->date('date_spend');
            $table->string('description');
            $table->foreignId('user_id')->constrained()->onDelete('CASCADE');
            $table->foreignId('category_id')->constrained('expense_categories')->onDelete('CASCADE');
            $table->foreignId('budget_id')->constrained('budgets')->onDelete('CASCADE');
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

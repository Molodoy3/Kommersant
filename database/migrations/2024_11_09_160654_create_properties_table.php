<?php

use App\Models\TransactionType;
use App\Models\TypeProperty;
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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('description', 2000);
            $table->integer('prise');
            $table->string('address', 100);
            $table->foreignIdFor(TypeProperty::class)->constrained();
            $table->foreignIdFor(TransactionType::class)->constrained();
            $table->decimal('square', 10, 2);
            $table->decimal('latitude', 10, 6)->nullable();
            $table->decimal('longitude', 11, 6)->nullable();
            $table->string('link');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};

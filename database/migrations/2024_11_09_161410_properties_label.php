<?php

use App\Models\Label;
use App\Models\Property;
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
        Schema::create('property_label', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Property::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(Label::class)->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_label');
    }
};

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
        Schema::create('languages', function (Blueprint $table) {
            $table->string('code', 3)->unique()->primary(); // e.g. en, fr, es, kis
            $table->string('name')->unique();             // e.g. English
            $table->string('native_name')->unique();      // e.g. English, Français, Kiswahili
            $table->string('flag')->nullable(); // Optional: for flag icon URLs
            $table->string('script_direction', 3)->default('LTR'); // e.g. RTL, LTR
            $table->string('float')->nullable($value = false);
            $table->bigInteger('lang_status')->index()->default(1)->nullable($value = false)->unsigned();
            $table->foreign('lang_status')->references('id')->on('workflow_status')->onDelete('restrict')->onUpdate('cascade');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            $table->softDeletes(); 
            $table->index('deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('languages');
    }
};

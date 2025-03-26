<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('sliders', function (Blueprint $table) {
            $table->enum('type', ['slider', 'banner'])->default('slider')->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('sliders', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};

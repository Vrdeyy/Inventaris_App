<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     * Menambahkan field placement_type dan breakdown jumlah per kondisi
     */
    public function up(): void
    {
        Schema::table('items', function (Blueprint $table) {
            // Penempatan: dalam_ruang atau dalam_lemari
            $table->enum('placement_type', ['dalam_ruang', 'dalam_lemari'])
                ->default('dalam_ruang')
                ->after('location');

            // Breakdown jumlah per kondisi
            $table->integer('qty_baik')->default(0)->after('quantity');
            $table->integer('qty_rusak')->default(0)->after('qty_baik');
            $table->integer('qty_hilang')->default(0)->after('qty_rusak');
        });

        // Update existing data: set qty_baik = quantity untuk data lama
        DB::statement("UPDATE items SET qty_baik = quantity WHERE `condition` = 'baik'");
        DB::statement("UPDATE items SET qty_rusak = quantity WHERE `condition` = 'rusak'");
        DB::statement("UPDATE items SET qty_hilang = quantity WHERE `condition` = 'hilang'");

        // Update condition enum untuk include 'sebagian_rusak'
        // Untuk MySQL, kita perlu alter column
        DB::statement("ALTER TABLE items MODIFY COLUMN `condition` ENUM('baik', 'rusak', 'hilang', 'sebagian_rusak') DEFAULT 'baik'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert condition enum
        DB::statement("ALTER TABLE items MODIFY COLUMN `condition` ENUM('baik', 'rusak', 'hilang') DEFAULT 'baik'");

        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn(['placement_type', 'qty_baik', 'qty_rusak', 'qty_hilang']);
        });
    }
};

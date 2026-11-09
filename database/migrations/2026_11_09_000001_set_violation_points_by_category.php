<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['ringan' => 5, 'sedang' => 15, 'berat' => 50] as $category => $points) {
            $typeIds = DB::table('violation_types')
                ->where('category', $category)
                ->pluck('id');

            DB::table('violation_types')
                ->whereIn('id', $typeIds)
                ->update(['points' => $points]);

            DB::table('violations')
                ->whereIn('violation_type_id', $typeIds)
                ->update(['points' => $points]);
        }
    }

    public function down(): void
    {
        // Custom point values before category standardization cannot be reconstructed.
    }
};

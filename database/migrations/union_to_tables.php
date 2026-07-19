<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('khajna_applications', function (Blueprint $table) {
            $table->string('union_name')->nullable()->after('upazila');
        });
        
        Schema::table('mutation_applications', function (Blueprint $table) {
            $table->string('union_name')->nullable()->after('upazila');
        });

        Schema::table('land_records', function (Blueprint $table) {
            $table->string('union_name')->nullable()->after('upazila');
        });
    }

    public function down()
    {
        Schema::table('khajna_applications', function (Blueprint $table) {
            $table->dropColumn('union_name');
        });

        Schema::table('mutation_applications', function (Blueprint $table) {
            $table->dropColumn('union_name');
        });

        Schema::table('land_records', function (Blueprint $table) {
            $table->dropColumn('union_name');
        });
    }
};

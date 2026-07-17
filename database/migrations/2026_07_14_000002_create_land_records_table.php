<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLandRecordsTable extends Migration
{
    public function up()
    {
        Schema::create('land_records', function (Blueprint $table) {
            $table->id();
            $table->string('district');
            $table->string('upazila');
            $table->string('dag_no');
            $table->string('khatian_no');
            $table->string('mouza');
            $table->string('owner_name');
            $table->decimal('area_percentage', 8, 2);
            $table->string('khajna_status')->default('Pending');
            $table->string('mutation_status')->default('Not applied');
            $table->decimal('previous_khajna_amount', 12, 2)->default(0);
            $table->string('previous_mutation_reference')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('land_records');
    }
}

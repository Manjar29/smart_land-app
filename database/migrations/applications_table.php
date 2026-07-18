<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('khajna_applications', function (Blueprint $table) {
            $table->id();
            $table->string('applicant_name');
            $table->string('district');
            $table->string('upazila');
            $table->string('dag_no');
            $table->string('khatian_no');
            $table->decimal('land_percentage', 8, 2);
            $table->string('tax_year');
            $table->string('mobile');
            $table->string('nid');
            $table->string('receipt_no')->unique();
            $table->decimal('amount', 12, 2);
            $table->string('status')->default('Submitted');
            $table->string('submitted_by')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('khajna_applications');
    }
};

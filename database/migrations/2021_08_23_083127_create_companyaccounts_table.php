<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyaccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companyaccounts', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('type',['Income','Expense','Pending'])->nullable();
            $table->unsignedInteger('company_id');
            $table->string('source')->nullable();
            $table->double('amount',10,2)->nullable();
            $table->date('date')->nullable();
            $table->string('document')->nullable();
            $table->string('company_balance')->nullable();
            $table->foreign('company_id')->references('id')->on('companies');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('companyaccounts');
    }
}

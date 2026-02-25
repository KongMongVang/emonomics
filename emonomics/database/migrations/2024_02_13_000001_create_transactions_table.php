<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('transaction_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('type_id');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->decimal('amount', 12, 2);
            $table->date('date');
            $table->unsignedBigInteger('emotion')->nullable();
            $table->string('note')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('user_id')->on('users');
            $table->foreign('type_id')->references('type_id')->on('types');
            $table->foreign('category_id')->references('category_id')->on('categories');
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
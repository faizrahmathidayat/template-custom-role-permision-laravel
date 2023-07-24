<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('product_type_id');
            $table->bigInteger('product_brand_id');
            $table->bigInteger('product_divisi_id');
            $table->bigInteger('product_category_id');
            $table->bigInteger('product_subcategory_id');
            $table->string('code')->unique();
            $table->string('name')->unique();
            $table->double('price')->nullable();
            $table->boolean('is_point');
            $table->enum('commision_type',['NOMINAL','PERCENTAGE']);
            $table->integer('stock')->nullable();
            $table->double('unit_commision')->nullable();
            $table->string('stock_unit_name');
            $table->string('small_unit');
            $table->string('unit_conversion')->nullable();
            $table->integer('purchase_lead_time')->nullable();
            $table->integer('minimal_order')->nullable();
            $table->integer('product_age_standard')->nullable();
            $table->enum('level_stock',['MINIMUM','MEDIUM','MAXIMUM']);
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->dateTime('created_at')->useCurrent();
            $table->bigInteger('created_by');
            $table->dateTime('updated_at')->useCurrentOnUpdate()->nullable();
            $table->bigInteger('updated_by')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->bigInteger('deleted_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('master_products');
    }
}

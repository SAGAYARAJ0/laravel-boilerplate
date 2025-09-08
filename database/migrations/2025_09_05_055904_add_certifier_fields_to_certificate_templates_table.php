<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('certificate_templates', function (Blueprint $table) {
            $table->string('certifier_design_id')->nullable()->after('template_data');
            $table->enum('template_source', ['local', 'certifier'])->default('local')->after('certifier_design_id');
            $table->string('certifier_group_id')->nullable()->after('template_source');
            $table->json('certifier_metadata')->nullable()->after('certifier_group_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('certificate_templates', function (Blueprint $table) {
            $table->dropColumn(['certifier_design_id', 'template_source', 'certifier_group_id', 'certifier_metadata']);
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('status', ['active', 'suspended', 'inactive'])->default('active')->after('role');
            $table->timestamp('suspended_at')->nullable()->after('status');
            $table->unsignedBigInteger('suspended_by')->nullable()->after('suspended_at');
            $table->text('suspension_reason')->nullable()->after('suspended_by');
            
            $table->foreign('suspended_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['suspended_by']);
            $table->dropColumn(['status', 'suspended_at', 'suspended_by', 'suspension_reason']);
        });
    }
};

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
        Schema::table('logs', function (Blueprint $table) {
            $table->text('request_data')->nullable()->after('message');
            $table->text('response_data')->nullable()->after('request_data');
            $table->text('error_details')->nullable()->after('response_data');
            $table->longText('stack_trace')->nullable()->after('error_details');
            $table->string('method', 10)->nullable()->after('stack_trace');
            $table->string('url')->nullable()->after('method');
            $table->string('ip_address', 45)->nullable()->after('url');
            $table->text('user_agent')->nullable()->after('ip_address');
            $table->decimal('response_time', 8, 3)->nullable()->after('user_agent');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('logs', function (Blueprint $table) {
            $table->dropColumn([
                'request_data',
                'response_data', 
                'error_details',
                'stack_trace',
                'method',
                'url',
                'ip_address',
                'user_agent',
                'response_time'
            ]);
        });
    }
};

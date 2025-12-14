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
        Schema::dropIfExists('cache');
        Schema::dropIfExists('cache_locks');
        Schema::dropIfExists('failed_jobs');
        Schema::dropIfExists('jobs');
        Schema::dropIfExists('job_batches');

        // Protect sessions table in environments that use database-backed sessions.
        // If you *really* want to drop sessions, set FORCE_DROP_SESSIONS=true in env.
        $forceDropSessions = filter_var(env('FORCE_DROP_SESSIONS', false), FILTER_VALIDATE_BOOLEAN);
        $sessionDriver = config('session.driver', env('SESSION_DRIVER', 'database'));
        if ($forceDropSessions || $sessionDriver !== 'database') {
            Schema::dropIfExists('sessions');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Note: Recreating these tables would require their original migration definitions
        // This is a one-way migration - these tables won't be restored on rollback
    }
};

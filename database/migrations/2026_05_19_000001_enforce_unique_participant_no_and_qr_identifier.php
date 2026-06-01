<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('participants')) {
            return;
        }

        $participantNoIndex = DB::selectOne("
            SELECT COUNT(*) AS total
            FROM information_schema.statistics
            WHERE table_schema = DATABASE()
              AND table_name = 'participants'
              AND index_name = 'participants_participant_no_unique'
        ");

        if ((int) $participantNoIndex->total === 0) {
            DB::statement("ALTER TABLE participants ADD UNIQUE participants_participant_no_unique (participant_no)");
        }

        $qrIdentifierIndex = DB::selectOne("
            SELECT COUNT(*) AS total
            FROM information_schema.statistics
            WHERE table_schema = DATABASE()
              AND table_name = 'participants'
              AND index_name = 'participants_qr_identifier_unique'
        ");

        if ((int) $qrIdentifierIndex->total === 0) {
            DB::statement("ALTER TABLE participants ADD UNIQUE participants_qr_identifier_unique (qr_identifier)");
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('participants')) {
            return;
        }

        $participantNoIndex = DB::selectOne("
            SELECT COUNT(*) AS total
            FROM information_schema.statistics
            WHERE table_schema = DATABASE()
              AND table_name = 'participants'
              AND index_name = 'participants_participant_no_unique'
        ");

        if ((int) $participantNoIndex->total > 0) {
            DB::statement("ALTER TABLE participants DROP INDEX participants_participant_no_unique");
        }

        $qrIdentifierIndex = DB::selectOne("
            SELECT COUNT(*) AS total
            FROM information_schema.statistics
            WHERE table_schema = DATABASE()
              AND table_name = 'participants'
              AND index_name = 'participants_qr_identifier_unique'
        ");

        if ((int) $qrIdentifierIndex->total > 0) {
            DB::statement("ALTER TABLE participants DROP INDEX participants_qr_identifier_unique");
        }
    }
};

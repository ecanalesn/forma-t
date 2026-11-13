<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Supabase expone automáticamente las tablas del esquema "public" a través de
 * su API REST (PostgREST). Activando Row Level Security sin políticas, esa API
 * no puede leer ni escribir nada, y el único acceso a los datos es la API de
 * Laravel (que se conecta como propietaria de las tablas y no se ve afectada).
 */
return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'pgsql') {
            return;
        }

        $tables = DB::select("select tablename from pg_tables where schemaname = 'public'");

        foreach ($tables as $table) {
            DB::statement(sprintf('alter table public."%s" enable row level security', $table->tablename));
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'pgsql') {
            return;
        }

        $tables = DB::select("select tablename from pg_tables where schemaname = 'public'");

        foreach ($tables as $table) {
            DB::statement(sprintf('alter table public."%s" disable row level security', $table->tablename));
        }
    }
};

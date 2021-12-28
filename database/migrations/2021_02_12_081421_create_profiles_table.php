<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     * unutar migracija radit ćemo stupce za tablice od:
     * id, user_id, title, description, url
     * id, user_id, title(zbog naslova), description(zbog opisa), url zbog url-a navedenog
     */
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('url')->nullable();
            $table->string('image')->nullable();

            $table->timestamps();

            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *Znači migracija ima up i down funkciju, gornja pokreće tablicu, tj kreira ju, a donja ju briše.
     *jer migracije se mogu kretati odozgo i odozdo
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('profiles');
    }
}

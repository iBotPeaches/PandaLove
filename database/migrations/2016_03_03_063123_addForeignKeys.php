<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('account_id', false, true)->nullable()->change();
        });

        DB::table('users')
            ->where('account_id', 0)
            ->update(['account_id' => null]);

        Schema::table('users', function (Blueprint $table) {
            $table->foreign('account_id')->references('id')->on('accounts');
        });

        Schema::table('halo5_warzone', function (Blueprint $table) {
            $table->foreign('account_id')->references('id')->on('accounts');
        });

        Schema::table('halo5_csrs', function (Blueprint $table) {
            $table->index('designationId')->unique();
        });

        Schema::table('halo5_seasons', function (Blueprint $table) {
            $table->index('contentId')->unique();
        });

        DB::statement('ALTER TABLE `halo5_csrs` CHANGE `designationId` `designationId` TINYINT(3) UNSIGNED NOT NULL;');

        Schema::table('halo5_playlists_data', function (Blueprint $table) {
            $table->foreign('account_id')->references('id')->on('accounts');
            $table->foreign('playlistId')->references('contentId')->on('halo5_playlists');
            $table->foreign('highest_CsrTier')->references('designationId')->on('halo5_csrs');
            $table->foreign('current_CsrTier')->references('designationId')->on('halo5_csrs');
            $table->foreign('seasonId')->references('contentId')->on('halo5_seasons');
        });

        Schema::table('halo5_data', function (Blueprint $table) {
            $table->foreign('account_id')->references('id')->on('accounts');
            $table->foreign('highest_CsrTier')->references('designationId')->on('halo5_csrs');
            $table->foreign('highest_CsrPlaylistId')->references('contentId')->on('halo5_playlists');
            $table->foreign('highest_CsrSeasonId')->references('contentId')->on('halo5_seasons');
            $table->foreign('seasonId')->references('contentId')->on('halo5_seasons');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('halo5_csrs', function (Blueprint $table) {
            $table->dropIndex('halo5_csrs_desinationid_index');
        });

        Schema::table('halo5_seasons', function (Blueprint $table) {
            $table->dropIndex('halo5_csrs_contentid_unique');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('users_account_id_foreign');
        });

        Schema::table('halo5_warzone', function (Blueprint $table) {
            $table->dropForeign('halo5_warzone_account_id_foreign');
        });

        Schema::table('halo5_playlists_data', function (Blueprint $table) {
            $table->dropForeign('halo5_playlists_data_account_id_foreign');
            $table->dropForeign('halo5_playlists_data_playlistid_foreign');
            $table->dropForeign('halo5_playlists_data_highest_csrtier_foreign');
            $table->dropForeign('halo5_playlists_data_current_csrtier_foreign');
            $table->dropForeign('halo5_playlists_data_seasonid_foreign');
        });

        Schema::table('halo5_data', function (Blueprint $table) {
            $table->dropForeign('halo5_data_account_id_foreign');
            $table->dropForeign('halo5_data_highest_csrtier_foreign');
            $table->dropForeign('halo5_data_highest_csrplaylistid_foreign');
            $table->dropForeign('halo5_data_highest_csrseasonid_foreign');
            $table->dropForeign('halo5_data_seasonid_foreign');
        });
    }
}

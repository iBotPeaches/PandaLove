<?php

use Illuminate\Database\Migrations\Migration;

class FixConstraintProblem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $season = new \Onyx\Halo5\Objects\Season();
        $season->contentId = 'Nonseasonal';
        $season->name = 'No Season';
        $season->start_date = new \Carbon\Carbon('2014-03-01');
        $season->end_date = new \Carbon\Carbon('2014-03-02');
        $season->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Onyx\Halo5\Objects\Season::where('contentId', 'Nonseasonal')->delete();
    }
}

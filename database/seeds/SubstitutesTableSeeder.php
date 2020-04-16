<?php
use App\Template;
use Illuminate\Database\Seeder;
use App\Resource;
use Illuminate\Support\Facades\DB;

class SubstitutesTableSeeder extends Seeder {

    public function run()
    {
        DB::table('substitutes')->delete();

        (new \App\Substitute())->refresh();
    }

}

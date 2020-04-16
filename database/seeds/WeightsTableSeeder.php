<?php
use App\Template;
use Illuminate\Database\Seeder;
use App\Resource;
use Illuminate\Support\Facades\DB;

class WeightsTableSeeder extends Seeder {

    public function run()
    {
        DB::table('weights')->delete();

        (new \App\Weight())->refresh();
    }

}

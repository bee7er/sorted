<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use RuntimeException;

class Substitute extends Model
{
    /**
     * Refresh weights table
     * @param $id
     * @return boolean
     */
    public function refresh()
    {
        $file = env('PROJECT_DIRECTORY') . DIRECTORY_SEPARATOR .
            env('SORT_CODE_IMPORT_DIR') . DIRECTORY_SEPARATOR .
            env('SORT_CODE_IMPORT_SUBSTITUTES');

        if (!file_exists($file)) {
            throw new RuntimeException("Could not find input file '$file'");
        } else {
            // Set any existing weight data to inactive
            $now = now();
            self::query()->whereNull('inactivated_at')->update(['inactivated_at' => $now]);

            // Open the data file and import the data
            $fh = fopen($file,'r');
            while ($info = fscanf($fh, "%s\t%s\n")) {

                $substitute = new self;
                list (
                    $substitute->original_sort_code,
                    $substitute->substitute_sort_code
                    ) = $info;

                $substitute->save();

            }
            fclose($fh);

        }

        return true;
    }
}

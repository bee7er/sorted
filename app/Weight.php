<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use RuntimeException;

class Weight extends Model
{
    const FIELDS = ['u', 'v', 'w', 'x', 'y', 'z', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h'];
    const SORT_CODE_FIELDS = ['u', 'v', 'w', 'x', 'y', 'z'];

    public $testsRun = 0;
    public $passesTest = false;

    /**
     * Refresh weights table
     * @param $id
     * @return boolean
     */
    public function refresh()
    {
        $file = env('PROJECT_DIRECTORY') . DIRECTORY_SEPARATOR .
            env('SORT_CODE_IMPORT_DIR') . DIRECTORY_SEPARATOR .
            env('SORT_CODE_IMPORT_WEIGHTS');

        if (!file_exists($file)) {
            throw new RuntimeException("Could not find input file '$file'");
        } else {
            // Set any existing weight data to inactive
            $now = now();
            self::query()->whereNull('inactivated_at')->update(['inactivated_at' => $now]);

            // Open the data file and import the data
            $fh = fopen($file,'r');
            while ($info = fscanf($fh, "%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\n")) {

                $weight = new self;
                list (
                    $weight->start,
                    $weight->end,
                    $weight->mod_check,
                    $weight->u,
                    $weight->v,
                    $weight->w,
                    $weight->x,
                    $weight->y,
                    $weight->z,
                    $weight->a,
                    $weight->b,
                    $weight->c,
                    $weight->d,
                    $weight->e,
                    $weight->f,
                    $weight->g,
                    $weight->h,
                    $weight->exception
                    ) = $info;

                $weight->save();

            }
            fclose($fh);

        }

        return true;
    }
}

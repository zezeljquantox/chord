<?php

namespace Chord\App\Traits;

use Illuminate\Support\Facades\Storage;

trait FileName
{
    /**
     * @param string $path
     * @param string $name
     * @return string
     */
    protected function uniqueFilename(string $path, string $name) {
        $output = $name;
        $name = explode('.', basename($name));
        $basename = $name[0];
        $ext = $name[1];

        $i = 1;

        while(Storage::disk($path)->exists($output)) {
            $output = $basename .'_'. $i . '.' . $ext;
            $i++;
        }

        return $output;
    }
}
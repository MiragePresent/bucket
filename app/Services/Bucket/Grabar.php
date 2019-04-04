<?php

namespace App\Services\Bucket;

use Illuminate\Support\Facades\Storage;

/**
 * Class Grabar
 *
 * @author Davyd Holovii <mirage.present@gmail.com>
 * @since  03.04.2019
 */
class Grabar
{
    /**
     * Downloads file from external source
     *
     * @param string $resource
     * @param string $path
     *
     * @return bool
     */
    public function downloadAs(string $resource, string $path): bool
    {
        return Storage::put($path, fopen($resource, 'r'));
    }
}

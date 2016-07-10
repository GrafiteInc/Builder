<?php

namespace Yab\Laracogs\Generators;

use Illuminate\Console\AppNamespaceDetectorTrait;
use Illuminate\Filesystem\Filesystem;

trait FileMakerTrait
{
    use AppNamespaceDetectorTrait;

    public function copyPreparedFiles($directory, $destination)
    {
        $fileSystem = new Filesystem();

        $files = $fileSystem->allFiles($directory);

        $fileDeployed = false;

        $fileSystem->copyDirectory($directory, $destination);

        foreach ($files as $file) {
            $fileContents = $fileSystem->get($file);
            $fileContentsPrepared = str_replace('{{App\}}', $this->getAppNamespace(), $fileContents);
            $fileDeployed = $fileSystem->put($destination.'/'.$file->getRelativePathname(), $fileContentsPrepared);
        }

        return $fileDeployed;
    }
}

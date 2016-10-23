<?php
/**
 * Created by PhpStorm.
 * User: carlosagudobelloso
 * Date: 23/10/16
 * Time: 17:48.
 */

namespace AppBundle\Services;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    private $targetDir;

    public function __construct(array $parameters)
    {
        $this->targetDir = $parameters['upload_dir'];
    }

    public function upload(UploadedFile $file)
    {
        $fileName = md5(uniqid()).'.'.$file->guessExtension();
        $file->move($this->targetDir, $fileName);
        $targetDir = explode('web', $this->targetDir);

        return $targetDir[1].DIRECTORY_SEPARATOR.$fileName;
    }
}

<?php

namespace App\Helpers;

class ContentLoader
{
    /**
     * Get file that will be upload to /upload/:uploadType directory with random filename and return string to this file
     * @param mixed $avatar
     * @param mixed $uploadType
     */
    public static function uploadImage($avatar, $uploadType): ?string
    {
        $storagePath = '../storage';
        $uploadPath = 'upload';
        if (!empty($avatar)) {
            $fileTmpPath = $_FILES[$uploadType]['tmp_name'];
            $fileName = $_FILES[$uploadType]['name'];
            $fileType = $_FILES[$uploadType]['type'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));

            $allowedfileExtensions = ['jpg', 'gif', 'png', 'jpeg'];
            $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];

            if (in_array($fileExtension, $allowedfileExtensions) && in_array($fileType, $allowedMimeTypes)) {
                $newFileName = uniqid() . '.' . $fileExtension;
                $movePath = $storagePath . "/" . $uploadPath . '/' . $uploadType . '/' . $newFileName;

                if (move_uploaded_file($fileTmpPath, $movePath)) {
                    $path = $uploadPath . '/' . $uploadType . '/' . $newFileName;
                    return $path;
                } else {
                    return null;
                }
            }
        } else {
            $defaultPath = $uploadPath . '/' . $uploadType . '/' .'default.jpg';
            return $defaultPath;
        }
    }

    /**
     * @param mixed $path
     * @return void
     */
    public static function getImage($path): void
    {
        echo $path;
    }
}

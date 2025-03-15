<?php

namespace App\Helper;

class ContentLoader
{
    /** Get file that will be upload to /upload/:uploadType directory with random filename and return string to this file */
    public function uploadImage($avatar, $uploadType): string | null
    {
        $uploadPath = '../storage/upload/';
        if (!empty($avatar)) {
            $fileTmpPath = $_FILES[$uploadType]['tmp_name'];
            $fileName = $_FILES[$uploadType]['name'];
            $fileSize = $_FILES[$uploadType]['size'];
            $fileType = $_FILES[$uploadType]['type'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));

            $allowedfileExtensions = ['jpg', 'gif', 'png', 'jpeg'];
            $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];

            if (in_array($fileExtension, $allowedfileExtensions) && in_array($fileType, $allowedMimeTypes)) {
                $newFileName = uniqid() . '.' . $fileExtension;
                $path = $uploadPath . '/' . $uploadType . '/' . $newFileName;

                if (move_uploaded_file($fileTmpPath, $path)) {
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

    public function getImage($path)
    {

    }
}

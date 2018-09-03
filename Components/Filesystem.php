<?php

namespace Micro\Component;

use Exception;
use Micro\Helper\Arrayment;

class Filesystem {
    public function readFile($file)
    {
        try {
            return file_get_contents($file);
        } catch (Exception $e) {
            throw new Exception('The file ' . $file . ' can\'t be readed, Permission denied');
        }
    }

    public function saveFile($file, $data, $overwrite = false)
    {
        if (($overwrite && file_exists($file)) || !file_exists($file)) {
            try {
                return @file_put_contents($file, $data);
            } catch (Exception $e) {
                throw new Exception('The file ' . $file . ' can\'t be written, Permission denied');
            }
        } else {
            throw new Exception('The file ' . $file . ' already exists');
        }
    }

    public function deleteFile($file)
    {
        try {
            return unlink($file);
        } catch (Exception $e) {
            throw new Exception('The file ' . $file . ' can\'t be deleted, Permission denied');
        }
    }

    public function isFile($file)
    {
        return is_file($file);
    }

    public function getInfoFile($file)
    {
        if (file_exists($file)) {
            $fileinfo = [
                'name' => basename($file),
                'path' => $file,
                'size' => filesize($file),
                'date' => filemtime($file),
                'readable' => is_readable($file),
                'executable' => is_executable($file),
                'fileperms' => fileperms($file)
            ];

            return $fileinfo;
        }

        return false;
    }

    public function readDir($dir, $flat = true, $recursive = false)
    {
        if (is_dir($dir)) {
            $content = array_values(array_diff(scandir($dir), ['.', '..']));

            if ($recursive || $flat) {
                foreach ($content as $key => $entry) {
                    $path = DS . trim($dir, DS) . DS . $entry;

                    if (is_dir($path)) {
                        $path = $path . DS;
                    }

                    if ($flat) {
                        $content[$key] = $path;
                    }

                    if (is_dir($path) && $recursive && !$flat) {
                        $content[$key] = [$entry => $this->readDir($path, true)];
                    } elseif (is_dir($path) && $recursive && $flat) {
                        $content[$path] = $path;
                        $content[$key] = [$entry => $this->readDir($path, true)];
                    }
                }
            }

            if ($flat) {
                $arrayment = new Arrayment();
                $content = $arrayment->collapse($content, true);
            }

            return $content;
        }

        return [];
    }

    public function createDir($dir, $permissions = 0777)
    {
        try {
            return mkdir($dir, $permissions, true);
        } catch (Exception $e) {
            throw new Exception('The directory ' . $dir . ' can\'t be created, Permission denied');
        }
    }

    public function deleteDir($dir, $recursive = true)
    {
        if ($recursive) {
            $entries = $this->readDir($dir, true, true);

            foreach ($entries as $entry) {
                if (is_file($entry)) {
                    unlink($entry);
                } elseif (is_dir($entry)) {
                    rmdir($entry);
                }
            }

            return rmdir($dir);
        } else {
            try {
                return rmdir($dir);
            } catch (Exception $e) {
                throw new Exception('The directory ' . $dir . ' can\'t be deleted, Permission denied');
            }
        }
    }

    public function isDir($dir)
    {
        try {
            return is_dir($dir);
        } catch (Exception $e) {
            throw new Exception('The directory ' . $dir . ' can\'t be readed, Permission denied');
        }
    }

    public function fileExists($file)
    {
        return file_exists($file);
    }

    public function dirExists($dir)
    {
        return $this->isDir($dir);
    }
}

<?php

namespace Micro\Core;

use Exception;
use Micro\Helper\Json as Json;
use Micro\Helper\Text as Text;

class Model
{
    protected $language;

    public function __construct()
    {
        // Initialize language object
        $this->language = new Language();
    }

    protected function createTable($name)
    {
        // Load helpers
        $text = new Text();
        $json = new Json();

        $data    = [];
        $name    = $text->snakeCase($name);
        $encoded = base64_encode($json->jsonEncode($data));

        return (bool) file_put_contents(DATABASEDIR . $name . '.db', $encoded);
    }

    protected function deleteTable($name)
    {
        // Load helpers
        $text = new Text();

        $name = $text->snakeCase($name);

        return unlink(DATABASEDIR . $name . '.db');
    }

    protected function insert($table, array $data)
    {
        // Load helpers
        $text = new Text();
        $json = new Json();

        $uuid  = uniqid();
        $table = $text->snakeCase($table);
        $file  = DATABASEDIR . $table . '.db';

        if (file_exists($file)) {
            // Fetch the table data
            $table_data = file_get_contents($file);
            $decoded    = (array) $json->jsonDecode(base64_decode($table_data));

            // Add new row
            if (!isset($decoded[$uuid])) {
                $decoded[$uuid]	= (object) $data;
            }

            // Save data
            $encoded = base64_encode($json->jsonEncode($decoded));

            return (bool) file_put_contents(DATABASEDIR . $table . '.db', $encoded);
        } else {
            throw new Exception('The table "' . $table . '" could not be found in the database');
        }
    }

    protected function update($table, $uuid, array $data)
    {
        // Load helpers
        $text = new Text();
        $json = new Json();

        $uuid  = uniqid();
        $table = $text->snakeCase($table);
        $file  = DATABASEDIR . $table . '.db';

        if (file_exists($file)) {
            // Fetch the table data
            $table_data = file_get_contents($file);
            $decoded    = (array) $json->jsonDecode(base64_decode($table_data));

            // Update row
            if (!isset($decoded[$uuid])) {
                // Get old row
                $old_row = (array) $decoded[$uuid];

                // Combine row deltas
                $new_row        = array_merge($old_row, $data);
                $decoded[$uuid]	= (object) $new_row;
            }

            // Save data
            $encoded = base64_encode($json->jsonEncode($decoded));

            return (bool) file_put_contents(DATABASEDIR . $table . '.db', $encoded);
        } else {
            throw new Exception('The table "' . $table . '" could not be found in the database');
        }
    }

    protected function delete($table, $uuid)
    {
        // Load helpers
        $text = new Text();
        $json = new Json();

        $table = $text->snakeCase($table);
        $file  = DATABASEDIR . $table . '.db';

        if (file_exists($file)) {
            // Fetch the table data
            $table_data = file_get_contents($file);
            $decoded    = (array) $json->jsonDecode(base64_decode($table_data));

            // Delete row
            if (isset($decoded[$uuid])) {
                unset($decoded[$uuid]);
            }

            // Save data
            $encoded = base64_encode($json->jsonEncode($decoded));

            return (bool) file_put_contents(DATABASEDIR . $table . '.db', $encoded);
        } else {
            throw new Exception('The table "' . $table . '" could not be found in the database');
        }
    }

    protected function get($table, $uuid = null)
    {
        // Load helpers
        $text = new Text();
        $json = new Json();

        $table = $text->snakeCase($table);
        $file  = DATABASEDIR . $table . '.db';

        if (file_exists($file)) {
            // Fetch the table data
            $table_data = file_get_contents($file);
            $decoded    = (array) $json->jsonDecode(base64_decode($table_data));

            if (is_null($uuid)) {
                return $decoded;
            }

            return (object) $decoded[$uuid];
        } else {
            throw new Exception('The table "' . $table . '" could not be found in the database');
        }
    }

    protected function loadCommand($command)
    {
        // Load Text helper
        $text = new Text();

        $command = $text->studlyCase($command);
        $file    = COMMANDSDIR . $command . '.php';

        if (file_exists($file)) {
            require $file;

            $class_name       = '\\Micro\\Command\\' . $command;
            $this->{$command} = new $class_name();
        } else {
            throw new Exception('The command "' . $command . '" could not be found');
        }
    }
}

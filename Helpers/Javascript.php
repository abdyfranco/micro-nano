<?php

namespace Micro\Helper;

class Javascript extends Html {
    private $js_files = [];
    private $js_inline = [];
    private $default_path;

    public function __construct($default_path = null)
    {
        $this->setDefaultPath($default_path);
    }

    public function setDefaultPath($default_path)
    {
        $temp = $this->default_path;
        $this->default_path = $default_path;

        return $temp;
    }

    public function getFiles($location)
    {
        $html = null;
        if (isset($this->js_files[$location])) {
            $num_docs = count($this->js_files[$location]);
            for ($i = 0; $i < $num_docs; $i++) {
                $html .= $this->addCondition('<script type="text/javascript" src="' . $this->_($this->js_files[$location][$i]['file'], true) . '"></script>', $this->js_files[$location][$i]['condition'], $this->js_files[$location][$i]['hidden']) . "\n";
            }
        }

        return $html;
    }

    public function getInline()
    {
        $html = null;

        $num_docs = count($this->js_inline);

        for ($i = 0; $i < $num_docs; $i++) {
            $html .= $this->addCondition('<script type="text/javascript">' . $this->js_inline[$i]['data'] . '</script>', $this->js_inline[$i]['condition'], $this->js_inline[$i]['hidden']) . "\n";
        }

        return $html;
    }

    public function setFile($file, $location = 'head', $path = null, $condition = null, $hidden = true)
    {
        if ($path == null) {
            $path = $this->default_path;
        }

        $this->js_files[$location][] = [
            'file' => $path . $file,
            'condition' => $condition,
            'hidden' => $hidden
        ];

        return $this;
    }

    public function setInline($data, $condition = null, $hidden = true)
    {
        $this->js_inline[] = [
            'data' => $data,
            'condition' => $condition,
            'hidden' => $hidden
        ];

        return $this;
    }

    public function unsetFiles()
    {
        $this->js_files = [];

        return $this;
    }

    public function unsetInline()
    {
        $this->js_inline = [];

        return $this;
    }
}

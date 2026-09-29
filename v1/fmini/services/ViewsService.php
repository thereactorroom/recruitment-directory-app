<?php

class ViewsService
{
    protected $file;
    protected $primary;
    protected $secondary;
    protected $uniqueKey;

    public function __construct($file, $primary, $secondary, $uniqueKey)
    {
        $this->file = $file;
        $this->primary = $primary;
        $this->secondary = $secondary;
        $this->uniqueKey = $uniqueKey;        
    }

    public function css(): string {

        if (!file_exists($this->file)) {
            echo $this->file;
            echo $css;
            return '';
        }

        $css = file_get_contents($this->file);

        return str_replace(
            [
                'pri_key',
                'sec_key',
                ':unq_key'
            ],
            [
                $this->primary,
                $this->secondary,
                $this->uniqueKey
            ],
            $css
        );
    }
}
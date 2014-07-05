<?php

class NotORM_Entity extends NotORM_Row {

    public function offsetGet($key) {
        $functionName = 'get' . $this->getFunctionName($key);
        if (method_exists($this, $functionName)) {
            return $this->$functionName();
        }
        return parent::offsetGet($key);
    }

    public function offsetSet($key, $value) {
        $functionName = 'set' . $this->getFunctionName($key);
        if (method_exists($this, $functionName)) {
            $this->$functionName($value);
        } else {
            parent::offsetSet($key, $value);
        }
    }

    public function getRaw($key) {
        return parent::offsetGet($key);
    }

    public function setRaw($key, $value) {
        parent::offsetSet($key, $value);
    }

    /** convert underscored to upper-camelcase
     * example "this_method_name" -> "ThisMethodName"
     * @see http://www.php.net/manual/en/function.ucwords.php#92092
     *
     * @param string $key
     * @return string
     */
    private function getFunctionName($key) {
        return preg_replace_callback('/(?:^|_)(.?)/', function($m) { strtoupper($m[1]); },$key);
    }

}
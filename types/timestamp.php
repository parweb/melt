<?php

class TimestampType extends Type {
    public $only_date = "false";
    public $title = "";

    public function getSQLType() {
        return "int";
    }
    public function SQLize($data) {
        return intval($data);
    }
    public function getInterface($label, $data, $name) {
        $title = ($this->title != "")? ' title="' . $this->title . '" ': '';
        $dateonly = $this->only_date == "true";
        $date_syntax_helper = __('YYYY-MM-DD');
        $time_syntax_helper = __(', HH:MM:SS');
        $stamp = ($data != 0)? $this->write($data): $date_syntax_helper;
        if (!$dateonly && $data == 0)
            $stamp .= $time_syntax_helper;
        return "$label <input$title type=\"text\" name=\"$name\" value=\"$stamp\" />"
            . "<br /><span style=\"font-size: 9px;\">" . __('Timestamp Format')
            . ": " . $date_syntax_helper . ($dateonly? "": $time_syntax_helper) . "</span>";
    }
    public function read($name, &$value) {
        $newstamp = strval(@$_POST[$name]);
        // Get the numeric clusters.
        $m = preg_split('#[^0-9]+#', $newstamp);
        // Filter all empty positions.
        $m = array_values(array_filter($m, create_function('$val', 'return $val !== \'\';')));
        // Make timestamp.
        $dateonly = $this->only_date == "true";
        if ($dateonly) {
            if (count($m) == 3) {
                $yr = intval($m[0]);
                $mo = intval($m[1]);
                $d = intval($m[2]);
                $time = mktime(0, 0, 0, $mo, $d, $yr);
                if ($time === false || $time === -1)
                    $time = 0;
            } else
                $time = 0;
        } else {
            if (count($m) == 6) {
                $yr = intval($m[0]);
                $mo = intval($m[1]);
                $d = intval($m[2]);
                $hr = intval($m[3]);
                $mi = intval($m[4]);
                $s = intval($m[5]);
                $time = mktime($hr, $mi, $s, $mo, $d, $yr);
                if ($time === false || $time === -1)
                    $time = 0;
            } else
                $time = 0;
        }
        $value = intval($time);
    }
    public function write($value) {
        $dateonly = $this->only_date == "true";
        return date(!$dateonly? 'Y-m-d, H:i:s': 'Y-m-d', intval($value));
    }

}

?>
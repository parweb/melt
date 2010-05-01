<?php namespace nanomvc\data_tables;

class DataTablesModule extends \nanomvc\Module {
    public static function getAuthor() {
        $year = date("Y");
        return "Hannes Landeholm, Media People Sverige AB, ©$year";
    }

    public static function getInfo() {
        return "<b>Integrating DataTables into nanoMVC.</b>";
    }

    public static function getVersion() {
        return "1.0.1";
    }
}
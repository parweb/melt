<?php

namespace nanomvc\translate;

class TranslateModule extends \nanomvc\CoreModule {
    /** RPC function that exports a translation from the database. */
    public static function export() {
        // See if the language exists.
        \nanomvc\db\enable_display();
        $language = strtolower($_GET["lang"]);
        $lang_table = \nanomvc\db\query("DESCRIBE " . config\TRANSLATION_TABLE);
        while (false !== ($column = api_database::next_array($lang_table))) {
            $column_name = strtolower($column[0]);
            if ($column_name == $language) {
                $out_buffer = "<?php\nglobal \$_lang_translation;\n\$_lang_translation = array(\n";
                $translations = api_database::query("SELECT original,$column_name FROM "
                . config\DB_HOST . "." . config\TRANSLATION_TABLE);
                while (false !== ($row = api_database::next_array($translations))) {
                    $original = var_export($row[0], true);
                    $translate = var_export($row[1], true);
                    $out_buffer .= "\t$original =>\n\t\t$translate,\n\n";
                }
                $out_buffer .= ");";
                $filename = APP_DIR . "/lang.$language.php";
                file_put_contents($filename, $out_buffer);
                die("\n\nTranslation export successful!\nTranslation was written to: $filename");
            }
        }
        die("The language you specified: $language, does not exist in the language table. Export failed!");
    }

    private static function try_set_language($set_to, $languages) {
        $set_to = substr($set_to, 0, 2);
        if (in_array($set_to, $languages)) {
            $_SESSION['language'] = $set_to;
            define("LANGUAGE_SET", $set_to);
            define("LANGUAGE_FILE", APP_DIR . "/lang.$set_to.php");
            require LANGUAGE_FILE;
            // Since not using default language, we can append a content-language header.
            header("Content-Language: " . LANGUAGE_SET);
            return true;
        } else
            return false;
    }

    public static function beforeRequestProcess() {
        // Ignore the rest of this file if not translating.
        if (!\nanomvc\translate\config\ENABLE)
            return;
        // Get array of supported languages.
        $language_files = glob(APP_DIR . "/lang.??.php");
        $languages = array();
        foreach ($language_files as $file)
            $languages[] = substr($file, -6, 2);
        define("TRANSLATION_AVAILIBLE", count($languages) > 0);
        if (!TRANSLATION_AVAILIBLE)
            return;
        // Determine what language to use if supporting more than one language.
        if (count($languages) > 1) {
            $lang = null;
            if (isset($_SESSION['language']))
                // 1. Set by session, if it can.
                if (self::try_set_language($_SESSION['language'], $languages))
                    return;
            if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
                // 2. Set by accept-language, if it can.
                $server_accept_language = preg_replace('#\s#', '', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
                $accept_langs = explode(',', $server_accept_language);
                $lang_try_order = array();
                foreach ($accept_langs as $lang) {
                    if (false !== ($qp = strpos($lang, ';q='))) {
                        $q = floatval(substr($lang, $qp + 3));
                    } else
                        $q = 1;
                    $lang = substr($lang, 0, 2);
                    $lang_try_order[$lang] = $q;
                }
                arsort($lang_try_order);
                foreach ($lang_try_order as $lang => $q)
                    if (self::try_set_language($lang, $languages))
                        return;
            }
            // 3. Set to english, if it can.
            if (self::try_set_language('en', $languages))
                return;
        }
        // 4. Set to one of the supported.
        try_set_language($languages[0], $languages);
    }
}

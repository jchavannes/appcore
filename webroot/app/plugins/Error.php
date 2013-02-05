<?php

class Error {

    const MAX_LOG_LENGTH = 1000;

    static public function log($newline) {

        $logfile = ROOT_DIR . "../app.log";
        $logs = array();

        if (file_exists($logfile)) {
            $logs = explode("\n", file_get_contents($logfile));
        }

        $logs[] = $newline;

        if (count($logs) > self::MAX_LOG_LENGTH) {
            $logs = array_slice($logs, count($logs) - self::MAX_LOG_LENGTH);
        }

        file_put_contents($logfile, implode($logs, "\n"));

    }

    static public function dump($var) {
        echo "<pre style='background:#d5d5d5; border:2px solid #888; padding:5px;'>";
        var_dump($var);
        echo "</pre>";
    }

    static public function show(Exception $e) {
        echo "<h1>Caught Error</h1>";
        echo "<h2>File: " . $e->getFile() . "<br/>Line: " . $e->getLine() . "</h2>";
        echo "<h3><i>" . $e->getMessage() . "</i></h3>";
        $trace = $e->getTrace();
        echo '<div style="background:#d5d5d5; border:2px solid #888; padding:5px;">';
        echo "<h4>STACK TRACE</h4>";
        for ($i = 0; $i < count($trace); $i++) {
            echo 
                "<p>" .
                    "<b>" .
                    (count($trace) - $i) . ". " . $trace[$i]['file'] . ":" . $trace[$i]['line'] . "</b><br/>" .
                    $trace[$i]['class'] . "::" . $trace[$i]['function'] . "("; 
                $first = true;
                foreach ($trace[$i]['args'] as $arg) {
                    if (!$first) {echo ", ";}
                    echo "\"$arg\"";
                    $first = false;
                }

            echo ")<br/>" .
                "</p>";

        }
        echo "</div>";
    }

}
<?php

function show_error($code, $message)
{
    http_response_code($code);
    echo "<html>";
    echo "<h1> $message </h1>";
    echo "</html>";
    exit(0);
}

?>

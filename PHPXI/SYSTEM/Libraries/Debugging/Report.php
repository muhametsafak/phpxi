<?php
/**
 * Report.php
 *
 * This file is part of PHPXI.
 *
 * @package    Report.php @ 2021-05-11T20:49:12.748Z
 * @author     Muhammet ŞAFAK <info@muhammetsafak.com.tr>
 * @copyright  Copyright © 2021 PHPXI Open Source MVC Framework
 * @license    http://www.gnu.org/licenses/gpl-3.0.txt  GNU GPL 3.0
 * @version    1.6.2
 * @link       http://phpxi.net
 *
 * PHPXI is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * PHPXI is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with PHPXI.  If not, see <https://www.gnu.org/licenses/>.
 */

if (!isset($file)) {
    $file = "";
}
if (!isset($line)) {
    $line = "";
}
if (!isset($message)) {
    $message = "";
}
if (!isset($name)) {
    $name = "";
}

$css_file = [
    SYSTEM_PATH . 'Libraries/Debugging/assets/default.min.css',
    SYSTEM_PATH . 'Assets/css/bootstrap.min.css',
    SYSTEM_PATH . 'Libraries/Debugging/assets/handler.css'
];

$js_file = [
    SYSTEM_PATH . 'Assets/js/jquery.min.js',
    SYSTEM_PATH . 'Libraries/Debugging/assets/highlight.min.js',
    SYSTEM_PATH . 'Assets/js/bootstrap.min.js'
];

$report = [];

$reader = new \PHPXI\Libraries\Debugging\File();

echo '<!DOCTYPE html> <html lang="en"> <head>';

echo '<meta charset="utf-8" />';

echo '<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />';

echo '<title>Debug :: ErrorHandler | PHPXI</title>';

echo '<meta name="robots" content="noindex" />';

echo '<style tpye="text/css">';

foreach ($css_file as $css_row) {
    if (file_exists($css_row)) {
        require $css_row;
    }
}

echo '</style>';

echo '<script> ';

foreach ($js_file as $js_row) {
    if (file_exists($js_row)) {
        require $js_row;
    }
}

echo ' </script>';

echo '<script>hljs.highlightAll();</script>';

echo '</head> <body>';

echo '<nav class="navbar navbar-expand-lg"><div class="container-fluid">';

echo '<a class="navbar-brand" href="#">PHPXI::ErrorHandler</a>';

echo '<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>';

echo '<div class="collapse navbar-collapse" id="navbarText">';

echo '<ul class="navbar-nav me-auto mb-2 mb-lg-0">';

echo '<li class="nav-item"><a class="nav-link" aria-current="page" target="_blanks" title="Search on Google" href="https://www.google.com/search?q=' . urlencode($message) . '"><i class="fab fa-google"></i> Google Search</a></li>';

echo '<li class="nav-item"><a class="nav-link" aria-current="page" target="_blank" title="Search in stackoverflow.com" href="https://stackoverflow.com/search?q=' . urlencode($message) . '"><i class="fab fa-stack-overflow"></i> Stackoverflow.com</a></li>';

echo '</ul>';

echo '<span class="navbar-text">';

echo '<ul class="navbar-nav me-auto mb-2 mb-lg-0">';

echo '<li class="nav-item"><a class="nav-link active" aria-current="page" href="http://phpxi.net">PHPXI.NET</a></li>';

echo '<li class="nav-item"><a class="nav-link" href="http://docs.phpxi.net">Documents</a></li>';

echo '<li class="nav-item"><a class="nav-link" href="https://github.com/muhametsafak/phpxi/issues">Issues</a></li>';

echo '</ul>';

echo '</span>';

echo '</div>';

echo '</div></nav>';

echo '<main role="main" class="container mt-4"><section>';

echo '<h1>' . $name . '</h1>';

echo '<div class="row"><div class="col-md-12">';

$view_file = str_replace([PHPXI_PATH, PUBLIC_PATH], ["{PHPXI_PATH}", "{PUBLIC_PATH}"], $file);
$line--;
echo '<strong>' . $view_file . '</strong> at line <strong> ' . $line . ' </strong><br /> <code>' . $message . '</code>';

echo '</div></div>';

echo '<div class="row"><div class="col-md-12">';

echo '<p>' . $reader->read($file, $line) . '</p>';

$report["INFO"] = [
    "version" => VERSION,
    "type" => $name,
    "file" => $view_file,
    "line" => $line,
    "message" => $message,
    "php_version" => phpversion()
];

echo '</div></div>';

echo '</section>';

echo '<section class="detail mt-4">';

echo '<div class="row"><div class="col-md-12 text-break">';

echo '<ul class="nav nav-tabs" id="myTab" role="tablist">';

echo '<li class="nav-item" role="presentation"><button class="nav-link active" id="backtrace-tab" data-bs-toggle="tab" data-bs-target="#backtrace" type="button" role="tab" aria-controls="fibacktraceles" aria-selected="true">Backtrace</button></li>';

echo '<li class="nav-item" role="presentation"><button class="nav-link" id="files-tab" data-bs-toggle="tab" data-bs-target="#files" type="button" role="tab" aria-controls="files" aria-selected="true">Files</button></li>';

echo '<li class="nav-item" role="presentation"><button class="nav-link" id="request-tab" data-bs-toggle="tab" data-bs-target="#request" type="button" role="tab" aria-controls="request" aria-selected="true">Request</button></li>';

echo '<li class="nav-item" role="presentation"><button class="nav-link" id="response-tab" data-bs-toggle="tab" data-bs-target="#response" type="button" role="tab" aria-controls="response" aria-selected="true">Response</button></li>';

echo '<li class="nav-item" role="presentation"><button class="nav-link" id="constants-tab" data-bs-toggle="tab" data-bs-target="#constants" type="button" role="tab" aria-controls="constants" aria-selected="false">Constants</button></li>';

echo '<li class="nav-item" role="presentation"><button class="nav-link" id="server-tab" data-bs-toggle="tab" data-bs-target="#server" type="button" role="tab" aria-controls="server" aria-selected="false">$_SERVER</button></li>';

echo '<li class="nav-item" role="presentation"><button class="nav-link" id="memory-tab" data-bs-toggle="tab" data-bs-target="#memory" type="button" role="tab" aria-controls="memory" aria-selected="false">MEMORY</button></li>';

echo '<li class="nav-item" role="presentation"><button class="nav-link" id="report-tab" data-bs-toggle="tab" data-bs-target="#report" type="button" role="tab" aria-controls="report" aria-selected="false">Report Output</button></li>';

echo '</ul>';

echo '<div class="tab-content" id="debugTabContent">';

//Backtrace Begin
$backtrace = debug_backtrace();
echo '<div class="tab-pane fade show active" id="backtrace" role="tabpanel" aria-labelledby="backtrace-tab">';
$report['BACKTRACE'] = [];
foreach ($backtrace as $row) {
    if (isset($row['file']) && isset($row['line'])) {
        $line = $row['line'] - 1;
        $file = str_replace(
            [PHPXI_PATH, PUBLIC_PATH],
            ["{PHPXI_PATH}", "{PUBLIC_PATH}"],
            $row['file']
        );
        $report['BACKTRACE'][] = $file . ':' . $line;
        echo ' <br /> <strong>' . $file . '</strong> at line <strong>' . $line . '</strong> <br /> <br />';
        echo $reader->read($row['file'], $line, 5, 5);
    } else {
        $report['BACKTRACE'][] = $row;
        echo '<table class="table table-hover"><thead><tr><th scope="col">KEY</th><th scope="col">Value</th></tr></thead><tbody>';
        if (isset($row['function'])) {
            echo '<tr><th class="col-md-4" scope="row">Function</th><td class="col-md-8 text-break">' . $row["function"] . '</td></tr>';
        }
        if (isset($row['class'])) {
            echo '<tr><th class="col-md-4" scope="row">Class</th><td class="col-md-8 text-break">' . $row["class"] . '</td></tr>';
        }
        if (isset($row['type'])) {
            echo '<tr><th class="col-md-4" scope="row">Type</th><td class="col-md-8 text-break">' . $row["type"] . '</td></tr>';
        }
        echo '</tbody></table>';
    }

    echo '<hr />';
}
unset($backtrace);
echo '</div>';
//Backtrace End

//Includes Files List Begin
echo '<div class="tab-pane fade show" id="files" role="tabpanel" aria-labelledby="files-tab">';
echo '<table class="table table-hover"><tbody>';
$included = get_included_files();
$i = 1;
$report['INCLUDED'] = [];
foreach ($included as $file) {
    $file = str_replace(
        [PHPXI_PATH, PUBLIC_PATH],
        ["{PHPXI_PATH}", "{PUBLIC_PATH}"],
        $file
    );
    $report['INCLUDED'][] = $file;
    if ("{PHPXI_PATH}\SYSTEM\Assets" != substr($file, 0, 26) && "{PHPXI_PATH}\SYSTEM\Libraries\Debugging" != substr($file, 0, 39)) {
        echo '<tr>';
        echo '<td class="col-md-8">' . $i . '. ' . $file . '</td>';
        echo '</tr>';
        $i++;
    }
}
unset($included);
echo '</tbody></table>';
echo '</div>';
//Includes Files List End

//Request Begin
echo '<div class="tab-pane fade show" id="request" role="tabpanel" aria-labelledby="request-tab">';

echo '<table class="table table-hover"><tbody><tr><th class="col-md-4" scope="row">User Agent</th><td class="col-md-8">' . $_SERVER["HTTP_USER_AGENT"] . '</td></tr></tbody></table>';

echo '<h4>Headers</h4><table class="table table-hover"><thead><tr><th scope="col">KEY</th><th scope="col">Value</th></tr></thead><tbody>';
$request_headers = apache_request_headers();
$report["APACHE_REQUEST_HEADERS"] = $request_headers;
foreach ($request_headers as $key => $value) {
    echo '<tr>';
    echo '<th class="col-md-4" scope="row">' . $key . '</th>';
    echo '<td class="col-md-8">';
    if (is_string($value) || is_numeric($value)) {
        echo $value;
    } else {
        var_dump($value);
    }
    echo '</td>';
    echo '</tr>';
}
unset($request_headers);
echo '</tbody></table>';

echo '<h4>$_COOKIE</h4><table class="table table-hover"><thead><tr><th scope="col">KEY</th><th scope="col">Value</th></tr></thead><tbody>';
$cookie = $_COOKIE;
$report["COOKIE"] = $cookie;
foreach ($cookie as $key => $value) {
    echo '<tr>';
    echo '<th class="col-md-4" scope="row">' . $key . '</th>';
    echo '<td class="col-md-8">';
    if (is_string($value) || is_numeric($value)) {
        echo $value;
    } else {
        var_dump($value);
    }
    echo '</td>';
    echo '</tr>';
}
unset($cookie);
echo '</tbody></table>';

if (!function_exists("get_browser")) {
    echo '<h4>Browser</h4><table class="table table-hover"><thead><tr><th scope="col">KEY</th><th scope="col">Value</th></tr></thead><tbody>';
    $get_browser = get_browser(null, true);
    $report["GET_BROWSER"] = $get_browser;
    foreach ($get_browser as $key => $value) {
        echo '<tr>';
        echo '<th class="col-md-4" scope="row">' . $key . '</th>';
        echo '<td class="col-md-8">';
        if (is_string($value) || is_numeric($value)) {
            echo $value;
        } else {
            var_dump($value);
        }
        echo '</td>';
        echo '</tr>';
    }
    unset($get_browser);
    echo '</tbody></table>';
}
echo '</div>';
//Request End

//Response Begin
echo '<div class="tab-pane fade show" id="response" role="tabpanel" aria-labelledby="response-tab">';
echo '<table class="table table-hover"><tbody><tr><th class="col-md-4" scope="row">Response Status</th><td class="col-md-8">' . $response_status . '</td></tr></tbody></table>';
echo '<h4>Headers</h4> <table class="table table-hover"><thead><tr><th scope="col">KEY</th><th scope="col">Value</th></tr></thead><tbody>';
$response_headers = apache_response_headers();
$report["APACHE_RESPONSE_HEADER"] = $response_headers;
foreach ($response_headers as $key => $value) {
    echo '<tr>';
    echo '<th class="col-md-4" scope="row">' . $key . '</th>';
    echo '<td class="col-md-8">';
    if (is_string($value) || is_numeric($value)) {
        echo $value;
    } else {
        var_dump($value);
    }
    echo '</td>';
    echo '</tr>';
}
unset($response_headers);
echo '</tbody></table>';
echo '</div>';
//Response End

//Constants Begin
echo '<div class="tab-pane fade" id="constants" role="tabpanel" aria-labelledby="constants-tab">';
echo '<table class="table table-hover"><thead><tr><th scope="col">KEY</th><th scope="col">Value</th></tr></thead><tbody>';
$const = get_defined_constants(true);
$report["CONSTANTS"] = $const;
foreach ($const['user'] as $key => $value) {
    echo '<tr>';
    echo '<th class="col-md-4" scope="row">' . $key . '</th>';
    echo '<td class="col-md-8">';
    if (is_string($value) || is_numeric($value)) {
        echo $value;
    } else {
        var_dump($value);
    }
    echo '</td>';
    echo '</tr>';
}
echo '</tbody></table>';
unset($const['user']);
echo '<h1>System & PHP Constants</h1>';
echo '<div class="alert alert-danger" role="alert">Attention: This printout contains critical information about your server.</div>';
foreach ($const as $key => $value) {
    echo '<hr />';
    echo '<h4>' . $key . '</h4>';
    echo '<table class="table table-hover"><thead><tr><th scope="col">KEY</th><th scope="col">Value</th></tr></thead><tbody>';
    foreach ($value as $subkey => $subvalue) {
        echo '<tr>';
        echo '<th class="col-md-4" scope="row">' . $subkey . '</th>';
        echo '<td class="col-md-8">';
        if (is_string($subvalue) || is_numeric($subvalue)) {
            echo $subvalue;
        } else {
            var_dump($subvalue);
        }
        echo '</td>';
        echo '</tr>';
    }
    echo '</tbody></table>';
}
unset($const);
echo '</div>';
//Constants End

//$_SERVER Begin
echo '<div class="tab-pane fade" id="server" role="tabpanel" aria-labelledby="server-tab">';
echo '<table class="table table-hover"> <thead> <tr> <th scope="col">KEY</th><th scope="col">Value</th></tr></thead><tbody>';
$server = $_SERVER;
$report["SERVER"] = $server;
foreach ($server as $key => $value) {
    echo '<tr>';
    echo '<th class="col-md-4" scope="row">' . $key . '</th>';
    echo '<td class="col-md-8">';
    if (is_string($value) || is_numeric($value)) {
        echo $value;
    } else {
        var_dump($value);
    }
    echo '</td>';
    echo '</tr>';
}
unset($server);
echo '</tbody></table>';
echo '</div>';
//$_SERVER End

//Memory Begin
echo '<div class="tab-pane fade" id="memory" role="tabpanel" aria-labelledby="memory-tab">';
echo '<table class="table table-hover"><tbody>';

$memory_get_peak_usage = memory_get_peak_usage();
$report['INFO']['MEMORY_USE'] = $memory_get_peak_usage;
echo '<tr>';
echo '<th class="col-md-4" scope="row">Memory USE</th>';
echo '<td class="col-md-8">' . ceil($memory_get_peak_usage / 1048576) . ' MB</td>';
echo '</tr>';
$php_memory_limit = ini_get('memory_limit');
$report['INFO']['PHP_MEMORY_LIMIT'] = $php_memory_limit;
echo '<tr>';
echo '<th class="col-md-4" scope="row">Memory LIMIT</th>';
echo '<td class="col-md-8">' . $php_memory_limit . '</td>';
echo '</tr>';
echo '</tbody></table>';
echo '</div>';
//Memory End

//Report Begin
echo '<div class="tab-pane fade" id="report" role="tabpanel" aria-labelledby="report-tab">';
echo '<div class="alert alert-danger" role="alert">Attention: This report printout contains critical information about your server.</div>';
echo '<pre class="first language-json">';
echo var_dump($report);
unset($report);
echo '</pre>';
echo '</div>';
//Report End

echo '</div></div></div>';
echo '</section>';
echo '<section class="text-center version">PHPXI Version : ' . VERSION . ' / PHP Version : ' . phpversion() . '</section><br /><br />';
echo '</main>';

echo '<script src="https://kit.fontawesome.com/671e88baef.js" crossorigin="anonymous"></script>';

echo '<footer class="footer"> <div class="container text-center"> <span>Copyright &copy; ' . date("Y") . ' PHPXI (' . VERSION . ') Open Source MVC Framework</span></div></footer> </body> </html>';

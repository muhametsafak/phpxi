<?php
/**
 * Author: Muhammet ŞAFAK <info@muhammetsafak.com.tr>
 * Project: PHPXI MVC Framework <phpxi.net>
 */

if (!isset($line) and !isset($coding) and !isset($message) and !isset($name) and !isset($file)) {
    exit;
} else {
    @header('HTTP/1.1 500 Internal Server Error', true, 500);
}

?><!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <title>Debug :: ErrorHandle | PHPXI</title>
        <meta name="robots" content="noindex" />
        <style tpye="text/css"><?php require SYSTEM_PATH . 'Libraries/Debugging/assets/style.css.php';?></style>
    </head>
    <body>
        <div class="d-flex" id="wrapper">
            <!-- Sidebar-->
            <div class="bg-light border-right" id="sidebar-wrapper">
                <div class="sidebar-heading">Development (<?php echo 'v' . VERSION; ?>)</div>
                <div class="list-group list-group-flush">
                    <a class="list-group-item list-group-item-action bg-light" target="_blanks" title="Search on Google" href="https://www.google.com/search?q=<?php echo urlencode($message); ?>">Google Search</a>
                    <a class="list-group-item list-group-item-action bg-light" target="_blank" title="Search in stackoverflow.com" href="https://stackoverflow.com/search?q=<?php echo urlencode($message); ?>">Stackoverflow.com</a>
                </div>
            </div>
            <!-- Page Content-->
            <div id="page-content-wrapper">
                <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ml-auto mt-2 mt-lg-0">
                            <li class="nav-item active">
                                <a class="nav-link" target="_blank" href="http://phpxi.net">
                                    PHPXI.NET
                                </a>
                            </li>
                            <li class="nav-item"><a class="nav-link" href="https://github.com/muhametsafak/phpxi/issues">Issues</a></li>
                            <li class="nav-item"><a class="nav-link" href="https://github.com/muhametsafak/phpxi/wiki">Wiki</a></li>
                        </ul>
                    </div>
                </nav>
                <div class="container-fluid">
                    <h1 class="mt-4"><?php echo $name; ?></h1>
                    <p>
                        <strong>
                        <?php echo $file; ?>
                        </strong>
                        at line
                        <strong>
                        <?php echo $line; ?>
                        </strong><br />
                        <code><?php echo $message; ?><code>
                    </p>
                    <p>
                        <?php echo $coding; ?>
                    </p>
                </div>
            </div>
        </div>
    </body>
</html>
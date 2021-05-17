<!DOCTYPE html>
<html lang="en" >
    <head>
        <meta charset="UTF-8">
        <title><?php echo Lang::r("welcome_title"); ?></title>
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Ubuntu:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
        <style type="text/css">
            body
            {
                font-size: 48px;
                font-family: "Ubuntu";
                background: #fafafa;
                color: #2c001e;
            }

            div
            {
                display: flex;
                justify-content: center;
                align-items: center;
            }

            h1
            {
                color: #77216f;
            }
            p
            {
                display: flex;
                justify-content: center;
                align-items: center;
                font-size: 18px;
                font-weight: normal;
                text-align: center;
            }

            p code
            {
                color: #5e2750;
                font-weight: bold;
                font-style: italic;
                border-radius: 2px;
                padding: 2px 5px;
                margin-left: 7px;
                margin-right: 7px;
                cursor: pointer;
            }

            .blink
            {
                color: #dd4814;
                font-size: 1.5em;
                animation: blink-anim 0.8s ease 0.3s infinite alternate;
            }

            .footer
            {
                display: flex;
                justify-content: bottom;
                align-items: center;
                font-size: 13px;
                font-weight: normal;
                text-align: center;
            }
        </style>
    </head>
    <body>
        <div>
            <h1>&lt;?php<span class="blink">XI</span>?&gt;</h1>
        </div>
        <p>
            <?php echo $starting; ?>
        </p>
        <div class="footer">
<?php
Lang::e("footer_load_time_info", ["memory_use" => MEMORY_USE, "load_time" => LOAD_TIME]);
?>
        </div>
    </body>
</html>
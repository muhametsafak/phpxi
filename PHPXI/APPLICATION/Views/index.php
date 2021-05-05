<!DOCTYPE html>
<html lang="en" >
    <head>
        <meta charset="UTF-8">
        <title>phpXI::Hello World!</title>
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
            /*
            @keyframes blink-anim
            {
                from { opacity: 1; }
                to { opacity: 0.2; }
            }
            */
            
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
            You can start developing from the <code title="<?php echo APPLICATION_PATH; ?>">/PHPXI/APPLICATION/</code> directory.
        </p>
        <div class="footer">
        
            <?php echo 'This page was submitted using ' . MEMORY_USE . ' MB memory per ' . LOAD_TIME . ' seconds.'; ?>
        </div>
    </body>
</html>
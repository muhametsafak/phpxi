<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title><?php echo $title; ?></title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="<?php public_url("assets/css/reset.css", true); ?>" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php public_url("assets/css/welcome.css", true); ?>" media="screen" />
</head>
<body>
<div id="container">
  <div id="header">
    <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAASwAAABeCAYAAACZ4CkLAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyJpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoV2luZG93cykiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6RTQ4NUE2QzhGNzMyMTFFQUI1M0ZDREZENDVDREI4QkYiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6RTQ4NUE2QzlGNzMyMTFFQUI1M0ZDREZENDVDREI4QkYiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDpFNDg1QTZDNkY3MzIxMUVBQjUzRkNERkQ0NUNEQjhCRiIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDpFNDg1QTZDN0Y3MzIxMUVBQjUzRkNERkQ0NUNEQjhCRiIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PvgakLsAAAlJSURBVHja7F3tcSI5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" class="logo" />
    <h2><?php echo $this->lang->r("welcome_page_h2"); ?></h2>
  </div>
  <!--//end #headern//-->
  <div id="leftColumn">
    <ul>
      <li><a href="https://github.com/muhametsafak/phpxi">Go to Githup Project</a></li>
      <li><a href="https://github.com/muhametsafak/phpxi/blob/master/LICENSE">LICENSE</a></li>
      <li><a href="http://www.muhammetsafak.com.tr/phpxi">Blog</a></li>
      <li><a href="http://docs.phpxi.net">Documents</a></li>
      <li><a href="http://www.muhammetsafak.com.tr/donate">Donate</a></li>
    </ul>
    <p>Thank you for creating your project with PHPXI. PHPXI develop by <a href="https://www.muhammetsafak.com.tr">Muhammet ŞAFAK</a> is a free and open source framework.</p>
  </div>
  <!--//end #leftColumn//-->
  <div id="centerColumn">
    <h2>Let's start</h2>
    <p>If you don't know where to start. You can start from the "/PHPXI/APPLICATION/" directory.</p>
    <blockquote>
      <p><?php echo $this->lang->r("utterances", [utterances(), "Anonymous"]); ?></p>
    </blockquote>
  </div>
  <div id="footer"> <a target="_blank" href="http://phpxi.net/" title="PHPXI MVC Framework">PHPXI</a> tarafından <?php echo LOAD_TIME." sn'de ".MEMORY_USE." MB bellek kullanılarak"; ?>  oluşturuldu! | Development : <a target="_blank" href="http://www.muhammetsafak.com.tr/" title="PHPXI Developer">Muhammet ŞAFAK</a>
    </p>
  </div>
  
</div>

</body>
</html>

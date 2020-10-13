<?php
namespace PHPXI\SYSTEM;

class Debug{

    public $type;
    public $file;
    public $line;
    public $message;
    public $name;

    function __construct($type, $file, $line, $message){
        $this->file = $file;
        $this->line = $line;
        $this->type = $type;
        $this->message = $message;
        $this->error_name();
    }

    function error_name(){
        $_ERRORS = Array(
            0x0001 => 'E_ERROR',
            0x0002 => 'E_WARNING',
            0x0004 => 'E_PARSE',
            0x0008 => 'E_NOTICE',
            0x0010 => 'E_CORE_ERROR',
            0x0020 => 'E_CORE_WARNING',
            0x0040 => 'E_COMPILE_ERROR',
            0x0080 => 'E_COMPILE_WARNING',
            0x0100 => 'E_USER_ERROR',
            0x0200 => 'E_USER_WARNING',
            0x0400 => 'E_USER_NOTICE',
            0x0800 => 'E_STRICT',
            0x1000 => 'E_RECOVERABLE_ERROR',
            0x2000 => 'E_DEPRECATED',
            0x4000 => 'E_USER_DEPRECATED'
        );
        if(!@is_string($name = @array_search($this->type, @array_flip($_ERRORS)))){
            $name = 'E_UNKNOWN';
        }
        $this->name = $name;
    }

    function file_read_lines(){
        $lines = array();
        for($i = ($this->line - 1); $i >= ($this->line - 7); $i--){
            if($i >= 0){
                $lines[] = $i;
            }
        }
        $lines = array_reverse($lines);
        $lines[] = $this->line;
        for($i = ($this->line + 1); $i <= ($this->line + 7); $i++){
            if(!in_array($i, $lines)){
                $lines[] = $i;
            }
        }
        return $lines;
    }

    function file_read(){
        if(file_exists($this->file)){

            $file_error_lines = array();
            $open = file($this->file);
            foreach($this->file_read_lines() as $row){
                $line = $row - 1;
                if(isset($open[$line])){
                    $file_error_lines[$row] = str_replace(array("<", ">"), array("&lt;", "&gt;"), $open[$line]);
                }
            }
            return $file_error_lines;
        }else{
            return false;
        }
    }

    public function testing(){
        $return = $this->name . " - Error in file " . $this->file . " at line " . $this->line . " : ".$this->message."\n";
        return $return;
    }

    public function development(){
        $return = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"><head> <title>PHPXI ERROR</title> <meta http-equiv="content-type" content="text/html; charset=utf-8" /><style type="text/css">body.phpxi_debug{width: 100%; height: 100%; z-index:999;padding:0;background-color:#fff;color:#000;text-align:center;font-family:Arial,Helvetica,sans-serif;font-size:.9em}a{color:#c00;text-decoration:underline}a:hover{color:#eee;text-decoration:none}img.logo{height:40px;width:auto;margin:10px 0 0 10px;}blockquote{margin:1em;padding:.5em;background-color:#eee;border-top:1px solid #ccc;border-bottom:1px solid #ccc}blockquote p{margin:.2em}#phpxi_debug{margin:3em auto 0 auto;padding:1em;width:40em;text-align:left;vertical-align:middle;background-color:#ccc;border:1px solid #999}#phpxi_debug h2{margin:0 0 -.5em;padding:.75em 0 0;font-size:1em;letter-spacing:.1em}#header{margin:-1em -1em 0;padding:0;height:5em;background-color:#fff}#header h1{margin:0 0 -.6em;padding:.5em 0 0 1em;font-size:1.5em;letter-spacing:.1em}#header h2{margin:0;padding:0 0 0 4.5em;font-size:.9em;font-weight:300;letter-spacing:.1em}#nav{margin:0 0 1em;padding:.4em 0 0}#nav ul{margin:0;padding:0;list-style:none}#nav li{margin:0;padding:.25em;display:inline}#footer{position:relative;bottom:0;margin:5em 0 0;padding:0;height:4em;line-height:4em;text-align:center;font-size:.7em;background-color:#ccc;border-top:1px solid #999}pre{background:#2c3e50;padding:12px 0 14px;width:100%;color:#ecf0f1;line-height:100%;overflow-x:auto;}code.line{width:15px;padding-left:5px;text-align:left;display:block;float:left;}code.error{color: red;}pre::-webkit-scrollbar-track{-webkit-box-shadow:inset 0 0 6px rgba(0,0,0,0.3);background-color:#2c3e50}pre::-webkit-scrollbar{height:6px;background-color:#F5F5F5}pre::-webkit-scrollbar-thumb{background-color:#000}</style></head><body class="phpxi_debug"><div id="phpxi_debug"><div id="header"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAASwAAABeCAYAAACZ4CkLAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyJpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoV2luZG93cykiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6RkIxNTAzNTNGNzMyMTFFQTk0MjhBRjZDNUM2NkE3NTAiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6RkIxNTAzNTRGNzMyMTFFQTk0MjhBRjZDNUM2NkE3NTAiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDpGQjE1MDM1MUY3MzIxMUVBOTQyOEFGNkM1QzY2QTc1MCIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDpGQjE1MDM1MkY3MzIxMUVBOTQyOEFGNkM1QzY2QTc1MCIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PslUsPgAAAknSURBVHja7F3tbSo7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" class="logo"><h2>Debugging</h2></div><p><strong>'.$this->file.'</strong> at line <strong>'.$this->line.'</strong></p>';


        $read = $this->file_read();
        if(is_array($read)){
            $return .= '<pre>';
            foreach($read as $line => $code){
                if($line == $this->line){
                    $return .= '<code class="line error">'.$line.'</code> <code class="error">'.$code.'</code>';
                }else{
                    $return .= '<code class="line">'.$line.'</code> <code>'.$code.'</code>';
                }
                
            }
            $return .= '</pre>';
        }
        $return .= '<blockquote><p>'.$this->name.' - Error in file <strong>'.$this->file.'</strong> at line <strong>'.$this->line.'</strong> : '.$this->message.'</p></blockquote>';
        $return .= '<div id="footer"> <a href="http://www.phpxi.net">phpxi.net</a> developed by <a href="http://www.muhammetsafak.com.tr">Muhammet ŞAFAK</a></div></div></body></html>';
        return $return;
    }
}

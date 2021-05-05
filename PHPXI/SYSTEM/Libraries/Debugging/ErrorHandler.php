<?php
namespace PHPXI\Libraries\Debugging;

class ErrorHandler
{

    private $type;
    private $file;
    private $line;
    private $message;
    private $name;

    function __construct($type, $file, $line, $message)
    {
        $this->type = $type;
        $this->file = $file;
        $this->line = $line;
        $this->message = $message;
        $this->error_name();
    }

    private function error_name()
    {
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

    private function file_read_lines()
    {
        $lines = array();
        for($i = ($this->line - 1); $i >= ($this->line - 8); $i--){
            if($i >= 0){
                $lines[] = $i;
            }
        }
        $lines = array_reverse($lines);
        $lines[] = $this->line;
        for($i = ($this->line + 1); $i <= ($this->line + 8); $i++){
            if(!in_array($i, $lines)){
                $lines[] = $i;
            }
        }
        return $lines;
    }

    private function file_read()
    {
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

    public function testing()
    {
        $return = $this->name . " - Error in file " . $this->file . " at line " . $this->line . " : ".$this->message."\n";
        return $return;
    }

    public function printr(){
        $read = $this->file_read();
        if(is_array($read)){
            $coding = '<pre class="first">';
            foreach($read as $line => $code){
                if($line == $this->line){
                    $coding .= '<code class="error"><div class="line">' . $line . '</div>' . $code . '</code>';
                }else{
                    $coding .= '<code><div class="line">' . $line . '</div>' . $code . '</code>';
                }
                
            }
            $coding .= '</pre>';
        }
        
        $line = $this->line;
        $file = $this->file;
        $name = $this->name;
        $message = $this->message;


        require SYSTEM_PATH . "Libraries/Debugging/ErrorHandler.template.php";
    }


}

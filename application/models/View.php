<?php
class View {
    function  __construct() {
    }
    function render($file,$head='',$foot='') {
        ob_start();
        if($head)
            require_once (ROOT.'/application/views/'.$head);

        require_once (ROOT.'/application/views/'.$file);

        if($foot)
            require_once (ROOT.'/application/views/'.$foot);
        return ob_get_clean();
    }
}

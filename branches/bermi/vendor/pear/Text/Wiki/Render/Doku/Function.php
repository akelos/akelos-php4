<?php

// $Id: Function.php,v 1.1 2005/07/21 20:56:15 justinpatrin Exp $

class Text_Wiki_Render_Doku_Function extends Text_Wiki_Render {
    
    /**
    * 
    * Renders a token into text matching the requested format.
    * 
    * @access public
    * 
    * @param array $options The "options" portion of the token (second
    * element).
    * 
    * @return string The text rendered from the token options.
    * 
    */
    
    function token($options)
    {
        extract($options); // name, access, return, params, throws
        
        // build the baseline output
        $output = $this->conf['format_main'];
        $output = str_replace('%access', htmlspecialchars($access), $output);
        $output = str_replace('%return', htmlspecialchars($return), $output);
        $output = str_replace('%name', htmlspecialchars($name), $output);
        
        // build the set of params
        $list = array();
        foreach ($params as $key => $val) {
            
            // is there a default value?
            if ($val['default']) {
                $tmp = $this->conf['format_paramd'];
            } else {
                $tmp = $this->conf['format_param'];
            }
            
            // add the param elements
            $tmp = str_replace('%type', htmlspecialchars($val['type']), $tmp);
            $tmp = str_replace('%descr', htmlspecialchars($val['descr']), $tmp);
            $tmp = str_replace('%default', htmlspecialchars($val['default']), $tmp);
            $list[] = $tmp;
        }
        
        // insert params into output
        $tmp = implode($this->conf['list_sep'], $list);
        $output = str_replace('%params', $tmp, $output);
        
        // build the set of throws
        $list = array();
        foreach ($throws as $key => $val) {
               $tmp = $this->conf['format_throws'];
            $tmp = str_replace('%type', htmlspecialchars($val['type']), $tmp);
            $tmp = str_replace('%descr', htmlspecialchars($val['descr']), $tmp);
            $list[] = $tmp;
        }
        
        // insert throws into output
        $tmp = implode($this->conf['list_sep'], $list);
        $output = str_replace('%throws', $tmp, $output);
        
        // close the div and return the output
        $output .= '</div>';
        return "\n$output\n\n";
    }
}
?>
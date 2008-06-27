<?php
define('COMPULSORY','COMPULSORY');

class Route extends AkObject 
{

    private $url_pattern;
    private $defaults;
    private $requirements;
    private $regex;
    private $dynamic_segments = array();
    private $segments;
    
    function __construct($url_pattern, $defaults = array(), $requirements = array(), $conditions = array())
    {
        $this->url_pattern  = $url_pattern;    
        $this->defaults     = $defaults;
        $this->requirements = $requirements; 
    }
    
    function match(AkRequest $Request)
    {
        $url = $Request->getRequestedUrl();
        #var_dump($url);
        
        if (!preg_match($this->getRegex(),$url,$matches)) return false;
        array_shift($matches);   //throw away the "all-match", we only need the groups
        #var_dump($matches);

        $params = array();
        $break = false;
        foreach ($matches as $i=>$match){
            if (empty($match)) {
                $break = true;
                continue;  
            }
            if ($break) return false;
            $params[$this->dynamic_segments[$i]] = $match;
        }
        foreach ($this->defaults as $name=>$value){
            if (!isset($params[$name])){
                $params[$name] = $value;
            }
        }
        return $params;
    }

    function urlize($params)
    {
        $url = '';
        $segments = $this->getSegments();
        
        $break = false;
        foreach ($segments as $segment){
            if ($segment instanceof Segment){
                $name = $segment->name;
                if (isset($params[$name]) && !($segment->default == $params[$name])){
                    if ($break) return false;
                    if (!$segment->meetsRequirement($params[$name])) return false;
                    $url .= '/'.$params[$name];
                }else{
                    if (!$segment->isOptional()) return false; // compulsory segment must be set
                    $break = true;
                }
                unset ($params[$name]);  // later we'll need the parameters that don't match dynamic-segments 
            }else{ // = static segment
                $url .= $segment;
            }
        }
        
        if ($url=='') $url = '/';

        // $params now holds additional values which are not present in the url-pattern as 'dynamic-segments'
        if (!empty($params)){
            if (!$key_value_list = $this->getAdditionalKeyValueListForUrl($params)) return false;
            $url .= $key_value_list;
        }
        return $url;
    }
    
    function getAdditionalKeyValueListForUrl($params)
    {
        $key_value_pairs = array();
        foreach ($params as $name=>$value){
            // don't override defaults that don't correspond to dynamic segments
            if (isset($this->defaults[$name]) && $this->defaults[$name] != $value) return false;
            $key_value_pairs[] = "$name=$value";
        }
        return '?'.join('&',$key_value_pairs);            
    }
    
    function getRegex()
    {
        if ($this->regex) return $this->regex;
        
        $regex = '|^'.join('',$this->getSegments()).'$|';
        #var_dump($regex);
        return $this->regex = $regex;        
    }
    
    function getSegments()
    {
        if ($this->segments) return $this->segments;
        
        $segments = explode('/',trim($this->url_pattern,'/'));
        foreach ($segments as &$segment){
            if ($this->isVariableSegment($segment)){
                $name = $this->dynamic_segments[] = substr($segment,1);
                $segment = new Segment($name,'/',@$this->defaults[$name],@$this->requirements[$name]);
            }else{
                $segment = '/'.$segment;
            }
        }
        return $this->segments = $segments;
    }
    
    function isVariableSegment($name)
    {
        if ($name && $name{0}==':') return true;
        return false;
    }

}

?>
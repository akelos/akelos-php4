<?php
require_once 'IdealWorld_TestCase.php';

class NamedRouteIdealWorld extends IdealWorld_TestCase 
{

    public $Routes = array(
        'author' =>array('/author/:name',array('controller'=>'author','action'=>'show','name'=>COMPULSORY)),
        'default'=>array('/:controller/:action/:id',array('controller'=>COMPULSORY,'action'=>'index')),
        'root'   =>array('/',array('controller'=>'blog','action'=>'index'))
    );
    
    function testDefaultRoute()
    {
        $url_writer = $this->withRequestTo('/user');
        $this->assertEquals('http://localhost/user/show/1',default_url(array('action'=>'show','id'=>'1')));
    }
    
    function testChangeLanguage()
    {
        $url_writer = $this->withRequestTo('/en/user/show/1');
        $this->assertEquals('/es/user/show/1',default_path(array('overwrite_params'=>array('lang'=>'es'))));
    }
    
    function testFromDefaultToAuthor()
    {
        $this->withRequestTo('/user');
        $this->assertEquals('http://localhost/author/mart',author_url(array('name'=>'mart')));
    }

    function testFromAuthorToRoot()
    {
        $this->withRequestTo('/author/steve');
        $this->assertEquals('http://localhost/',root_url());
    }
    
    function testFromRootToAuthorPath()
    {
        $this->withRequestTo('/');
        $this->assertEquals('/author/steve',author_path(array('name'=>'steve')));
    }
    
}

?>
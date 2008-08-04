<?php
require_once 'TemplatePicking_TestCase.php';

class TemplatePathsTests extends TemplatePicking_TestCase 
{

    function testSettingLayoutToFalseMeansYouDontWantALayout() 
    {
        $this->createViewTemplate('index');
        $this->createTemplate('layouts/application.tpl');
        $controller = $this->createControllerFor('index');
        $controller->layout = false;
        
        $this->expectRender(array('index'));
        $controller->defaultRender();
    }
    
    function testPickApplicationLayoutIfWeDontHaveAControllerLayout()
    {
        $this->createViewTemplate('index');
        $this->createTemplate('layouts/application.tpl');
        $controller = $this->createControllerFor('index');
        
        $this->expectRender(array('index',AK_VIEWS_DIR.DS.'layouts/application.tpl'));
        $controller->defaultRender();
    }
    
    function testDontPickAnyLayoutIfNoneIsPresent()
    {
        $this->createViewTemplate('index');
        $controller = $this->createControllerFor('index');
        
        $this->expectRender(array('index'));
        $controller->defaultRender();
    }
    
    function testPickControllerLayoutIfPresent()
    {
        $this->createViewTemplate('index');
        $this->createTemplate('layouts/template_paths.tpl');
        $controller = $this->createControllerFor('index');
        
        $this->expectRender(array('index',AK_VIEWS_DIR.DS.'layouts/template_paths.tpl'));
        $controller->defaultRender();
    }
    
    function testPickExplicitlySetLayout()
    {
        $this->createViewTemplate('index');
        $this->createTemplate('render_tests/my_layout.tpl');
        $controller = $this->createControllerFor('index');
        $controller->setLayout('render_tests/my_layout');
        
        $this->expectRender(array('index',AK_VIEWS_DIR.DS.'render_tests/my_layout.tpl'));
        $controller->defaultRender();
    }
    
    function testPickALayoutUsingADefinedMethod()
    {
        $this->createViewTemplate('index');
        $this->createTemplate('layouts/picked_from_method.tpl');
        $controller = $this->createControllerFor('index');
        $controller->setLayout('my_layout_picker');
        
        $this->expectRender(array('index',AK_VIEWS_DIR.DS.'layouts/picked_from_method.tpl'));
        $controller->defaultRender();
    }
    
    function testPickALayoutUsingAnObject()
    {
        $this->createViewTemplate('index');
        $this->createTemplate('layouts/picked_from_method.tpl');
        $controller = $this->createControllerFor('index');
        $controller->setLayout(array($controller,'my_layout_picker'));
        
        $this->expectRender(array('index',AK_VIEWS_DIR.DS.'layouts/picked_from_method.tpl'));
        $controller->defaultRender();
    }
    
    function testPickLayoutIfActionameMatches()
    {
        $this->createViewTemplate('index');
        $this->createTemplate('layouts/application.tpl');
        $controller = $this->createControllerFor('index');
        $controller->setLayout('application',array('only'=>'index'));
        
        $this->expectRender(array('index',AK_VIEWS_DIR.DS.'layouts/application.tpl'));
        $controller->defaultRender();
    }

    function testPickLayoutUnlessActionameMatches()
    {
        $this->createViewTemplate('index');
        $this->createTemplate('layouts/application.tpl');
        $controller = $this->createControllerFor('index');
        $controller->setLayout('application',array('except'=>'index'));
        
        $this->expectRender(array('index'));
        $controller->defaultRender();
    }
    
}

?>
<?php echo '<?php'?>


class <?php echo $controller_class_name?> extends ApplicationController
{
<?php 
    if($model_name != $controller_name){ // if equal will be handled by the Akelos directly
        echo "    var \$models = '$singular_name';\n\n";
    }
?>
    function index()
    {
        $this->performAction('listing');
    }

<?php  foreach((array)@$actions as $action) :?>
    function <?php echo $action?>()
    {
    }

<?php  endforeach; ?>
    function listing()
    {
        $this-><?php echo $singular_name?>_pages = $this->pagination_helper->getPaginator($this-><?php echo $model_name?>, array('items_per_page' => 10));
        $options = $this->pagination_helper->getFindOptions($this-><?php echo $model_name?>);
        $this-><?php echo $plural_name?> =& $this-><?php echo $model_name?>->find('all', $options);
    }

    function show()
    {<?php if($model_name != $controller_name){ ?>

        $this-><?php echo $singular_name?> =& $this-><?php echo $model_name?>->find(@$this->params['id']);<?php } ?>

    }

    function add()
    {
        if(!empty($this->params['<?php echo $singular_name?>'])){
            $this-><?php echo $model_name?>->setAttributes($this->params['<?php echo $singular_name?>']);
            if ($this->Request->isPost() && $this-><?php echo $model_name?>->save()){
                $this->flash['notice'] = $this->t('<?php echo $model_name?> was successfully created.');
                $this->redirectTo(array('action' => 'show', 'id' => $this-><?php echo $model_name?>->getId()));
            }
        }
    }
    <?php  if($model_name != $controller_name){ ?>

    function edit()
    {
        if(!empty($this->params['id'])){
            if(empty($this-><?php echo $singular_name?>->id) || $this-><?php echo $singular_name?>->id != $this->params['id']){
                $this-><?php echo $singular_name?> =& $this-><?php echo $model_name?>->find($this->params['id']);
            }
        }else{
            $this->redirectToAction('listing');
        }

        if(!empty($this->params['<?php echo $singular_name?>'])){
            $this-><?php echo $singular_name?>->setAttributes($this->params['<?php echo $singular_name?>']);
            if($this->Request->isPost() && $this-><?php echo $singular_name?>->save()){
                $this->flash['notice'] = $this->t('<?php echo $model_name?> was successfully updated.');
                $this->redirectTo(array('action' => 'show', 'id' => $this-><?php echo $singular_name?>->getId()));
            }
        }
    }
    <?php } else { ?>

    function edit()
    {
        if (empty($this->params['id'])){
         $this->redirectToAction('listing');
        }
        if(!empty($this->params['<?php echo $singular_name?>']) && !empty($this->params['id'])){
            $this-><?php echo $singular_name?>->setAttributes($this->params['<?php echo $singular_name?>']);
            if($this->Request->isPost() && $this-><?php echo $singular_name?>->save()){
                $this->flash['notice'] = $this->t('<?php echo $model_name?> was successfully updated.');
                $this->redirectTo(array('action' => 'show', 'id' => $this-><?php echo $singular_name?>->getId()));
            }
        }
    }
    <?php } ?>
    
    function destroy()
    {
        if(!empty($this->params['id'])){
            $this-><?php echo $singular_name?> =& $this-><?php echo $model_name?>->find($this->params['id']);
            if($this->Request->isPost()){
                $this-><?php echo $singular_name?>->destroy();
                $this->redirectTo(array('action' => 'listing'));
            }
        }
    }  
}

?>
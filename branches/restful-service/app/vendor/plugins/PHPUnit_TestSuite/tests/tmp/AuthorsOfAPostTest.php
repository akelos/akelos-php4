<?php
PHPUnit_Akelos_autoload::addFolder(__FILE__);

class AuthorsOfAPostTest extends PHPUnit_Model_TestCase 
{
    function testPostIsWrittenByAnAuthor()
    {
        $this->useModel('Post');
        list(,$People) = $this->useModel('Person');

        $FirstPost = $this->createPost("title: First Post,written_by: {$People['sigmund']->id}");

        $Reloaded = $this->Post->find($FirstPost->id,array('include'=>'author'));
        $this->assertEquals($Reloaded->author->id, $People['sigmund']->find()->id);
        $this->assertEquals('Sigmund', $Reloaded->author->first_name);
    }
    
    function testDeleteCommentFromAPost()
    {
        list($Post)    = $this->useModel('Post');
        list($Comment) = $this->useModel('Comment');
        
        $MyPost    = $this->createPost('title: First Post');
        $MyComment = $this->createComment("name: My 2 cents,post_id: {$MyPost->id}");
        
        $Reloaded = $Post->find('first');
        $Reloaded->comment->delete($MyComment);
        
        $this->assertEquals(0,$Comment->count());
    }
}

?>
<?php
define('ESTRAIERPURE_DEBUG', 1);
//define('ESTRAIERPURE_USE_HTTP_STREAM', 1);
error_reporting(E_ALL & ~E_STRICT);
require_once 'estraierpure.php5';

// create and configure the node connecton object
$node = new EstraierPure_Node;
$node->set_url('http://localhost:1978/node/test');
$node->set_auth('admin', 'admin');

// create a document object
$doc = new EstraierPure_Document;

// add attributes to the document object
$doc->add_attr('@uri', 'http://estraier.example.com/example.txt');
$doc->add_attr('@title', 'Bridge Over The Troubled Water');

// add the body text to the document object
$doc->add_text('Like a bridge over the troubled water,');
$doc->add_text('I will ease your mind.');

// register the document object to the node
if (!$node->put_doc($doc)) {
    fprintf(STDERR, "error: %d\n", $node->status());
    if (EstraierPure_Utility::errorstack()->hasErrors()) {
        fputs(STDERR, print_r(EstraierPure_Utility::errorstack()->getErrors(), true));
    }
} else {
    fputs(STDOUT, "success.\n");
}
?>

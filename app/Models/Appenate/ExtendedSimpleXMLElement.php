<?php

namespace App\Models\Appenate;

use SimpleXMLElement;

class ExtendedSimpleXMLElement extends SimpleXMLElement
{
    public function addCData($cdata_text){
        $node = dom_import_simplexml($this);
        $no = $node->ownerDocument;
        $node->appendChild($no->createCDATASection($cdata_text));
    }
}

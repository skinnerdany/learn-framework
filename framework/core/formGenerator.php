<?php

class formGenerator
{
    private $dom;
    private $formElement;
    
    public function __construct($action = "/", $method = 'GET')
    {
        $this->dom = new DOMDocument('1.0', 'utf-8');
        
        $this->formElement = $this->dom->createElement('form');
        
        $actionAttribute = $this->dom->createAttribute('action');
        $actionAttribute->value = $action;
        
        $methodAttribute = $this->dom->createAttribute('method');
        $methodAttribute->value = $method;
        
        $this->formElement->appendChild($actionAttribute);
        $this->formElement->appendChild($methodAttribute);
        
        /*
         * DOMDocument
         *      formElement[DOMElement]
         *          actionAttribute[DOMAttr]
         *          methodAttribute[DOMAttr]
         * 
         * 12 <form ></form>
         * 14-15 action="/" 
         * 17-18 method="GET"
         * 20 <form action="/"></form>
         * 21 <form action="/" method="GET"></form>
         * <textarea>
         * ghdjfhksd sd jffsdj f;jsd flsdj;sdf
         * </textarea>
         */
    }
    
    public function attachInputByArray($attributes)
    {
        $input = $this->dom->createElement('input');

        foreach ($attributes as $name => $value) {
            $attr = $this->dom->createAttribute($name);
            $attr->value = $value;
            $input->appendChild($attr);
        }

        $this->formElement->appendChild($input);
    }

    public function attachInput($type, $name, $value = '')
    {
        $input = $this->dom->createElement('input');
        
        $typeAttribute = $this->dom->createAttribute('type');
        $typeAttribute->value = $type;
        
        $nameAttribute = $this->dom->createAttribute('name');
        $nameAttribute->value = $name;
        
        $valueAttribute = $this->dom->createAttribute('value');
        $valueAttribute->value = $value;
        
        $input->appendChild($typeAttribute);
        $input->appendChild($nameAttribute);
        $input->appendChild($valueAttribute);
        
        $this->formElement->appendChild($input);
    }
    
    public function generateForm()
    {
        $this->dom->appendChild($this->formElement);
        return $this->dom->saveHTML();
    }
}

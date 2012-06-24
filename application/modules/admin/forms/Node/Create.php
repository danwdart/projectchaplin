<?php
class Admin_Form_Node_Create extends Zend_Form
{
    public function init()
    {
        $name = new Zend_Form_Element_Text('Name');
        $name->setLabel('Name');
        $name->addValidators(array(
            new Zend_Validate_StringLength(array(
                'min' => 1,
                'max' => 255
            ))
        ));
        
        $ip = new Zend_Form_Element_Text('IP');
        $ip->setLabel('IP/Host');
        //$ip->addValidators(array(
        //    new Zend_Validate_Hostname()
        //));
        
        $submit = new Zend_Form_Element_Submit('Add');
        
        $this->addElements(array(
            $name,
            $ip,
            $submit
        ));
    }
}
?>

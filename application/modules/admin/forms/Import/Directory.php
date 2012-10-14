<?php
class Admin_Form_Import_Directory extends Zend_Form
{
	public function init()
	{
		$directory = new Zend_Form_Element_Text('Directory');
		$directory->setLabel('Directory');
		$submit = new Zend_Form_Element_Submit('Scan');
		$this->addElements(array(
			$directory,
			$submit
		));
	}
}
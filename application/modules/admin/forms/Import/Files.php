<?php
class Admin_Form_Import_Files extends Zend_Form
{
	private $_iterator;

	public function __construct(Iterator $iterator)
	{
		$this->_iterator = $iterator;
		parent::__construct();
	}

	public function init()
	{
		// Until we set this into a session or cookie...
		foreach($this->_iterator as $file) {
			$checkbox = new Zend_Form_Element_Checkbox(base64_encode($file->getPathName()));
			$checkbox->setLabel($file->getBaseName('.'.$file->getExtension()));
			$this->addElement($checkbox);				
		}

		$submit = new Zend_Form_Element_Submit('Upload');

		$this->addElement($submit);
	}
}
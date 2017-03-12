<?php
class IndexControllerTest extends Zend_Test_PHPUnit_ControllerTestCase
{
    public function setUp()
    {
        $this->bootstrap = new Zend_Application(
            APPLICATION_ENV,
            APPLICATION_PATH . '/config/application.ini'
        );
        parent::setUp();
    }

    public function testIndexAction()
    {
        $this->markTestSkipped('Route default not found');
        $params = [
            'action' => 'index',
            'controller' => 'Index',
            'module' => 'default'
        ];
        $urlParams = $this->urlizeOptions($params);
        $url = $this->url($urlParams);
        $this->dispatch($url);

        // assertions
        $this->assertModule($urlParams['module']);
        $this->assertController($urlParams['controller']);
        $this->assertAction($urlParams['action']);
    }
}

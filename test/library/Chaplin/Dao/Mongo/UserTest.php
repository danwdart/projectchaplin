<?php
class Chaplin_Dao_Mongo_UserTest extends Zend_Test_PHPUnit_ControllerTestCase
{
    public function testGetByUsernameAndPassword()
    {
        $strUsername = 'Username';
        $strPassword = 'Password';

        $arrQuery = array(
            Chaplin_Model_User::FIELD_Username => Chaplin_Model_User::encodeUsername($strUsername),
            Chaplin_Model_User::FIELD_Password => Chaplin_Model_User::encodePassword($strPassword)
        );

        $arrDocument = array(
            Chaplin_Model_User::FIELD_Username => Chaplin_Model_User::encodeUsername($strUsername),
            Chaplin_Model_User::FIELD_Password => Chaplin_Model_User::encodePassword($strPassword)
        );

        $mockCollection = \Mockery::mock('Mongo_Collection')
                                  ->shouldReceive('findOne')
                                    ->with($arrQuery)
                                    ->andReturn($arrDocument)
                                  ->mock();

        $dao = new Chaplin_Dao_Mongo_User();
        $dao->setMongoCollection($mockCollection);
        $modelUser = $dao->getByUsernameAndPassword($strUsername, $strPassword);
        $this->assertTrue($modelUser instanceof Chaplin_Model_User);
        $this->assertEquals(Chaplin_Model_User::encodeUsername($strUsername), $modelUser->getUsername());
    }

    public function testGetByUsernameAndPasswordThrowsException()
    {
        $strUsername = 'Username';
        $strPassword = 'Password';

        $arrQuery = array(
            Chaplin_Model_User::FIELD_Username => Chaplin_Model_User::encodeUsername($strUsername),
            Chaplin_Model_User::FIELD_Password => Chaplin_Model_User::encodePassword($strPassword)
        );

        $arrDocument = array(
            Chaplin_Model_User::FIELD_Username => Chaplin_Model_User::encodeUsername($strUsername),
            Chaplin_Model_User::FIELD_Password => Chaplin_Model_User::encodePassword($strPassword)
        );

        $mockCollection = \Mockery::mock('Mongo_Collection')
                                  ->shouldReceive('findOne')
                                    ->with($arrQuery)
                                    ->andReturn(null)
                                  ->mock();

        $dao = new Chaplin_Dao_Mongo_User();
        $dao->setMongoCollection($mockCollection);
        $this->setExpectedException('Chaplin_Dao_Exception_User_NotFound');
        $modelUser = $dao->getByUsernameAndPassword($strUsername, $strPassword);
    }

    public function testSave()
    {
        $strUsername = 'Username';
        $strPassword = 'Password';

        $modelUser = Chaplin_Model_User::create($strUsername, $strPassword);
        
        $arrCriteria = array(
            Chaplin_Model_User::FIELD_Username => Chaplin_Model_User::encodeUsername($strUsername),
        );

        $arrQuery = array(
            '$set' => array(
                Chaplin_Model_User::FIELD_Username => Chaplin_Model_User::encodeUsername($strUsername),
                Chaplin_Model_User::FIELD_Password => Chaplin_Model_User::encodePassword($strPassword)
            ),
            '$addToSet' => array()
        );
        
        $mockCollection = \Mockery::mock('Mongo_Collection')
                                  ->shouldReceive('updateArray')
                                   ->with($arrCriteria, $arrQuery)
                                  //->andReturnUsing(function($a, $b) use($arrQuery) { var_dump($b, $arrQuery);})
                                  ->mock();

        $dao = new Chaplin_Dao_Mongo_User();
        $dao->setMongoCollection($mockCollection);
        $dao->save($modelUser);
    }
}

<?php
/**
 * This file is part of Project Chaplin.
 *
 * Project Chaplin is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Project Chaplin is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with Project Chaplin. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package    Project Chaplin
 * @author     Dan Dart
 * @copyright  2012-2013 Project Chaplin
 * @license    http://www.gnu.org/licenses/agpl-3.0.html GNU AGPL 3.0
 * @version    git
 * @link       https://github.com/dandart/projectchaplin
**/
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

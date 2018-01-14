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
 * @package   ProjectChaplin
 * @author    Dan Dart <chaplin@dandart.co.uk>
 * @copyright 2012-2018 Project Chaplin
 * @license   http://www.gnu.org/licenses/agpl-3.0.html GNU AGPL 3.0
 * @version   GIT: $Id$
 * @link      https://github.com/danwdart/projectchaplin
**/
class Chaplin_Gateway_Channel
    extends Chaplin_Gateway_Abstract
{
    private $_daoChannel;

    public function __construct(Chaplin_Dao_Interface_Channel $daoChannel)
    {
        $this->_daoChannel = $daoChannel;
    }

    public function getAllChannels()
    {
        return $this->_daoChannel->getAllChannels();
    }

    public function getByChannelId($strChannelId)
    {
        return $this->_daoChannel->getByChannelId($strChannelId);
    }
    
    public function delete(Chaplin_Model_Channel $modelChannel)
    {
        return $this->_daoChannel->delete($modelChannel);
    }

    public function deleteById($strId)
    {
        return $this->_daoChannel->deleteById($strId);
    }

    public function save(Chaplin_Model_Channel $modelChannel)
    {
        return $this->_daoChannel->save($modelChannel);
    }
}

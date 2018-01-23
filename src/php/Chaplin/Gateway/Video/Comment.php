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

namespace Chaplin\Gateway\Video;

use Chaplin\Gateway\GatewayAbstract;
use Chaplin\Dao\Interfaces\Video\Comment as InterfaceVideoComment;
use Chaplin\Model\Video\Comment as ModelVideoComment;



class Comment extends GatewayAbstract
{
    private $_daoComment;

    public function __construct(InterfaceVideoComment $daoComment)
    {
        $this->_daoComment = $daoComment;
    }

    public function getById($strId)
    {
        return $this->_daoComment->getById($strId);
    }

    public function getByVideoId($strVideoId)
    {
        return $this->_daoComment->getByVideoId($strVideoId);
    }

    public function delete(ModelVideoComment $modelComment)
    {
        return $this->_daoComment->delete($modelComment);
    }

    public function deleteById($strCommentId)
    {
        return $this->_daoComment->deleteById($strCommentId);
    }

    public function save(ModelVideoComment $modelComment)
    {
        return $this->_daoComment->save($modelComment);
    }
}

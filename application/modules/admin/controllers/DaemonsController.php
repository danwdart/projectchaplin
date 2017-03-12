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
 * @author    Kathie Dart <chaplin@kathiedart.uk>
 * @copyright 2012-2017 Project Chaplin
 * @license   http://www.gnu.org/licenses/agpl-3.0.html GNU AGPL 3.0
 * @version   GIT: $Id$
 * @link      https://github.com/kathiedart/projectchaplin
**/
class Admin_DaemonsController extends Zend_Controller_Action
{
    public function startAction()
    {
        system('nohup '.APPLICATION_PATH.'/../cli.sh start 2>&1');
        exit();
    }

    public function stopAction()
    {
        system('nohup '.APPLICATION_PATH.'/../cli.sh stop 2>&1');
        exit();
    }

    public function restartAction()
    {
        system('nohup '.APPLICATION_PATH.'/../cli.sh restart 2>&1');
        exit();
    }

    public function statusAction()
    {
        system('nohup '.APPLICATION_PATH.'/../cli.sh status 2>&1');
        exit();
    }

    public function statusYoutubeAction()
    {
        system('nohup '.APPLICATION_PATH.'/../cli.sh status-youtube 2>&1');
        exit();
    }

    public function statusVimeoAction()
    {
        system('nohup '.APPLICATION_PATH.'/../cli.sh status-vimeo 2>&1');
        exit();
    }

    public function statusConvertAction()
    {
        system('nohup '.APPLICATION_PATH.'/../cli.sh status-convert 2>&1');
        exit();
    }

    public function statusNodeAction()
    {
        system('nohup '.APPLICATION_PATH.'/../cli.sh status-node 2>&1');
        exit();
    }
}

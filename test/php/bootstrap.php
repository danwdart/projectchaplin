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

// Define path to application directory
defined('APPLICATION_PATH')
    || define(
        'APPLICATION_PATH',
        realpath(
            dirname(__FILE__) . '/../../src/php'
        )
    );

// Define application environment
defined('APPLICATION_ENV')
    || define(
        'APPLICATION_ENV',
        (
            getenv('APPLICATION_ENV') ?? 'testing'
        )
    );


require_once APPLICATION_PATH.'/vendor/autoload.php';

Mockery::getConfiguration()->allowMockingNonExistentMethods(false);

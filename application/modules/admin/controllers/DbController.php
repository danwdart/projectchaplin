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
 * @copyright  2016 Project Chaplin
 * @license    http://www.gnu.org/licenses/agpl-3.0.html GNU AGPL 3.0
 * @version    git
 * @link       https://github.com/dandart/projectchaplin
**/
use Phinx\Console\PhinxApplication;
use Phinx\Wrapper\TextWrapper;

class Admin_DbController extends Zend_Controller_Action
{
    public function autoAction()
    {
        $app = new PhinxApplication();
        $wrap = new TextWrapper($app, [
            'configuration' => APPLICATION_PATH.'/../phinx.php',
            'parser' => 'php',
            'environment' => 'chaplin'
        ]);
        $output = $wrap->getStatus();
        $exitcode = $wrap->getExitCode();
    }

    public function statusAction()
    {
        $app = new PhinxApplication();
        $wrap = new TextWrapper($app, [
            'configuration' => APPLICATION_PATH.'/../phinx.php',
            'parser' => 'php',
            'environment' => 'chaplin'
        ]);
        $output = $wrap->getStatus();
        $exitcode = $wrap->getExitCode();
        if (0 == $exitcode) {
            echo 'OK';
        } else {
            echo 'Missing migrations exist.'.PHP_EOL.$output;
        }
        exit();
    }

    public function migrateAction()
    {
        $app = new PhinxApplication();
        $wrap = new TextWrapper($app, [
            'configuration' => APPLICATION_PATH.'/../phinx.php',
            'parser' => 'php',
            'environment' => 'chaplin'
        ]);
        $output = $wrap->getMigrate();
        if ($output) echo 'Done';
        else echo 'Error: '.$output;
        exit();
    }
}

<?php
declare(strict_types=1);

namespace Chaplin\Controller;

use Zend_Controller_Action as ZendController;
use Zend_Controller_Action_Exception as ActionException;

class Action extends ZendController
{
    public function __call($methodName, $args)
    {
        [$strMethod, $strAction] = preg_split(
            "/([A-Z][a-z]*)/",
            $methodName,
            0,
            PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY
        );

        $strMethod = strtoupper($strMethod);
        $strAction = strtolower($strAction);

        // TODO 405

        throw new ActionException(
            sprintf(
                "Action '$strAction' was not found using method $strMethod",
                $methodName
            ),
            404
        );
    }
}

<?php
namespace Chaplin\Application\Resource;

use Zend_Application_Resource_Modules as ResourceModules;

class ChaplinModules extends ResourceModules
{
    public function init()
    {
        $bootstraps = array();
        $bootstrap = $this->getBootstrap();
        $bootstrap->bootstrap('FrontController');
        $front = $bootstrap->getResource('FrontController');

        $modules = $front->getControllerDirectory();
        $default = $front->getDefaultModule();
        $curBootstrapClass = get_class($bootstrap);

        foreach ($modules as $module => $moduleDirectory) {
            $bootstrapClass = $this->_formatModuleName($module) . '\Bootstrap';
            if (!class_exists($bootstrapClass, false)) {
                $bootstrapPath = dirname($moduleDirectory) . '/Bootstrap.php';
                if (file_exists($bootstrapPath)) {
                    $eMsgTpl = 'Bootstrap w file found for module "%s" but bootstrap class "%s" not found';
                    include_once $bootstrapPath;
                    if (($default != $module)
                        && !class_exists($bootstrapClass, false)
                    ) {
                        throw new Zend_Application_Resource_Exception(
                            sprintf(
                                $eMsgTpl, $module, $bootstrapClass
                            )
                        );
                    } elseif ($default == $module) {
                        if (!class_exists($bootstrapClass, false)) {
                            $bootstrapClass = 'Bootstrap';
                            if (!class_exists($bootstrapClass, false)) {
                                throw new Zend_Application_Resource_Exception(
                                    sprintf(
                                        $eMsgTpl, $module, $bootstrapClass
                                    )
                                );
                            }
                        }
                    }
                } else {
                    continue;
                }
            }

            if ($bootstrapClass == $curBootstrapClass) {
                // If the found bootstrap class matches the one calling this
                // resource, don't re-execute.
                continue;
            }

            $bootstraps[$module] = $bootstrapClass;
        }

        return $this->_bootstraps = $this->bootstrapBootstraps($bootstraps);
    }

    /**
     * Format a module name to the module class prefix
     *
     * @param  string $name
     * @return string
     */
    protected function _formatModuleName($name)
    {
        $name = strtolower($name);
        $name = str_replace(array('-', '.'), ' ', $name);
        $name = ucwords($name);
        $name = str_replace(' ', '', $name);
        return "Chaplin\Module\\$name";
    }
}

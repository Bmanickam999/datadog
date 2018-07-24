<?php
/**
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @copyright Copyright (c) 2014-2016 Zend Technologies USA Inc. (http://www.zend.com)
 */

$config = [
    // Retrieve the list of modules for this application.
    'modules' => include __DIR__ . '/../config/modules.config.php',
    // This should be an array of paths in which modules reside.
    // If a string key is provided, the listener will consider that a module
    // namespace, the value of that key the specific path to that module's
    // Module class.
    'module_listener_options' => [
        'module_paths' => [
            './module',
            './vendor'
        ],
        // Using __DIR__ to ensure cross-platform compatibility. Some platforms --
        // e.g., IBM i -- have problems with globs that are not qualified.
        'config_glob_paths' => [
            __DIR__ . '/../config/autoload/{,*.}{global,local}.php',
            __DIR__ . '/local.php',
        ],
        'config_cache_enabled' => false,
        'module_map_cache_enabled' => false,
    ],
];

// Disable auditing for unit tests
foreach ($config['modules'] as $key => $value) {
    if ($value == 'ZF\Doctrine\Audit') {
        unset($config['modules'][$key]);
    }
}

return $config;

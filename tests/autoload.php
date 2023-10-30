<?php

/**
 * -------------------------------------------------------------------------
 * translate plugin for GLPI
 * -------------------------------------------------------------------------
 *
 * MIT License
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 * -------------------------------------------------------------------------
 * @copyright Copyright (C) 2023 by the translate plugin team.
 * @license   MIT https://opensource.org/licenses/mit-license.php
 * @link      https://github.com/pluginsGLPI/translate
 * -------------------------------------------------------------------------
 */

use Glpi\Cache\CacheManager;
use Glpi\Cache\SimpleCache;
use Symfony\Component\Cache\Adapter\ArrayAdapter;

ini_set('display_errors', 'On');
error_reporting(E_ALL);

define('GLPI_ROOT', __DIR__ . '/../../../../');
define('GLPI_CONFIG_DIR', __DIR__ . '/../../../../tests/config');
define('GLPI_VAR_DIR', __DIR__ . '/files');
define('GLPI_URI', (getenv('GLPI_URI') ?: 'http://localhost:8088'));
define('GLPI_LOG_DIR', GLPI_VAR_DIR . '/_log');
define(
    'PLUGINS_DIRECTORIES',
    [
        GLPI_ROOT . '/plugins',
        GLPI_ROOT . '/tests/fixtures/plugins',
    ]
);

define('TU_USER', '_test_user');
define('TU_PASS', 'PhpUnit_4');

global $CFG_GLPI, $GLPI_CACHE;

include(GLPI_ROOT . "/inc/based_config.php");

if (!file_exists(GLPI_CONFIG_DIR . '/config_db.php')) {
    die("\nConfiguration file for tests not found\n\nrun: bin/console glpi:database:install --config-dir=tests/config ...\n\n");
}

// Create subdirectories of GLPI_VAR_DIR based on defined constants
foreach (get_defined_constants() as $constant_name => $constant_value) {
    if (
        preg_match('/^GLPI_[\w]+_DIR$/', $constant_name)
        && preg_match('/^' . preg_quote(GLPI_VAR_DIR, '/') . '\//', $constant_value)
    ) {
        is_dir($constant_value) or mkdir($constant_value, 0755, true);
    }
}

//init cache
if (file_exists(GLPI_CONFIG_DIR . DIRECTORY_SEPARATOR . CacheManager::CONFIG_FILENAME)) {
    // Use configured cache for cache tests
    $cache_manager = new CacheManager();
    $GLPI_CACHE = $cache_manager->getCoreCacheInstance();
} else {
    // Use "in-memory" cache for other tests
    $GLPI_CACHE = new SimpleCache(new ArrayAdapter());
}

global $PLUGIN_HOOKS;

include_once GLPI_ROOT . 'inc/includes.php';
include_once GLPI_ROOT . '/vendor/autoload.php';
include_once __DIR__ . '/LogTest.php';

// $_SESSION['glpiprofiles'][4]['entities'] = [0 => ['id' => 0, 'is_recursive' => true]];
// $_SESSION['glpidefault_entity'] = 0;
$auth = new Auth();
$user = new User();
$auth->auth_succeded = true;
$user->getFromDB(2);
$auth->user = $user;
Session::init($auth);
Session::initEntityProfiles(2);
Session::changeProfile(4);

if (!file_exists(GLPI_LOG_DIR . '/php-errors.log')) {
    file_put_contents(GLPI_LOG_DIR . '/php-errors.log', '');
}

if (!file_exists(GLPI_LOG_DIR . '/sql-errors.log')) {
    file_put_contents(GLPI_LOG_DIR . '/sql-errors.log', '');
}

// @codingStandardsIgnoreStart
class GlpitestPHPerror extends \Exception
{
}
class GlpitestPHPwarning extends \Exception
{
}
class GlpitestPHPnotice extends \Exception
{
}
class GlpitestSQLError extends \Exception
{
}
// @codingStandardsIgnoreEnd

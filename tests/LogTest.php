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

use PHPUnit\Framework\TestCase;

class GLPIlogs extends TestCase
{
    public function testSQLlogs()
    {
        $filecontent = file_get_contents("../../files/_log/sql-errors.log");

        $this->assertEmpty($filecontent, 'sql-errors.log not empty: ' . $filecontent);
        // Reinitialize file
        file_put_contents("../../files/_log/sql-errors.log", '');
    }


    public function testPHPlogs()
    {
        $filecontent = file("../../files/_log/php-errors.log");
        $lines = [];
        foreach ($filecontent as $line) {
            if (
                !strstr($line, 'apc.')
                && !strstr($line, 'glpiphplog.DEBUG: Config::getCache()')
                && !strstr($line, 'Test logger')
            ) {
                $lines[] = $line;
            }
        }
        $this->assertEmpty(implode("", $lines), 'php-errors.log not empty: ' . implode("", $lines));
        // Reinitialize file
        file_put_contents("../../files/_log/php-errors.log", '');
    }
}

<?php

use PHPUnit\Framework\TestCase;

class ConfigLessonStepsTest extends TestCase
{
    private $config;

    /**
     * Set up the test
     *
     * @return void
     */
    public function setUp(): void
    {
        include 'config.php';
        $this->config = $discoverConfig;
    }

    /**
     * Test if each step in the "steps" array has the required keys
     *
     * @return void
     */
    public function testLessonStepsArrayStructure()
    {
        $requiredKeys = [
            'title' => 'string',
            'content' => 'string',
        ];
        $recognizedKeys = $requiredKeys + [
            'tooltipClass' => 'string',
            'tooltipPosition' => 'string',
            'element' => 'string',
            'actions' => 'array',
        ];

        foreach ($this->config['lessons'] as $lesson) {
            foreach ($lesson['steps'] as $step) {
                foreach ($requiredKeys as $key => $type) {
                    $this->assertArrayHasKey($key, $step);
                    $this->assertEquals($type, gettype($step[$key]));
                }

                foreach ($step as $key => $value) {
                    if (!in_array($key, array_keys($recognizedKeys))) {
                        $this->fail("The key '$key' is not recognized in the config for lesson '{$lesson['id']}'");
                    }

                    $this->assertEquals(gettype($value), gettype($step[$key]));
                }
            }
        }
    }

    /**
     * Test if the "actions" key is an array of arrays
     *
     * @return void
     */
    public function testActionsKeyIsAnArrayOfArrays()
    {
        foreach ($this->config['lessons'] as $lesson) {
            foreach ($lesson['steps'] as $step) {
                if (array_key_exists('actions', $step)) {
                    $this->assertIsArray($step['actions']);

                    foreach ($step['actions'] as $action) {
                        $this->assertIsArray($action);
                    }
                }
            }
        }
    }

    /**
     * Test if the actions are recognized and valid
     *
     * @return void
     */
    public function testActionsAreRecognizedAndValid()
    {
        $recognizedActions = [
            'dropdown-show' => [
                'dropdown' => 'string',
            ]
        ];

        foreach ($this->config['lessons'] as $lesson) {
            foreach ($lesson['steps'] as $step) {
                if (array_key_exists('actions', $step)) {
                    foreach ($step['actions'] as $action => $data) {
                        $this->assertContains($action, array_keys($recognizedActions));

                        foreach ($data as $key => $value) {
                            if (!in_array($key, array_keys($recognizedActions[$action]))) {
                                $this->fail("The attribute '$key' is not recognized in the config for action '$action' in lesson '{$lesson['id']}'");
                            }

                            $this->assertEquals(gettype($value), gettype($recognizedActions[$action][$key]));
                        }
                    }
                }
            }
        }
    }

    /**
     * Test if the "content" value is a valid file path or string
     *
     * @return void
     */
    public function testContentValueIsValidFilePathOrString()
    {
        foreach ($this->config['lessons'] as $lesson) {
            foreach ($lesson['steps'] as $step) {
                if (array_key_exists('content', $step)) {
                    $this->assertIsString($step['content']);

                    if (strpos($step['content'], 'file://') === 0) {
                        $this->assertFileExists('sources/' . str_replace('file://', '', $step['content']));
                    }
                }
            }
        }
    }

    /**
     * Test if the tooltipPosition value is valid
     *
     * @return void
     */
    public function testTooltipPositionValueIsValid()
    {
        $validValues = [
            'top',
            'right',
            'bottom',
            'left',
        ];

        foreach ($this->config['lessons'] as $lesson) {
            foreach ($lesson['steps'] as $step) {
                if (array_key_exists('tooltipPosition', $step)) {
                    $this->assertContains($step['tooltipPosition'], $validValues);
                }
            }
        }
    }
}

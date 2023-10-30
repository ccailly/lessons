<?php

use PHPUnit\Framework\TestCase;

class ConfigLessonsTest extends TestCase
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
     * Test if each lesson in the "lessons" array has the required keys
     *
     * @return void
     */
    public function testLessonsArrayStructure()
    {
        $requiredKeys = [
            'id' => 'string',
            'title' => 'string',
            'steps' => 'array',
        ];
        $recognizedKeys = $requiredKeys + [
            'category' => 'string',
            'description' => 'string',
            'points' => 'integer',
            'showEndingLesson' => 'boolean',
            'navigateTo' => 'string',
            'canView' => 'array',
        ];

        foreach ($this->config['lessons'] as $lesson) {
            foreach ($requiredKeys as $key => $type) {
                // For endingLesson, the "title" key is not required
                if (array_key_exists('endingLesson', $this->config) && $key === 'title' && $lesson['id'] === $this->config['endingLesson']) {
                    continue;
                }

                $this->assertArrayHasKey($key, $lesson);
                $this->assertEquals($type, gettype($lesson[$key]));
            }

            foreach ($lesson as $key => $value) {
                if (!in_array($key, array_keys($recognizedKeys))) {
                    $this->fail("The key '$key' is not recognized in the config for lesson '{$lesson['id']}'");
                }

                $this->assertEquals(gettype($value), gettype($lesson[$key]));
            }
        }
    }

    /**
     * If the "endingLesson" key does not exist, no lesson must have the "showEndingLesson" key.
     */
    public function testShowEndingLessonKey()
    {
        if (!array_key_exists('endingLesson', $this->config)) {
            foreach ($this->config['lessons'] as $lesson) {
                $this->assertArrayNotHasKey('showEndingLesson', $lesson);
            }
        } else {
            // The "endingLesson" key exists, "showEndingLesson" is usable
            $this->assertTrue(true);
        }
    }

    /**
     * If the "canView" key exists, check if it is an array of boolean or integer
     */
    public function testCanViewKey()
    {
        foreach ($this->config['lessons'] as $lesson) {
            if (array_key_exists('canView', $lesson)) {
                $this->assertIsArray($lesson['canView']);

                foreach ($lesson['canView'] as $canView) {
                    $this->assertTrue(is_bool($canView) || is_int($canView));
                }
            }
        }
    }
}

<?php

use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
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
     * Test if the "version" key exists in the config
     *
     * @return void
     */
    public function testVersionKeyExists()
    {
        $this->assertArrayHasKey('version', $this->config);
    }

    /**
     * Test if the "lessons" key exists in the config
     *
     * @return void
     */
    public function testLessonsKeyExists()
    {
        $this->assertArrayHasKey('lessons', $this->config);
    }

    /**
     * Test if the config contains only the expected keys
     *
     * @return void
     */
    public function testNonRecognizedKeys()
    {
        $keys = [
            'version',
            'lessons',
            'startingLesson',
            'endingLesson',
        ];

        array_map(function ($key) use ($keys) {
            if (!in_array($key, $keys)) {
                $this->fail("The key '$key' is not recognized in the config");
            }
        }, array_keys($this->config));

        // All keys are recognized, required to pass the test
        $this->assertTrue(true);
    }

    /**
     * Test if the "version" key is a string
     *
     * @return void
     */
    public function testVersionKeyIsString()
    {
        $this->assertIsString($this->config['version']);
    }

    /**
     * Test if the "lessons" key is an array
     *
     * @return void
     */
    public function testLessonsKeyIsArray()
    {
        $this->assertIsArray($this->config['lessons']);
    }

    /**
     * Test if the "lessons" array is not empty
     *
     * @return void
     */
    public function testLessonsArrayNotEmpty()
    {
        $this->assertNotEmpty($this->config['lessons']);
    }

    /**
     * If the "startingLesson" key exists, test if it is a string
     */
    public function testStartingLessonKeyIsString()
    {
        if (array_key_exists('startingLesson', $this->config)) {
            $this->assertIsString($this->config['startingLesson']);
        } else {
            $this->markTestSkipped('The "startingLesson" key does not exist in the config');
        }
    }

    /**
     * If the "endingLesson" key exists, test if it is a string
     */
    public function testEndingLessonKeyIsString()
    {
        if (array_key_exists('endingLesson', $this->config)) {
            $this->assertIsString($this->config['endingLesson']);
        } else {
            $this->markTestSkipped('The "endingLesson" key does not exist in the config');
        }
    }

    /**
     * If the "startingLesson" key exists, test if it is a valid lesson id
     */
    public function testStartingLessonKeyIsValidLessonId()
    {
        if (array_key_exists('startingLesson', $this->config)) {
            $this->assertContains($this->config['startingLesson'], array_column($this->config['lessons'], 'id'));
        } else {
            $this->markTestSkipped('The "startingLesson" key does not exist in the config');
        }
    }

    /**
     * If the "endingLesson" key exists, test if it is a valid lesson id
     */
    public function testEndingLessonKeyIsValidLessonId()
    {
        if (array_key_exists('endingLesson', $this->config)) {
            $this->assertContains($this->config['endingLesson'], array_column($this->config['lessons'], 'id'));
        } else {
            $this->markTestSkipped('The "endingLesson" key does not exist in the config');
        }
    }
}

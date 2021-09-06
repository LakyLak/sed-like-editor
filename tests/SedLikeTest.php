<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
// use app\core\Request;

final class SedLikeTest extends TestCase
{
    protected function setUp()
    {
        system('php sedlike.php -i s/robot/human/ file1.txt');
        if (file_exists('file2.txt')) {
            system('rm file2.txt');
        } 
    }

    public function testChangeWithOutput(): void
    {
        $content = file_get_contents(__DIR__ . '/../file1.txt');

        $output = system('php sedlike.php s/human/robot/ file1.txt');

        $expected = "Some robot emotional expressions, such as laughter, are not robot only and can be found nonrobot primates."; 

        $this->assertNotEquals($content, $output);
        $this->assertEquals($expected, $output);
    }

    public function testChangeWithOutputToAnotherFile(): void
    {
        $contentOfFirstFile = file_get_contents(__DIR__ . '/../file1.txt');
        
        $output = system('php sedlike.php s/human/robot/ file1.txt file2.txt');
        $contentOfSecondFile = file_get_contents(__DIR__ . '/../file2.txt');

        $expected = "Some robot emotional expressions, such as laughter, are not robot only and can be found nonrobot primates."; 

        $this->assertNotEquals($contentOfFirstFile, $contentOfSecondFile);
        $this->assertEquals($expected, $output);
    }

    public function testChangeWithoutOutput(): void
    {
        $content = file_get_contents(__DIR__ . '/../file1.txt');

        $output = system('php sedlike.php -i s/human/robot/ file1.txt');

        $expected = ""; 

        $this->assertNotEquals($content, $output);
        $this->assertEquals($expected, $output);
    }

    public function testChangeWithoutOutputToAnotherFile(): void
    {
        $contentOfFirstFile = file_get_contents(__DIR__ . '/../file1.txt');
        
        $output = system('php sedlike.php -i s/human/robot/ file1.txt file2.txt');
        $contentOfSecondFile = file_get_contents(__DIR__ . '/../file2.txt');

        $expected = ""; 

        $this->assertNotEquals($contentOfFirstFile, $contentOfSecondFile);
        $this->assertEquals($expected, $output);
    }
}

<?php
use Pantaovay\WechatLikeGroupAvatar\Generator;

class GeneratorTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    public function testGenerate()
    {
        $dataPath = dirname(__DIR__) .  DIRECTORY_SEPARATOR . '_data';
        $backgroundColor = [242, 242, 242];
        $destImageSize = 120;
        $imageMargin = 2;

        for($i = 1; $i <= 9; $i++) {
            $srcImagesPath = [];
            for ($j = 1; $j <= $i; $j++) {
                $srcImagesPath[] = $dataPath . DIRECTORY_SEPARATOR . $j . '.png';
            }

            $combineImage = new Generator(
                $srcImagesPath,
                $dataPath . DIRECTORY_SEPARATOR . "combine_{$i}_result.png",
                $backgroundColor,
                $destImageSize,
                $imageMargin
            );
            $combineImage->generate();

            $this->tester->assertTrue(file_exists($dataPath . DIRECTORY_SEPARATOR . "combine_{$i}_result.png"));
            //unlink($dataPath . DIRECTORY_SEPARATOR . "combine_{$i}_result.png");
        }

        $combineImage = new Generator(
            [$dataPath . DIRECTORY_SEPARATOR . 'test.jpg'],
            $dataPath . DIRECTORY_SEPARATOR . "test_jpg_result.png",
            $backgroundColor,
            $destImageSize,
            $imageMargin
        );
        $combineImage->generate();
        //unlink($dataPath . DIRECTORY_SEPARATOR . "test_jpg_result.png");

        $combineImage = new Generator(
            [
                'http://image.yoloyolo.tv/headimgs/00015606-ba10-11e5-be94-00163e06137c.jpeg@60w_60h.png',
                'http://image.yoloyolo.tv/headimgs/0001a57a-bb64-11e5-90dd-00163e06137c.jpeg@60w_60h.png',
                'http://image.yoloyolo.tv/headimgs/0001e2d0-bae0-11e5-a39c-00163e06137c.jpeg@60w_60h.png',
                'http://image.yoloyolo.tv/headimgs/0004ff3e-d23b-11e5-9ea1-00163e00014d.jpeg@60w_60h.png',
                'http://image.yoloyolo.tv/headimgs/000859e8-2d50-11e6-8305-00163e06137c.gif@60w_60h.png',
                'http://image.yoloyolo.tv/headimgs/000993ba-e08b-11e5-9a09-00163e06137c.jpeg@60w_60h.png',
                'http://image.yoloyolo.tv/headimgs/000ac5ee-da9d-11e5-9054-00163e06137c.jpeg@60w_60h.png',
                'http://image.yoloyolo.tv/headimgs/000bef80-bda3-11e5-80f4-00163e00014d.jpeg@60w_60h.png',
                'http://image.yoloyolo.tv/headimgs/000c9f76-2fbc-11e6.jpeg@60w_60h.png',
            ],
            $dataPath . DIRECTORY_SEPARATOR . "test_url_result.png",
            $backgroundColor,
            $destImageSize,
            $imageMargin
        );
        $combineImage->generate();
        //unlink($dataPath . DIRECTORY_SEPARATOR . "test_url_result.png");

        $combineImage = new Generator(
            [
                'http://image.yoloyolo.tv/headimgs/00015606-ba10-11e5-be94-00163e06137c.jpeg',
                'http://image.yoloyolo.tv/headimgs/0001a57a-bb64-11e5-90dd-00163e06137c.jpeg',
                'http://image.yoloyolo.tv/headimgs/0001e2d0-bae0-11e5-a39c-00163e06137c.jpeg',
                'http://image.yoloyolo.tv/headimgs/0004ff3e-d23b-11e5-9ea1-00163e00014d.jpeg',
                'http://image.yoloyolo.tv/headimgs/000859e8-2d50-11e6-8305-00163e06137c.gif@60w_60h.png',
                'http://image.yoloyolo.tv/headimgs/000993ba-e08b-11e5-9a09-00163e06137c.jpeg@60w_60h.png',
                'http://image.yoloyolo.tv/headimgs/000ac5ee-da9d-11e5-9054-00163e06137c.jpeg@60w_60h.png',
                'http://image.yoloyolo.tv/headimgs/000bef80-bda3-11e5-80f4-00163e00014d.jpeg@60w_60h.png',
                'http://image.yoloyolo.tv/headimgs/000c9f76-2fbc-11e6-a2ce-00163e00014d.jpeg@60w_60h.png',
            ],
            $dataPath . DIRECTORY_SEPARATOR . "test_url_result_2.png",
            $backgroundColor,
            $destImageSize,
            $imageMargin
        );
        $combineImage->generate();
        //unlink($dataPath . DIRECTORY_SEPARATOR . "test_url_result_2.png");

        $combineImage = new Generator(
            [
                $dataPath . DIRECTORY_SEPARATOR . '1.png',
                $dataPath . DIRECTORY_SEPARATOR . '2.png',
                $dataPath . DIRECTORY_SEPARATOR . '3.png',
                $dataPath . DIRECTORY_SEPARATOR . '4.png',
                'http://image.yoloyolo.tv/headimgs/000859e8-2d50-11e6-8305-00163e06137c.gif@60w_60h.png',
                'http://image.yoloyolo.tv/headimgs/000993ba-e08b-11e5-9a09-00163e06137c.jpeg@60w_60h.png',
                'http://image.yoloyolo.tv/headimgs/000ac5ee-da9d-11e5-9054-00163e06137c.jpeg@60w_60h.png',
                'http://image.yoloyolo.tv/headimgs/000bef80-bda3-11e5-80f4-00163e00014d.jpeg@60w_60h.png',
                'http://image.yoloyolo.tv/headimgs/000c9f76-2fbc-11e6-a2ce-00163e00014d.jpeg@60w_60h.png',
            ],
            $dataPath . DIRECTORY_SEPARATOR . "test_url_result_3.png",
            $backgroundColor,
            $destImageSize,
            $imageMargin
        );
        $combineImage->generate();
        //unlink($dataPath . DIRECTORY_SEPARATOR . "test_url_result_3.png");
    }
}

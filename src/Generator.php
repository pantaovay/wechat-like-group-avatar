<?php
namespace Pantaovay\WechatLikeGroupAvatar;

class Generator
{
    private $srcImagesPath;
    private $destImagePath;
    private $descImageBackgroundColor;

    private $canvas;
    private $canvasSize;

    private $imageMargin;
    private $imageFullSize;
    private $imageMiddleSize;
    private $imageSmallSize;

    public function __construct(array $srcImagesPath, $destImagePath, array $backgroundColor, $destImageSize, $imageMargin)
    {
        $validSrcImagesPath = [];
        foreach ($srcImagesPath as $srcImagePath) {
            if (($urlComponents = parse_url($srcImagePath)) == false || !isset($urlComponents['scheme'])) {
                if (file_exists($srcImagePath) && self::getMimeType($srcImagePath) == 'image/png') {
                    $validSrcImagesPath[] = $srcImagePath;
                }
            } else {
                $validSrcImagesPath[] = $srcImagePath;
            }
        }

        $this->srcImagesPath = array_slice($validSrcImagesPath, 0, 9);
        $this->destImagePath = trim($destImagePath);
        $this->descImageBackgroundColor = $backgroundColor;

        $this->canvasSize = $destImageSize;

        $this->imageMargin = $imageMargin;
        $this->imageFullSize = $this->canvasSize;
        $this->imageMiddleSize = $this->canvasSize / 2 - 2 * $this->imageMargin;
        $this->imageSmallSize = $this->canvasSize / 3 - 2 * $this->imageMargin;
    }

    public function __destruct()
    {
        if ($this->canvas != null) {
            imagedestroy($this->canvas);
        }
    }

    public function generate()
    {
        $this->createCanvas();
        $this->combine();
        if (imagepng($this->canvas, $this->destImagePath) === false) {
            throw new Exception('Output dest image failed');
        }
    }

    private function createCanvas()
    {
        if (($this->canvas = imagecreatetruecolor($this->canvasSize, $this->canvasSize)) === false) {
            throw new Exception('Create true color image failed');
        }
        $backgroundColor = imagecolorallocate(
            $this->canvas,
            $this->descImageBackgroundColor[0],
            $this->descImageBackgroundColor[1],
            $this->descImageBackgroundColor[2]
        );
        if (imagefill($this->canvas, 0, 0, $backgroundColor) === false) {
            throw new Exception('Flood fill canvas failed');
        }
    }

    private function combine()
    {
        switch (count($this->srcImagesPath)) {
            case 0:
                return;
            case 1:
                $this->putImageOnCanvas($this->srcImagesPath[0], 0, 0, $this->imageFullSize);

                return;
            case 2:
                $this->putImageOnCanvas($this->srcImagesPath[0], 0, $this->canvasSize / 4, $this->imageMiddleSize);
                $this->putImageOnCanvas($this->srcImagesPath[1], $this->canvasSize / 2, $this->canvasSize / 4, $this->imageMiddleSize);

                return;
            case 3:
                $this->putTopImagesForFourGrids(array_slice($this->srcImagesPath, 0, 2));
                $this->putImageOnCanvas($this->srcImagesPath[2], $this->canvasSize / 4, $this->canvasSize / 2, $this->imageMiddleSize);

                return;
            case 4:
                $this->putTopImagesForFourGrids(array_slice($this->srcImagesPath, 0, 2));
                $this->putImageOnCanvas($this->srcImagesPath[2], 0, $this->canvasSize / 2, $this->imageMiddleSize);
                $this->putImageOnCanvas($this->srcImagesPath[3], $this->canvasSize / 2, $this->canvasSize / 2, $this->imageMiddleSize);

                return;
            case 5:
                $this->putTopImagesForSixGrids(array_slice($this->srcImagesPath, 0, 3));
                $this->putImageOnCanvas($this->srcImagesPath[3], $this->canvasSize / 6, $this->canvasSize / 2, $this->imageSmallSize);
                $this->putImageOnCanvas($this->srcImagesPath[4], $this->canvasSize / 2, $this->canvasSize / 2, $this->imageSmallSize);

                return;
            case 6:
                $this->putTopImagesForSixGrids(array_slice($this->srcImagesPath, 0, 3));
                $this->putImageOnCanvas($this->srcImagesPath[3], 0, $this->canvasSize / 2, $this->imageSmallSize);
                $this->putImageOnCanvas($this->srcImagesPath[4], $this->canvasSize / 3, $this->canvasSize / 2, $this->imageSmallSize);
                $this->putImageOnCanvas($this->srcImagesPath[5], $this->canvasSize / 3 * 2, $this->canvasSize / 2, $this->imageSmallSize);

                return;
            case 7:
                $this->putTopImagesForNineGrids(array_slice($this->srcImagesPath, 0, 6));
                $this->putImageOnCanvas($this->srcImagesPath[6], $this->canvasSize / 3, $this->canvasSize / 3 * 2, $this->imageSmallSize);

                return;
            case 8:
                $this->putTopImagesForNineGrids(array_slice($this->srcImagesPath, 0, 6));
                $this->putImageOnCanvas($this->srcImagesPath[6], $this->canvasSize / 6, $this->canvasSize / 3 * 2, $this->imageSmallSize);
                $this->putImageOnCanvas($this->srcImagesPath[7], $this->canvasSize / 2, $this->canvasSize / 3 * 2, $this->imageSmallSize);

                return;
            case 9:
                $this->putTopImagesForNineGrids(array_slice($this->srcImagesPath, 0, 6));
                $this->putImageOnCanvas($this->srcImagesPath[6], 0, $this->canvasSize / 3 * 2, $this->imageSmallSize);
                $this->putImageOnCanvas($this->srcImagesPath[7], $this->canvasSize / 3, $this->canvasSize / 3 * 2, $this->imageSmallSize);
                $this->putImageOnCanvas($this->srcImagesPath[8], $this->canvasSize / 3 * 2, $this->canvasSize / 3 * 2, $this->imageSmallSize);

                return;
            default:
                return;
        }
    }

    private function putTopImagesForFourGrids(array $srcImagesPath)
    {
        $this->putImageOnCanvas($srcImagesPath[0], 0, 0, $this->imageMiddleSize);
        $this->putImageOnCanvas($srcImagesPath[1], $this->canvasSize / 2, 0, $this->imageMiddleSize);
    }

    private function putTopImagesForSixGrids(array $srcImagesPath)
    {
        $this->putImageOnCanvas($srcImagesPath[0], 0, $this->canvasSize / 6, $this->imageSmallSize);
        $this->putImageOnCanvas($srcImagesPath[1], $this->canvasSize / 3, $this->canvasSize / 6, $this->imageSmallSize);
        $this->putImageOnCanvas($srcImagesPath[2], $this->canvasSize / 3 * 2, $this->canvasSize / 6, $this->imageSmallSize);
    }

    private function putTopImagesForNineGrids(array $srcImagesPath)
    {
        $this->putImageOnCanvas($srcImagesPath[0], 0, 0, $this->imageSmallSize);
        $this->putImageOnCanvas($srcImagesPath[1], $this->canvasSize / 3, 0, $this->imageSmallSize);
        $this->putImageOnCanvas($srcImagesPath[2], $this->canvasSize / 3 * 2, 0, $this->imageSmallSize);
        $this->putImageOnCanvas($srcImagesPath[3], 0, $this->canvasSize / 3, $this->imageSmallSize);
        $this->putImageOnCanvas($srcImagesPath[4], $this->canvasSize / 3, $this->canvasSize / 3, $this->imageSmallSize);
        $this->putImageOnCanvas($srcImagesPath[5], $this->canvasSize / 3 * 2, $this->canvasSize / 3, $this->imageSmallSize);
    }

    private function putImageOnCanvas($srcImagePath, $dstX, $dstY, $dstSize)
    {
        if (($srcImage = @imagecreatefrompng($srcImagePath)) === false) {
            return;
        }

        if ($dstSize == $this->imageFullSize) {
            $result = imagecopyresampled(
                $this->canvas,
                $srcImage,
                $dstX,
                $dstY,
                0,
                0,
                $dstSize,
                $dstSize,
                imagesx($srcImage),
                imagesy($srcImage)
            );
        } else {
            $result = imagecopyresampled(
                $this->canvas,
                $srcImage,
                $dstX + $this->imageMargin,
                $dstY + $this->imageMargin,
                0,
                0,
                $dstSize,
                $dstSize,
                imagesx($srcImage),
                imagesy($srcImage)
            );
        }
        if ($result === false) {
            throw new Exception('Copy and resize part of an image with resampling failed');
        }
        if (imagedestroy($srcImage) === false) {
            throw new Exception('Destroy src image failed');
        }
    }

    private static function getMimeType($filename)
    {
        if (!file_exists($filename)) {
            throw new Exception('File not exists');
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        if (!$finfo) {
            throw new Exception('System error');
        }

        $mimeType = finfo_file($finfo, $filename);
        if (!$mimeType) {
            throw new Exception('System error');
        }

        if (!finfo_close($finfo)) {
            throw new Exception('System error');
        }

        return $mimeType;
    }
}

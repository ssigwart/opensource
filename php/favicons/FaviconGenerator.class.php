<?php

/**
 * @license MIT License
 * Copyright (c) 2018 Stephen Sigwart
 */

/**
 * Favicon Generator
 *
 * Example usage:
 * try
 * {
 * 	$faviconGenerator = new FaviconGenerator();
 * 	$faviconGenerator->convertImageToFavicon('srcImg.png', 'favicon.ico', FaviconGenerator::WEB_FAVICON_SIZES);
 * } catch (Exception $e) {
 * 	print "Failed to generate image.\n";
 * 	print $e;
 * }
 */
class FaviconGenerator
{
	/** Most Favicon sizes (must be ordered largest to smallest) */
	const MOST_FAVICON_SIZES = [228, 196, 192, 180, 167, 152, 144, 128, 120, 96, 76, 57, 32];
	/** Common Favicon sizes (must be ordered largest to smallest) */
	const COMMON_FAVICON_SIZES = [192, 152, 32];
	/**  Web favicon sizes (must be ordered largest to smallest) */
	const WEB_FAVICON_SIZES = [64, 48, 32, 16];

	/**
	 * Generate a favicon from a valid image
	 *
	 * @param string $srcFilename Source image filename
	 * @param string $destFilename Destination image filename
	 * @param int[] Favicon sizes (must be ordered largest to smallest)
	 *
	 * @throws ImagickException
	 */
	public function convertImageToFavicon($srcFilename, $destFilename, array $sizes)
	{
		$imgs = [];

		// Get original image
		$srcImg = new Imagick($srcFilename);
		$imgs[] = $srcImg;
		$srcImgWidth = $srcImg->getImageWidth();
		$srcImgHeight = $srcImg->getImageHeight();
		$srcImgMaxDimensionSize = max($srcImgHeight, $srcImgWidth);

		// Resize to largest size so we're not working with huge images
		$srcImg->scaleImage($sizes[0], 0);

		// Start creating new image
		$destImg = new Imagick();
		$destImg->setFormat('ico');
		$imgs[] = $destImg;

		// Add each applicable width
		foreach ($sizes as $size)
		{
			if ($size <= $srcImgMaxDimensionSize)
			{
				$tmpImg = clone $srcImg;
				$tmpImg->scaleImage($size, 0);
				$destImg->addImage($tmpImg);
				$tmpImg->destroy();
			}
		}

		// Save image
		$destImg->writeImages($destFilename, true);

		// Destroy images
		foreach ($imgs as $img)
			$img->destroy();
	}
}
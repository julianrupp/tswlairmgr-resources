<?php
# Requires PHP 5 with GD library.

$src_dir = dirname(__FILE__).'/lairbosses_xray_all_bwsolid';
$dst_dir = dirname(__FILE__).'/lairbosses_xray_all_wtransparent';

$files = scandir($src_dir);
foreach($files as $file)
{
	if(preg_match('/\.png$/', $file))
	{
		$src = imagecreatefromstring(file_get_contents($src_dir.'/'.$file));

		$im = imagecreatetruecolor(imagesx($src), imagesy($src));
		imagesavealpha($im, true);
		imageantialias($im, true);

		$c_bg = imagecolorallocatealpha($im, 0xff, 0xff, 0xff, 0x7f);
		imagealphablending($im, false);
		imagefilledrectangle($im, 0, 0, imagesx($im), imagesy($im), $c_bg);
		imagealphablending($im, true);

		for($y=0; $y<imagesy($src); $y++)
		{
			for($x=0; $x<imagesx($src); $x++)
			{
				$c_src = imagecolorsforindex($src, imagecolorat($src, $x, $y));
				$brightness = ($c_src['red'] + $c_src['green'] + $c_src['blue']) / 3;

				$c = imagecolorallocatealpha($im, 0xff, 0xff, 0xff, 127-floor($brightness/2));
				imagealphablending($im, false);
				imagesetpixel($im, $x, $y, $c);
				imagealphablending($im, true);
			}
		}

		imagepng($im, $dst_dir.'/'.$file);
	}
}
?>
<?php

use Intervention\Image\ImageManager;

require 'vendor/autoload.php';

function pushImage($uri)
{
    header("Link: <{$uri}>; rel=preload; as=image", false);

    // create an image manager instance with favored driver
    $manager = new ImageManager(array('driver' => 'imagick'));

    $Image = $manager->make('.'.$uri);

    $xyScale = $Image->Width() / $Image->Height();

    $xResize = 9;

    $yResize = ceil(9 / $xyScale);

    // to finally create image instances
    $imageBase64 = (string) $Image->resize($xResize, $yResize)->encode('data-url');

    // $imageBase64 = 'data:image/jpg;base64,'.base64_encode($image);

//     return <<<HTML
// <img src="{$imageBase64}" class="default" data-src="{$uri}" style="object-fit: color;width: 100%;">
// HTML;

    return <<<HTML
<div class="cf">
  <img class="bottom" data-src="{$uri}" />
  <img class="top" src="{$imageBase64}" />
</div>
HTML;
}

$image1 = pushImage('/1.jpg');
$image2 = pushImage('/2.jpg');
?>

<html>

<head>
	<meta charset="utf-8">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=0" />

	<title>Image Stuffs</title>

	<style>

		/*@keyframes fadeIn {
		  0% { opacity: 0.4;}
		  100% { opacity: 1;}
		}

		img.default {
			opacity: .9;
		}

		.fadeIn{
		    -webkit-animation-name:fadeIn;
		    -moz-animation-name:fadeIn;
		    -o-animation-name:fadeIn;
		    animation-name:fadeIn;
			-webkit-animation-duration: 1s;
		}*/

		.cf {
			position:relative;
			margin:5px 0;
			padding: 0;
		}

		.cf img {
			object-fit: cover;
			width: 100%;
			-webkit-transition: opacity 1.3s ease-in-out;
			-moz-transition: opacity 1.3s ease-in-out;
			-o-transition: opacity 1.3s ease-in-out;
			transition: opacity 1.3s ease-in-out;
		}

		.cf img.bottom {
			position:absolute;
  		  	left:0;
		}

		.cf img.top.fadeout {
			/*position: absolute;*/
			opacity:0;
		}

	</style>
</head>
<body>

<h1 style="margin: 0 auto;text-align: center;">Image Stuffs</h1>

<?php echo $image1;?>
<?php echo $image2;?>

<script>
	// window.onload = function() {
	//
	// 	var images = document.querySelectorAll('img');
	// 	var imageLoad = undefined;
	//
	// 	for(var i = 0; i < images.length; i++) {
	// 		// console.log();
	//
	// 		imageLoad = function() {
	// 			var image = images[i];
	// 			var img = new Image();
	// 			img.src = image.getAttribute('data-src');
	//
	// 			img.onload = function() {
	// 				image.setAttribute('src', img.src);
	// 				image.className = 'fadeIn';
	// 			}
	// 		};
	//
	// 		imageLoad();
	//
	// 	}
	//
	// };

	window.onload = function() {
		var cfs = document.querySelectorAll('.cf');
		var imageLoad = undefined;

		for(var i = 0; i < cfs.length; i++) {

			imageLoad = function () {
				var imageBottom = cfs[i].querySelector('img.bottom');
				var imageTop = cfs[i].querySelector('img.top');

				var img = new Image();
				img.src = imageBottom.getAttribute('data-src');

				img.onload = function() {
					imageBottom.setAttribute('src', img.src);
					imageTop.className = 'top fadeout';

				}
			}

			imageLoad();
		}

		// document.querySelector('img.top').addEventListener('transitionend', function(event) {
		// 	console.log(event.target.style.display = "none");
		// }, false);

	}
</script>

</body>
</html>

<?php

$maxWidth   =   2000;
$maxHeight  =   null;
$quality    =   99; // png'de 100 değeri hata veriyor

/**
 * Imajı yeniden boyutlandırma - genişliğe göre oranı koruyarak.
 * @param string $sourceImage kaynak JPEG/PNG imaj dosyası
 * @param string $targetImage hedef JPEG/PNG imaj dosyası
 * @param int $maxWidth hedeflenen imaj maksimum genişliği
 * @param int $maxHeight hedeflenen imaj maksimum genişliği
 * @param int $quality hedeflenen imaj kalitesi (0-100) // png'de 100 değeri hata veriyor
 * @return bool
 */

function resizeImage($sourceImage, $targetImage, $maxWidth, $maxHeight, $quality)
{
    $isValid = @getimagesize($sourceImage);

    if (!$isValid)
    {
        return false;
    }

    // İmajın özellikleri belirleniyor
    list($origWidth, $origHeight, $type) = getimagesize($sourceImage);

    if ($maxWidth == 0)
    {
        $maxWidth  = $origWidth;
    }

    if ($maxHeight == 0)
    {
        $maxHeight = $origHeight;
    }

    // Hedef imaj için oran hesaplanıyor
    $widthRatio = $maxWidth / $origWidth;
    $heightRatio = $maxHeight / $origHeight;

    var_dump($widthRatio);
    var_dump($heightRatio);
    // Yeni boyutların hesaplanmasında kullanılacak oran
   //$ratio = min($widthRatio, $heightRatio); // resim genişliği 2000'den küçük ve büyütülme isteniyorsa bu hesaplama kullanılmalıdır.
   $ratio = $maxWidth / $origWidth; // eğer resim genişliği 2000'den küçükse ve boyutlar korunacaksa bu hesaplama kullanılmamalıdır.  


    // Yeni imajın boyutları hesaplanıyor
    $newWidth  = (int)$origWidth  * $ratio;
    $newHeight = (int)$origHeight * $ratio;

    // Hesaplanan ölçülerde imaj oluşturuluyor
    $newImage = imagecreatetruecolor($newWidth, $newHeight);

    // Obtain image from given source file.
    switch(strtolower(image_type_to_mime_type($type)))
    {
        case 'image/jpeg':                      
            $image = @imagecreatefromjpeg($sourceImage);            
            if (!$image)
            {
                return false;
            }

            imagecopyresampled($newImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight); 

            if(imagejpeg($newImage,$targetImage,$quality))
            {
                // Free up the memory.
                imagedestroy($image);
                imagedestroy($newImage);
                return true;
            }            
        break;
        
        case 'image/png':
            $image = @imagecreatefrompng($sourceImage);

            if (!$image)
            {
                return false;
            }

            imagecopyresampled($newImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);

            if(imagepng($newImage,$targetImage, floor($quality / 10)))
            {
                
                imagedestroy($image);
                imagedestroy($newImage);
                return true;
            }
        break;
                
		default:
			return false;
       }
}


//resizeImage('source\\jpg\\Canon_40D.jpg','processed\\jpg\\Canon_40D.jpg', 2000);

//resizeImage('source\\jpg\\corrupted.jpg','processed\\jpg\\corrupted.jpg', 2000);

//resizeImage('source\\jpg\\corrupted.png','processed\\jpg\\corrupted.png', 2000);

//resizeImage('source\\map.png','processed\\jpg\\map.png', $maxWidth, $maxHeight, $quality);

//resizeImage('source\\jpg\\corrupted.jpg','processed\\jpg\\corrupted.jpg', $maxWidth, $maxHeight, $quality);

resizeImage('jepege.jpg','processed\\jepege.jpg', $maxWidth, $maxHeight, $quality);

resizeImage('penege.png','processed\\penege.png', $maxWidth, $maxHeight, $quality);
?>
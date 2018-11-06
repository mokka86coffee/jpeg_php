<?php 

setlocale(LC_COLLATE, 'ru_RU');

$html = file_get_contents('http://master-1.ru/catalog/show_group2/master-3');


$pattern = '/\bzoo-image="[^"]*\.(jpg|png)">\b/iUs';
$pattern_price = '/\bprice-one(\'|\")>[\d]+</iUs';
$pattern_name = '/\bname-one mb0(\'|\")>[^<]+</iUs';
$pattern_text = '/\bname-one mb0(\'|\")>[^<]+<.p>[^<]*<p>.*<\/p/iUs';
$pattern_text = '/\bname-one mb0(\'|\")>[^<]+<\/p>[^<]*<p>(.|\s)+(.|[^\/])*<\/p/iUs';
// $pattern_text_prev = '/\bname-one mb0(\'|\")+>([^\/]|\.)+<\/p>(.|\s)+<p>/iUs';
$pattern_text_prev = '/\bname-one mb0(\'|\")+>([^\/]|.)*<\/p>.*<p>/iUs';




preg_match_all($pattern, $html, $pics);
preg_match_all($pattern_price, $html, $prices);
preg_match_all($pattern_name, $html, $names);

preg_match_all($pattern_text, $html, $txts);


$str = implode(' ', $txts[0]);
$str = preg_replace($pattern_text_prev, '', $str);

$pattern_text_next = '/<\/p/iUs';
$str = preg_replace($pattern_text_next, '##', $str);

$txts = explode('##', $str);

$spisok = 'aa,3,5,12,';
$spisok_txts = 'aa,0,1,';

$i=0; $j=0; $k=0; foreach ($names[0] as $key => $value) {


if ( strpos($spisok, ",$key,") > 0 ) {
$pic1 = str_replace('zoo-image="/dir_images/', '', $pics[0][$j]);
$pic1 = str_replace('">', '', $pic1);
$pic2="$i";
$j++;
}

else {	
$pic1 = str_replace('zoo-image="/dir_images/', '', $pics[0][$j]);
$pic1 = str_replace('">', '', $pic1);
$pic2 = str_replace('zoo-image="/dir_images/', '', $pics[0][$j+1]);
$pic2 = str_replace('">', '', $pic2);
$j+=2;
}

if (strpos($spisok_txts, ",$key,") > 0) { $txt = ''; }
else {
	$txt = $txts[$k];
	$k++;
}



echo '<tr>
				<td>' . preg_replace("/(name-one mb0\'>|<)+/", '', $value) . '</td>
				<td>' . $txt . '<br>
				    <!--<div class="col-12 text-right"></div>-->
				</td> 
				<td>
					<div class="row" style="max-width: 490px; padding: 0;">
					    <div class="col-6 pl-3 pr-1"><img class="img-fluid" src="_mod_files/ce_images/master3turbo/' . $pic1 . '" alt="" title="" data-ami-mbgrp="Ажур-Мини" border="0" align="" data-ami-mbpopup="master3turbo/' . $pic1 . '"></div>
	               
					   <div class="col-6 pr-3 pl-1">	<img class="img-fluid" src="_mod_files/ce_images/master3turbo/' . $pic2 . '" alt="" title="" data-ami-mbgrp="Ажур-Мини" border="0" align="" data-ami-mbpopup="master3turbo/' . $pic2 . '"> </div> 
					</div>
				</td>
				<td>' . preg_replace('/(price-one\'>|<)/', '', $prices[0][$i]) . '</td>
			</tr>'; 
$i++;
}


//<span class="price-one">13800</span>
//<p class="fs20 name-one mb0">Основной комплект Мастер-2У 380В</p>

//<div class="zoo-img" style="background-image: url(&quot;/dir_images/catalog_file_129_l.jpg&quot;); cursor: pointer; transform: scale(1);"></div>


function LoadJpeg($imgname)
{
    /* Пытаемся открыть */
    $im = @imagecreatefromjpeg($imgname);

    /* Если не удалось */
    if(!$im)
    {
        /* Создаем пустое изображение */
        $im  = imagecreatetruecolor(150, 30);
        $bgc = imagecolorallocate($im, 255, 255, 255);
        $tc  = imagecolorallocate($im, 0, 0, 0);

        imagefilledrectangle($im, 0, 0, 150, 30, $bgc);

        /* Выводим сообщение об ошибке */
        imagestring($im, 1, 5, 5, 'Ошибка загрузки ' . $imgname, $tc);
    }

    return $im;
}

mkdir('../master3turbo');

foreach ($pics[0] as $key => $value) {
	break;

$value = str_replace('zoo-image="/dir_images/', '', $value);
$value = str_replace('">', '', $value);

if ( preg_replace('/\w+\./', '', $value) === 'png' ) {
	echo $value . '<br>';
	$img = imageCreateFromPng('http://master-1.ru/dir_images/'. $value); 
}
else {	
    $img = LoadJpeg('http://master-1.ru/dir_images/'. $value );
}

//header('Content-Type: image/jpeg');


imagejpeg($img, '../master3turbo/' . $value);

// break;
}


//zoo-image="/dir_images/catalog_file_129_l.jpg">
// imagedestroy($img);



?>
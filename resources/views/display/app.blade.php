
{{--<p> {{ $img }}</p>--}}

<?php

var_dump($img);

foreach ($img as $item){
//    file_get_contents($item);
    ?><img src="<?php echo asset($item)?>" alt=""><?php
}?>
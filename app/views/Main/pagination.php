<?php
$output = '';
if (!empty($images)):
    $output .= '<div class="container">';
    foreach ($images as $image):
        $output .= '<div class="item">';
        $output .= '<div>';
        $output .= '<a href="' . '/public/images/' . $image['image'] . '">';
        $output .= '<img class="front" width="300" src="' . '/public/images/' . $image['image'] . '" alt="">';
        $output .= '<span class="back"><img src="/public/icons/eye.png"></span>';
        $output .= '</a>';
        $output .= '</div>';
        $output .= '</div>';
    endforeach;
    $output .= '</div>';
else:
    $output .= '<p>No images yet</p>';
endif;
echo $output . '<div class="clear"></div><div class="pagination">' . $pagination . '</div>';
?>
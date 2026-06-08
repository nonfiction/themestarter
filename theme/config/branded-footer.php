<?php

$agency_url = 'https://www.nonfiction.ca';
$agency_name = 'nonfiction studios';

$footer = "<span id='footer-thankyou'>handcrafted by ";
$footer .= "<a href='$agency_url' target='_blank'>";
$footer .= $agency_name;
$footer .= '</a>';
$footer .= '</span>';

add_filter('admin_footer_text', function () use ($footer) {
  return $footer;
});

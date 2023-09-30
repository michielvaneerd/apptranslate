<?php

function my_app_is_in_current_url(string $currentUrl, string $url)
{
    return preg_match("@$url@", $currentUrl);
}
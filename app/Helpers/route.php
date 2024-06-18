<?php

function route($route, $data = [])
{
  // Generate the URL with query string parameters
  $url = $route;
  if (!empty($data)) {
    $params = http_build_query($data);
    $url .= "?{$params}";
  }

  // Redirect the user using a header
  header("Location: $url");
  exit();
}
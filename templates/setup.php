<?php
/*
  Create/Drop tables on activation/deactivation/deletion
*/

if (!function_exists('<%= name.slug %>_activate')) {
  function <%= name.slug %>_activate()
  {

  }
}    

if (!function_exists('<%= name.slug %>_deactivate')) {
  function <%= name.slug %>_deactivate()
  {

  }
}

if (!function_exists('<%= name.slug %>_uninstall')) {
  function <%= name.slug %>_uninstall()
  {

  }
}
?>
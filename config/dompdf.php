<?php

return array(
    'font_dir' => storage_path('fonts/'),
    'font_cache' => storage_path('fonts/'),
    'temp_dir' => sys_get_temp_dir(),
    'chroot' => realpath(base_path()),
    'allowed_protocols' => array(
        'file://' => array('rules' => array()),
        'http://' => array('rules' => array()),
        'https://' => array('rules' => array()),
    ),
    'log_output_file' => null,
    'default_media_type' => 'screen',
    'default_paper_size' => 'a4',
    'default_font' => 'sans-serif',
    'dpi' => 150,
    'font_height_ratio' => 0.9,
    'is_php_enabled' => true,
    'is_remote_enabled' => true,
    'is_html5_parser_enabled' => true,
    'is_font_subsetting_enabled' => true
);

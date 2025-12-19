<?php
/**
 * phpMyAdmin configuration
 * Auto-login với credentials từ environment variables
 */

// Disable authentication warnings
$cfg['LoginCookieValidity'] = 86400;
$cfg['LoginCookieStore'] = 0;

// Server configuration
$i = 0;
$i++;
$cfg['Servers'][$i]['auth_type'] = 'config';
$cfg['Servers'][$i]['host'] = getenv('PMA_HOST') ?: 'mysql';
$cfg['Servers'][$i]['port'] = getenv('PMA_PORT') ?: '3306';
$cfg['Servers'][$i]['connect_type'] = 'tcp';
$cfg['Servers'][$i]['socket'] = '';
$cfg['Servers'][$i]['user'] = getenv('PMA_USER') ?: 'myuser';
$cfg['Servers'][$i]['password'] = getenv('PMA_PASSWORD') ?: 'mypassword';

$cfg['Servers'][$i]['AllowNoPassword'] = false;
$cfg['Servers'][$i]['compress'] = false;

// Security
$cfg['blowfish_secret'] = 'H2OxcGXxflSd8JwrwVlh6KW6s2rER63j';

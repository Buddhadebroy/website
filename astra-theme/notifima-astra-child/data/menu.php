<?php
// Generated from data/menu.json by src/build-patterns.py.
defined( 'ABSPATH' ) || exit;
return array (
  'items' => 
  array (
    0 => 
    array (
      'title' => 'Home',
      'url' => '/',
    ),
    1 => 
    array (
      'title' => 'Features',
      'url' => '/features/',
    ),
    2 => 
    array (
      'title' => 'Pricing',
      'url' => '/pricing/',
    ),
    3 => 
    array (
      'title' => 'Resources',
      'url' => '#',
      'children' => 
      array (
        0 => 
        array (
          'title' => 'Learn',
          'children' => 
          array (
            0 => 
            array (
              'title' => 'Blog',
              'url' => '/blog/',
              'desc' => 'Guides to recover lost sales.',
              'icon' => 'file',
            ),
            1 => 
            array (
              'title' => 'Documentation',
              'url' => '@docs',
              'desc' => 'Set up Notifima step by step.',
              'icon' => 'book',
            ),
          ),
        ),
        1 => 
        array (
          'title' => 'Get help',
          'children' => 
          array (
            0 => 
            array (
              'title' => 'Support',
              'url' => '@support',
              'desc' => 'Talk to the Notifima team.',
              'icon' => 'help',
            ),
            1 => 
            array (
              'title' => 'Release Notes',
              'url' => '/release-notes/',
              'desc' => 'What\'s new in each version.',
              'icon' => 'refresh',
            ),
          ),
        ),
        2 => 
        array (
          'title' => 'Company',
          'children' => 
          array (
            0 => 
            array (
              'title' => 'About Us',
              'url' => '/about-us/',
              'desc' => 'The team behind Notifima.',
              'icon' => 'users',
            ),
            1 => 
            array (
              'title' => 'Contact Us',
              'url' => '@contact',
              'desc' => 'Sales and partnerships.',
              'icon' => 'mail',
            ),
          ),
        ),
      ),
    ),
  ),
  'feature' => 
  array (
    'title' => 'How smart waitlists boost your WooCommerce revenue.',
    'text' => 'Our most-read guide this month.',
    'link_text' => 'Read the post',
    'url' => '/blog/',
    'row1' => 'Never lose a sale again',
    'row2' => '5 min read',
  ),
  'foot' => 
  array (
    'text' => '<b>Try before you buy.</b> Explore the live demo store.',
    'link_text' => 'Open live demo',
    'url' => '@demo',
  ),
  'cta' => 
  array (
    'text' => 'Contact Sales',
    'url' => '@contact',
  ),
);

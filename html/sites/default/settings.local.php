<?php

/**
 * @file
 * Local development override configuration.
 */

use Drupal\Component\Assertion\Handle;

assert_options(ASSERT_ACTIVE, TRUE);
Handle::register();

// Fetch development services.
$settings['container_yamls'][] = DRUPAL_ROOT . '/sites/development.services.yml';

// Log everything.
$config['system.logging']['error_level'] = 'verbose';

// Disable css & js aggregation.
$config['system.performance']['css']['preprocess'] = FALSE;
$config['system.performance']['js']['preprocess'] = FALSE;

// Disable all caching.
$settings['cache']['bins']['render'] = 'cache.backend.null';
$settings['cache']['bins']['dynamic_page_cache'] = 'cache.backend.null';

// Allow test modules & themes to be installed.
$settings['extension_discovery_scan_tests'] = TRUE;

// Allow rebuild access.
$settings['rebuild_access'] = TRUE;

// Skip file system permissions hardening.
$settings['skip_permissions_hardening'] = TRUE;

<?php

// Configuration common to all environments
include_once __DIR__ . '/wp-config.common.php';

/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('REVISR_WORK_TREE', '/home/compareproviders/public_html/uat/'); // Added by Revisr
define('REVISR_GIT_PATH', 'https://github.com/iclployalty/uat.git'); // Added by Revisr
define('DB_NAME', 'comparep_wp236');

/** MySQL database username */
define('DB_USER', 'comparep_wp236');

/** MySQL database password */
define('DB_PASSWORD', '@d61[Zp5Sp');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '4rfpx9mfz6kbgic3ylb4lvl0qeu9qvtaqt3dq70xrellusaurrvsfhkgkf331bd1');
define('SECURE_AUTH_KEY',  'dy5eryhde5fyhooduknv7kpus6qogocbg9vcltstdqyjfsxuwfksykopn0nbmz1q');
define('LOGGED_IN_KEY',    'ulus2qy63mts2wsdwnkldpkwvyxucppetccgjwbdstoj33eiyivqzzsyp1upgzym');
define('NONCE_KEY',        'uooqurchofofeyvar69tzp6yvzpggsgpeezo0kpjlq3qvpqtp4ueitecqx8m9n3f');
define('AUTH_SALT',        'r1eazllt0ehz9zioxpqlgr8iji5klvvd4b0hcgeoz5itt3htq3qdcyoej58z8nwn');
define('SECURE_AUTH_SALT', 'gtbvrtekxeujkprajdfc5izzrlhj3fluxa9gwb8ljajr00ie8q8wtltemdvjcy46');
define('LOGGED_IN_SALT',   '4e3w1vxrdudcanizewt5rlhpfaa4z0bitrb0cgfuugkaisozcfk6klx2shxeydte');
define('NONCE_SALT',       'hbv4uwa3rzojxxzuvyctxrgcclxuqgkfeki1esxtbkrfnlav2qco37hiswdhkk77');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wpjj_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

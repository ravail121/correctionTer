<?php
define( 'WP_CACHE', true );




/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'corrgltt_wp38' );

/** Database username */
define( 'DB_USER', 'corrgltt_wp38' );

/** Database password */
define( 'DB_PASSWORD', 'O[SJ)w6([4p05!9d' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'v4tmj2gfsbiuo8ajhuhgzk7vdmkfwnttv3ykpsoi3e1ubrsshmam7otnewuoaihm' );
define( 'SECURE_AUTH_KEY',  'oa9xeqbgx0gpd0uyvq4iucterwzghlhsnfvvm5ev5dxnfqs6ypo9iux3pf00bo4a' );
define( 'LOGGED_IN_KEY',    'pyvgwka0rqacxv66dgflk7facn3yic3bkpzpd0u81vfgcjwj0uplsukgaxiapgcb' );
define( 'NONCE_KEY',        'fe8gfhgkhzrsnyli7gwxcnnmwzsa39k4ildqfhvk2ryaeipgc2tfkebnpnybb6sm' );
define( 'AUTH_SALT',        '0xllhobetjnrcye6qo9asbr5cvxcb9wgeomhycbtxyi32tezmyilrbasfseuworj' );
define( 'SECURE_AUTH_SALT', '0krgdr2o0hay4g3t9etpvvongelc0cdm9zeu28sl7pflzhkobyajm4x4ihg7fvq4' );
define( 'LOGGED_IN_SALT',   'vodaxf4w6v5w3v0qprls9wu8rksxt2lsogvapyh89flfevqz3psctwgt0actfu6c' );
define( 'NONCE_SALT',       'jrtpuoyrju2hb9epn6mswkmc0y3kxrjusluwq53jne6gysa3tk4slarehz0ovwg0' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = 'wpz3_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';

<?php
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


 define( 'WP_MEMORY_LIMIT' , '256M' );

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'dalil1' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

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
define( 'AUTH_KEY',         'Pq&Ji;t8j} X;>Yt90(O#h[=!K^+q+AbS`|2f,I;HZ::5V$!Yi`c;{Hm*WnX:`Zl' );
define( 'SECURE_AUTH_KEY',  '_jPg_d$`=]b!V)/$5+w@seN+h}~$:0l`omk0:~b NZFcVEvsN;XY8%t:FeA`1m}b' );
define( 'LOGGED_IN_KEY',    'A1~RwnWEX#btS~Lk:ftT!X;Z<^a6_.{U9TQw9rP)H&jOFC8 /t-.f+1DC?i*6IB*' );
define( 'NONCE_KEY',        'oYCtLLf2duICe.h~ D$?xJ*!GvI99pS 0d~H*JMr)*kFhBd1.1uh=Ajt [8g)*!r' );
define( 'AUTH_SALT',        'cbYM A2y#+ikAJ_lS@}+%$zJ%Mk!_=%;g:o&!+9jOG ,Fb9+2c9;z3wip+rln?}!' );
define( 'SECURE_AUTH_SALT', '$-[fEi8x#eHbC>Z=6;kUQ/zQRG?R8UrX WaCj  Pe=53)wJ%/1<y)67o~3CLV+~E' );
define( 'LOGGED_IN_SALT',   '1*I_i9pLK^{8T!XiO(5!HwjqCL&dpe%W-is|J9nP-$Q;8XFL^>2j(6,%1NCA=CM1' );
define( 'NONCE_SALT',       ';L4M{-T#;3 RyS_?&=K]8{^x{eWI*9I`%053]$%@eE?~;6sAl:CC4Tz]$O/&f;w^' );

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
$table_prefix = 'wp_';

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

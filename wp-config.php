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
define( 'DB_NAME', 'u380595786_griline' );

/** Database username */
define( 'DB_USER', 'u380595786_griline' );

/** Database password */
define( 'DB_PASSWORD', '8&i/euTEp!K7A78' );

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
define( 'AUTH_KEY',         'ummRh}I]+@@`(N2LTVfv-PTpWuV}=&e4Mk7Q&5ef>%,1]pbHO#oht|l)}7Ca3wEt' );
define( 'SECURE_AUTH_KEY',  '~DK;*Mh977%Uu@ndz?Z?|.<ui))qd<IJ23<ag*aoBFjn`daN.yhRpO:SgSCY_V=n' );
define( 'LOGGED_IN_KEY',    'U|:)KVKIqviDIfgC2kG>E[S^]k9i57|P&^{2s<bA2B4*UE+Vwykwg2mS]:Z_tNeV' );
define( 'NONCE_KEY',        'c&#Xy9T.8gS+2]P__7tyW@p4:WkeAsNmbW$0K4?|1/bpY9[V*_hc}{EKOe*S5shJ' );
define( 'AUTH_SALT',        '^oK55afIHhD;6m-R*#nogzL4;qJ$*hS]1ECiT&8]SPy{x)gojaZHC%lYC[k)J4Do' );
define( 'SECURE_AUTH_SALT', 'h-Mw ObiVj]w]tH9v5sX>1MKM)]FxrFIcZjvJNz_e+E!*+ry/KBQJdHJxKR2>DUa' );
define( 'LOGGED_IN_SALT',   '(.[3b4D4^-XpE}_1QJQr 5P&:|;2MXcaZV#o{`W6_*DZ_SRDZpf 0dzo:N6]<T@j' );
define( 'NONCE_SALT',       '8p`fkT%bvHfDOD0W=g,l=`{V)cvDhn3aO(8={=bW-EX[c)cq+sjn95Q2XKyzWbl8' );

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

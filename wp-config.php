<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, and ABSPATH. You can find more information by visiting
 * {@link https://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
 * Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'rttheme');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

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
define('AUTH_KEY',         '.=P9od%BG{jaR0MJ@iPo{R7$7^80;YChm-7QR=u3Z*&2VFCW.%}@Cf #d1E-.U%]');
define('SECURE_AUTH_KEY',  '~j:^r>>Zj;>%5,6C;zyfvI=0.,(CW(<_qz `gqbD,+$8Wj:zR]#ef^0<uKc?eGje');
define('LOGGED_IN_KEY',    '}u|LfS 3{^<Xm*kPW-GI+=)kYio4wT8;7`80,[QCr8>o~>}aJNsTx^I^y+%eQmh!');
define('NONCE_KEY',        'C:.qKQ?fu6Qju~(_.+{0Fw/XmdD @Lkn)-h!6z4lBIU]w@^FTme+t7K4;SBl6$l)');
define('AUTH_SALT',        'Qx>X7y^{M6#OVu:v<UBH+A{G$trp]6];`cStl%DD)mt)gW]t>#eJkV%:f@s@7u^Z');
define('SECURE_AUTH_SALT', '-}]_%16SMJS#UX7UZLj;->-owg&Z~2x6S/7>Mgl_~mDkp&Vj5W&c JT$~kg=g+~,');
define('LOGGED_IN_SALT',   '4^||N*mVdNO>Dy+##9&F$^^sZRXTW-d0-9{&Gqz]fpS_/m*}R[)+$;{kiU;@[JwQ');
define('NONCE_SALT',       '#Yj|&cW&PtQ3g/Kt6YtOhRI#Y=,EXHPGXA4k0,ela(U:t6cx+hd^+8,uWffm`fU+');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

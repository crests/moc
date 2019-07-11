<?php
/**
 * WordPress の基本設定
 *
 * このファイルは、MySQL、テーブル接頭辞、秘密鍵、ABSPATH の設定を含みます。
 * より詳しい情報は {@link http://wpdocs.sourceforge.jp/wp-config.php_%E3%81%AE%E7%B7%A8%E9%9B%86 
 * wp-config.php の編集} を参照してください。MySQL の設定情報はホスティング先より入手できます。
 *
 * このファイルはインストール時に wp-config.php 作成ウィザードが利用します。
 * ウィザードを介さず、このファイルを "wp-config.php" という名前でコピーして直接編集し値を
 * 入力してもかまいません。
 *
 * @package WordPress
 */

// 注意: 
// Windows の "メモ帳" でこのファイルを編集しないでください !
// 問題なく使えるテキストエディタ
// (http://wpdocs.sourceforge.jp/Codex:%E8%AB%87%E8%A9%B1%E5%AE%A4 参照)
// を使用し、必ず UTF-8 の BOM なし (UTF-8N) で保存してください。

// ** MySQL 設定 - この情報はホスティング先から入手してください。 ** //
/** WordPress のためのデータベース名 */
define('DB_NAME', 'gochikai');

/** MySQL データベースのユーザー名 */
define('DB_USER', 'gochikai');

/** MySQL データベースのパスワード */
define('DB_PASSWORD', 'Nz7om8#1');

/** MySQL のホスト名 */
define('DB_HOST', 'localhost');

/** データベースのテーブルを作成する際のデータベースの文字セット */
define('DB_CHARSET', 'utf8');

/** データベースの照合順序 (ほとんどの場合変更する必要はありません) */
define('DB_COLLATE', '');

/**#@+
 * 認証用ユニークキー
 *
 * それぞれを異なるユニーク (一意) な文字列に変更してください。
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org の秘密鍵サービス} で自動生成することもできます。
 * 後でいつでも変更して、既存のすべての cookie を無効にできます。これにより、すべてのユーザーを強制的に再ログインさせることになります。
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '*IPG^mE4Zce_kA2f8yVO6-EaMV.bFD@Bd4N~Ggi)%v|+:r4Hn$5.Gjjy/O2iWJ|U');
define('SECURE_AUTH_KEY',  ':duWG53.S$;BG,u/:P@@zve3#c~]hkN|:qnx*Ofp_{o[:p&1- vm<3r@1F0VCg,R');
define('LOGGED_IN_KEY',    'FW2[_9wY|tfp}`F-pR+UAph2A9P6x7 RU8Tp1C-:Lgw8Yz}e)F*?RO1Y4`?hi?+%');
define('NONCE_KEY',        'X;ru4^sY+%t|463JRjNg+|ON^<@}}EwH+^7q>p)+ T1oP^e3Y>&3_-`+yJ.mdY,?');
define('AUTH_SALT',        ')7 N#!o=[?PM;#1L%o-<[-w`{dbM:~BC(;Vr|pqq4qPJ&:|WW=hB8S>wP/n;>cvM');
define('SECURE_AUTH_SALT', '4]>eopZleA(mWQFQ0M:L{m.W|dK|an)gD|CtzK0L$[QF*!3-b`2~tW`a.(/>NC,5');
define('LOGGED_IN_SALT',   '<Nf/^X#[n;FyZ|81%k7dnG%h5j7aVMPi0AkshU6}08i) }D48 &h}v(ns2~*5/+U');
define('NONCE_SALT',       'Y[>dzsQz-TpBXIF-xL!9h{Xu$8AQv9z7l&6N=wrGeW.Cuh1TbE/mydI~}lcDY#`]');

/**#@-*/

/**
 * WordPress データベーステーブルの接頭辞
 *
 * それぞれにユニーク (一意) な接頭辞を与えることで一つのデータベースに複数の WordPress を
 * インストールすることができます。半角英数字と下線のみを使用してください。
 */
$table_prefix  = 'wp_';

/**
 * 開発者へ: WordPress デバッグモード
 *
 * この値を true にすると、開発中に注意 (notice) を表示します。
 * テーマおよびプラグインの開発者には、その開発環境においてこの WP_DEBUG を使用することを強く推奨します。
 */
define('WP_DEBUG', false);

/* 編集が必要なのはここまでです ! WordPress でブログをお楽しみください。 */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

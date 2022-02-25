<?php

namespace WP_Defender\Integrations;

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Class MaxMind_Geolocation
 *
 * @since 2.7.1
 * @package WP_Defender\Integrations
 */
class MaxMind_Geolocation {

	/**
	 * The name of the MaxMind database.
	 */
	const DB_NAME = 'GeoLite2-Country';

	/**
	 * The extension of the MaxMind database.
	 */
	const DB_EXT = '.mmdb';

	/**
	 * @return string
	 */
	public function get_db_full_name() {
		return self::DB_NAME . self::DB_EXT;
	}

	/**
	 * Todo: extend the logic to handle different results.
	 * @param string $license_key
	 *
	 * @return bool|string|\WP_Error
	 */
	public function get_downloaded_url( $license_key ) {
		$url = add_query_arg(
			array(
				'edition_id'  => self::DB_NAME,
				'license_key' => urlencode( sanitize_text_field( $license_key ) ),
				'suffix'      => 'tar.gz',
			),
			'https://download.maxmind.com/app/geoip_download'
		);
		if ( ! function_exists( 'download_url' ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}

		return download_url( $url );
	}
	/**
	 * Todo:
	 * - add a cron to update DB using 'fifteen_days' because MaxMind's TOS requires that the databases be updated or removed periodically,
	 * - add a method to the DB path.
	 */
}

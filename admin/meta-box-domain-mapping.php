<?php

global $post;

wp_nonce_field( 'pronamic_domain_mapping_save', 'pronamic_domain_mapping_meta_box_nonce' );

$host = get_post_meta( $post->ID, '_pronamic_domain_mapping_host', true );
$ga   = get_post_meta( $post->ID, '_pronamic_domain_mapping_ga_ua', true );

?>
<table class="form-table">
	<tr valign="top">
		<th scope="row">
			<label for="pronamic_domain_mapping_host"><?php esc_html_e( 'Domain Name', 'pronamic_domain_mapping' ); ?></label>
		</th>
		<td>
			<input id="pronamic_domain_mapping_host" name="_pronamic_domain_mapping_host" value="<?php echo esc_attr( $host ); ?>" type="text" class="regular-text" />
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">
			<label for="pronamic_domain_mapping_ga_ua"><?php esc_html_e( 'Google Analytics Tracking ID', 'pronamic_domain_mapping' ); ?></label>
		</th>
		<td>
			<input id="pronamic_domain_mapping_ga_ua" name="_pronamic_domain_mapping_ga_ua" value="<?php echo esc_attr( $ga ); ?>" type="text" class="regular-text" />
		</td>
	</tr>
</table>

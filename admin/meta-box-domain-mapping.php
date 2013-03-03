<?php

global $post;

wp_nonce_field( 'pronamic_domain_mapping_save', 'pronamic_domain_mapping_meta_box_nonce' );

?>
<table class="form-table">
	<tr valign="top">
		<th scope="row">
			<label for="pronamic_domain_mapping_host"><?php _e( 'Domain Name', 'pronamic_domain_mapping' ); ?></label>
		</th>
		<td>
			<input id="pronamic_domain_mapping_host" name="_pronamic_domain_mapping_host" value="<?php echo get_post_meta( $post->ID, '_pronamic_domain_mapping_host', true ); ?>" type="text" class="regular-text" />
		</td>
	</tr>
</table>
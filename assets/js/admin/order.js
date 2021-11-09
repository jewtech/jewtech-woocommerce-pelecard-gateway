jQuery( function( $ ) {

	var $container = jQuery( '#woocommerce-order-items' );

	$container.on( 'click', 'button.wpg-charge', function( e ) {
		e.preventDefault();

		if ( !window.confirm( wpg_i18n.do_charge ) ) {
			return false;
		}

		const $button = $( this );

		const data = {
			dataType: 'json',
			action: 'wpg_charge_order',
			order_id: woocommerce_admin_meta_boxes.post_id,
			security: woocommerce_admin_meta_boxes.order_item_nonce,
		};

		$.ajax( {
			type: 'POST',
			url: woocommerce_admin_meta_boxes.ajax_url,
			data: data,
			beforeSend: function() {
				$container.block( {
					message: null,
					overlayCSS: {
						background: '#fff',
						opacity: 0.6
					}
				} );
				$button.prop( 'disabled', true );
				$( 'button.do-api-refund' ).prop( 'disabled', true );
			},
			success: function( response ) {
				if ( true === response.success ) {
					window.location.reload();
				} else {
					window.alert( response.data.error );
					$button.prop( 'disabled', false );
					$container.unblock();
				}
			},
			complete: function() {
				window.wcTracks.recordEvent( 'order_edit_charged', {
					order_id: data.order_id,
					status: $( '#order_status' ).val()
				} );
			}
		} );
	} );

	$( '#wpg-transactions' ).accordion( {
		header: 'h3',
		collapsible: true,
		active: false,
		animate: false,
		heightStyle: 'content',
		icons: {
			header: 'ui-icon-plus',
			activeHeader: 'ui-icon-minus'
		}
	} );

	$( '#wpg-transactions h3 > a' ).on( 'click', null, null, function( event ) {
		window.open( $( this ).attr( 'href' ), '_blank' );
		event.stopPropagation();
		event.preventDefault();
	} );

} );
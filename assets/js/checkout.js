jQuery( function( $ ) {

	$( document.body ).on( 'updated_checkout payment_method_selected', function() {
		$( 'input.woocommerce-SavedPaymentMethods-tokenInput:checked' )
			.siblings( '.woocommerce-TotalPayments' )
			.show();
	} );

	$( document.body ).on( 'click', ':input.woocommerce-SavedPaymentMethods-tokenInput', function() {
		$( '.woocommerce-TotalPayments' ).hide();

		$( this ).siblings( '.woocommerce-TotalPayments' ).show();
	} );

} );
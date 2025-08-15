/**
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( $ ) {
	// Site title and description.
	wp.customize( 'blogname', function( value ) {
		value.bind( function( to ) {
			$( '.site-title a' ).text( to );
		} );
	} );
	wp.customize( 'blogdescription', function( value ) {
		value.bind( function( to ) {
			$( '.site-description' ).text( to );
		} );
	} );

	// Footer text
	wp.customize( 'footer_text', function( value ) {
		value.bind( function( to ) {
			$( '.footer__text p' ).html( to );
		} );
	} );

	// Copyright text
	wp.customize( 'copyright_text', function( value ) {
		value.bind( function( to ) {
			$( '.footer__copy' ).html( to );
		} );
	} );

	// Footer partners
	wp.customize( 'footer_partner_1', function( value ) {
		value.bind( function( to ) {
			var partners = [];
			
			// Parse JSON data
			try {
				partners = JSON.parse( to );
			} catch(e) {
				partners = [];
			}
			
			// Check if we're updating existing partners or rebuilding from scratch
			var $existingPartners = $( '.footer__partners' );
			var existingPartnerCount = $existingPartners.find( '.footer__partner' ).length;
			
			if ( partners && partners.length ) {
				// If partner count changed, rebuild everything
				if ( existingPartnerCount !== partners.length ) {
					rebuildPartners( partners );
				} else {
					// Just update existing partners
					updateExistingPartners( partners );
				}
			} else {
				// No partners, clear the container
				$existingPartners.empty();
			}
		} );
		
		function rebuildPartners( partners ) {
			var html = '';
			var pendingImages = [];
			
			partners.forEach( function( partner, index ) {
				if ( partner.image_id ) {
					// Build container styles for width and height
					var containerStyleParts = [];
					
					if ( partner.width ) {
						var widthUnit = partner.width_unit || 'rem';
						if ( widthUnit === 'auto' ) {
							containerStyleParts.push( 'width: auto' );
						} else {
							containerStyleParts.push( 'width: ' + parseFloat( partner.width ) + widthUnit );
						}
					}
					
					if ( partner.height ) {
						var heightUnit = partner.height_unit || 'rem';
						if ( heightUnit === 'auto' ) {
							containerStyleParts.push( 'height: auto' );
						} else {
							containerStyleParts.push( 'height: ' + parseFloat( partner.height ) + heightUnit );
						}
					}
					
					var containerStyle = '';
					if ( containerStyleParts.length > 0 ) {
						containerStyle = ' style="' + containerStyleParts.join( '; ' ) + ';"';
					}
					
					html += '<div class="footer__partner" data-partner-index="' + index + '"' + containerStyle + '>';
					html += '<img loading="lazy" alt="partner logo" data-image-id="' + partner.image_id + '" />';
					html += '</div>';
					
					// Queue for image loading
					pendingImages.push({ index: index, partner: partner });
				}
			});
			
			$( '.footer__partners' ).html( html );
			
			// Load images with proper src attributes
			pendingImages.forEach( function( item ) {
				loadPartnerImageSrc( item.index, item.partner );
			});
		}
		
		function updateExistingPartners( partners ) {
			partners.forEach( function( partner, index ) {
				var $partner = $( '.footer__partner[data-partner-index="' + index + '"]' );
				if ( $partner.length ) {
					var $img = $partner.find( 'img' );
					
					// Update image if changed
					if ( $img.attr( 'data-image-id' ) != partner.image_id ) {
						$img.attr( 'data-image-id', partner.image_id );
						loadPartnerImage( index, partner );
					} else {
						// Just update styles if image is the same
						applyPartnerStyles( $img, partner );
					}
				}
			});
		}
		
		function loadPartnerImageSrc( index, partner ) {
			var $img = $( '.footer__partner[data-partner-index="' + index + '"] img' );
			
			if ( !$img.length || !partner.image_id ) {
				return;
			}
			
			// Always use AJAX in preview since wp.media might not be available
			loadPartnerImageAjax( $img, partner );
		}
		
		function loadPartnerImage( index, partner ) {
			var $img = $( '.footer__partner[data-partner-index="' + index + '"] img' );
			
			if ( !$img.length || !partner.image_id ) {
				return;
			}
			
			if ( window.wp && window.wp.media && window.wp.media.attachment ) {
				var attachment = window.wp.media.attachment( partner.image_id );
				attachment.fetch().then( function() {
					var url = attachment.get( 'url' );
					if ( url ) {
						$img.attr( 'src', url );
						$img.attr( 'data-image-id', partner.image_id );
						applyPartnerStyles( $img, partner );
					}
				}).catch( function() {
					// Handle fetch error - try AJAX fallback
					loadPartnerImageAjax( $img, partner );
				});
			} else {
				loadPartnerImageAjax( $img, partner );
			}
		}
		
		function loadPartnerImageAjax( $img, partner ) {
			// Get AJAX URL for the preview context
			var ajaxUrl = typeof ajaxurl !== 'undefined' ? ajaxurl : '/wp-admin/admin-ajax.php';
			
			$.post( ajaxUrl, {
				action: 'get_attachment_image',
				attachment_id: partner.image_id
			}, function( response ) {
				if ( response.success && response.data.html ) {
					// Get the container before replacing
					var $container = $img.closest( '.footer__partner' );
					
					// Replace the img element with WordPress-generated HTML
					$img.replaceWith( response.data.html );
					
					// Get the new img element for styling
					var $newImg = $container.find( 'img[data-image-id="' + partner.image_id + '"]' );
					if ( $newImg.length ) {
						applyPartnerStyles( $newImg, partner );
					}
				}
			}).fail( function() {
				// If AJAX fails, try to get just the URL as fallback
				$.post( ajaxUrl, {
					action: 'get_attachment_url',
					attachment_id: partner.image_id
				}, function( response ) {
					if ( response.success && response.data.url ) {
						$img.attr( 'src', response.data.url );
						$img.attr( 'data-image-id', partner.image_id );
						applyPartnerStyles( $img, partner );
					}
				});
			});
		}
		
		function applyPartnerStyles( $img, partner ) {
			var $container = $img.closest( '.footer__partner' );
			var styleParts = [];
			
			if ( partner.width ) {
				var widthUnit = partner.width_unit || 'rem';
				if ( widthUnit === 'auto' ) {
					styleParts.push( 'width: auto' );
				} else {
					styleParts.push( 'width: ' + parseFloat( partner.width ) + widthUnit );
				}
			}
			
			if ( partner.height ) {
				var heightUnit = partner.height_unit || 'rem';
				if ( heightUnit === 'auto' ) {
					styleParts.push( 'height: auto' );
				} else {
					styleParts.push( 'height: ' + parseFloat( partner.height ) + heightUnit );
				}
			}
			
			var style = styleParts.join( '; ' );
			if ( style ) {
				$container.attr( 'style', style + ';' );
			} else {
				$container.removeAttr( 'style' );
			}
		}
	} );

} )( jQuery );
( function($) {

	var methods = {

		init : function( options ) {

			return this.each( function(){

				// Create default options
				var settings = {
					rotate_type						: 'fade',
					auto_rotate						: 0,
					delay									: 10,
					rotate_pause					: 0,
					rotate_speed					: 10,
					rotate_ease						: 'easeOutExpo',
					nav_reverse						: 0,
					before_change					: function(){},
					after_change					: function(){},
					after_load						: function(){}
				};

				// Useful variables. Play carefully.
        var vars = {
	        count						: 0,
	        current					: 0,
	        reverse					: 0,
	        running					: 0
        };

				// Add any set options
				if (options) {
					$.extend(settings, options);
				}

				// Create variables
				var $gallery_container = $(this),
						$gallery = $(this).find('.mtphr-gallery-resource-container'),
						$nav_prev = $(this).find('.mtphr-gallery-nav-prev'),
						$nav_next = $(this).find('.mtphr-gallery-nav-next'),
						$nav_controls = $(this).find('.mtphr-gallery-navigation'),
						gallery_width = $gallery.width(),
						gallery_height = 0,
						resources = [],
						gallery_delay,
						rotate_adjustment = settings.rotate_type,
						after_change_timeout,
						gallery_pause = false,
						touch_down_x,
						touch_down_y,
						touch_link = '',
						touch_target = '';

				// Add the vars
				$gallery.data('vars', vars);

				// Save the resource count & total
				vars.count = $gallery.find('.mtphr-gallery-resource').length;

				// Start the first resource
				if( vars.count > 0 ) {
					mtphr_galleries_rotator_setup();
		    }




		    /**
		     * Setup the rotator
		     *
		     * @since 1.0.4
		     */
		    function mtphr_galleries_rotator_setup() {

		    	// Loop through the resource items
					$gallery.find('.mtphr-gallery-resource').each( function(index) {

						// Add the resource to the array
						resources.push($(this));

					});

					// Resize the resources
					mtphr_galleries_resize_resources();

					// Find the rotation type and create the dynamic rotation init function
					var rotate_init_name = 'mtphr_galleries_'+settings.rotate_type+'_init';
					var mtphr_galleries_type_init = eval('('+rotate_init_name+')');
					mtphr_galleries_type_init( $gallery, resources, parseInt(settings.rotate_speed*100), settings.rotate_ease );
					mtphr_galleries_update_links( 0 );

					// Start the rotator rotate
					if( settings.auto_rotate ) {
						mtphr_galleries_delay();
					}

					// Clear the loop on mouse hover
					$gallery.hover(
					  function () {
					  	if( settings.auto_rotate && settings.rotate_pause ) {
					    	clearInterval( gallery_delay );
					    }
					  },
					  function () {
					  	if( settings.auto_rotate && settings.rotate_pause ) {
					    	mtphr_galleries_delay();
					    }
					  }
					);

					// Set the init class after the first load
					setTimeout(function() {
						$gallery_container.addClass('mtphr-gallery-init');
					}, parseInt(settings.rotate_speed*100) );
		    }

		    /**
		     * Create the resourceer rotator loop
		     *
		     * @since 1.0.0
		     */
		    function mtphr_galleries_delay() {

			    // Start the resourceer timer
			    clearInterval( gallery_delay );
					gallery_delay = setInterval( function() {

						// Find the new resource
			    	var new_resource = parseInt(vars.current + 1);
						if( new_resource == vars.count ) {
							new_resource = 0;
						}

						mtphr_galleries_update( new_resource );

			    }, parseInt(settings.delay*1000));
		    }

		    /**
		     * Create the rotator update call
		     *
		     * @since 1.0.0
		     */
		    function mtphr_galleries_update( new_resource ) {

		    	// Clear the interval
		    	if( settings.auto_rotate ) {
			    	clearInterval( gallery_delay );
			    }

		    	// Trigger the before change callback
          settings.before_change.call( this, $gallery );

          // Set the running variable
          vars.running = 1;

			    // Rotate the current resource out
					mtphr_galleries_out( new_resource );

					// Rotate the new resource in
					mtphr_galleries_in( new_resource );

					// Set the current resource
					vars.current = new_resource;

					// Trigger the after change callback
					after_change_timeout = setTimeout( function() {

						settings.after_change.call( this, $gallery );

						// Reset the rotator type & variables
						rotate_adjustment = settings.rotate_type;
						vars.reverse = 0;
						vars.running = 0;

						// Restart the interval
						if( settings.auto_rotate ) {
				    	mtphr_galleries_delay();
				    }

					}, parseInt(settings.rotate_speed*100) );
		    }

		    /**
		     * Update the control links
		     *
		     * @since 1.0.0
		     */
		    function mtphr_galleries_update_links( new_resource ) {

			    if( $nav_controls ) {
          	$nav_controls.children('a').removeClass('active');
          	$nav_controls.children('a[href="'+new_resource+'"]').addClass('active');
          }
		    }

		    /**
		     * Create the rotator in function calls
		     *
		     * @since 1.0.0
		     */
		    function mtphr_galleries_in( new_resource ) {

		    	// Update the links
		    	mtphr_galleries_update_links( new_resource );

			    // Find the rotation type and create the dynamic rotation in function
					var rotate_in_name = 'mtphr_galleries_'+rotate_adjustment+'_in';
					var mtphr_galleries_type_in = eval('('+rotate_in_name+')');
					mtphr_galleries_type_in( $gallery, $(resources[new_resource]), $(resources[vars.current]), parseInt(settings.rotate_speed*100), settings.rotate_ease );
		    }

		    /**
		     * Create the rotator out function calls
		     *
		     * @since 1.0.0
		     */
		    function mtphr_galleries_out( new_resource ) {

			    // Find the rotation type and create the dynamic rotation out function
					var rotate_out_name = 'mtphr_galleries_'+rotate_adjustment+'_out';
					var mtphr_galleries_type_out = eval('('+rotate_out_name+')');
					mtphr_galleries_type_out( $gallery, $(resources[vars.current]), $(resources[new_resource]), parseInt(settings.rotate_speed*100), settings.rotate_ease );
		    }

		    /**
		     * Resize the rotator resources
		     *
		     * @since 1.0.0
		     */
		    function mtphr_galleries_resize_resources() {

			    for( var i=0; i<vars.count; i++ ) {
				    $(resources[i]).width( gallery_width+'px' );
			    }
			    $gallery.css('height', $(resources[vars.current]).height()+'px');
		    }




			  // Initialize the resources and resourceer
				function mtphr_galleries_fade_init( $gallery, resources, speed, ease ) {

					// Get the first resource
					$resource = resources[0];

					// Find the width of the resource
					var w = $gallery.width();
					var h = $resource.outerHeight();

					// Set the height of the resourceer
					$gallery.css( 'height', h+'px' );

					// Set the initial position of the width & make sure it's visible
					$resource.show();

					// If there are any images, reset height after loading
					if( $resource.find('img').length > 0 ) {

						$resource.find('img').each( function(index) {

							jQuery(this).load( function() {

								// Find the height of the resource
								var h = $resource.outerHeight();

								// Set the height of the resourceer
								$gallery.css( 'height', h+'px' );
							});
						});
					}
			  }

				// Show the new resource
				function mtphr_galleries_fade_in( $gallery, $resource, $prev, speed, ease ) {
			    $resource.fadeIn( speed );

			    var h = $resource.outerHeight();

					// Resize the resourceer
					$gallery.stop(true,true).animate( {
						height: h+'px'
					}, speed, ease, function() {
					});
			  }

			  // Hide the old resource
			  function mtphr_galleries_fade_out( $gallery, $resource, $next, speed, ease ) {
			    $resource.stop(true,true).fadeOut( speed, function() {
				    $gallery.prepend($resource);
			    });
			  }




			  // Initialize the resources and resourceer
				function mtphr_galleries_slide_left_init( $gallery, resources, speed, ease ) {

					// Get the first resource
					$resource = resources[0];

					// Find the dimensions of the resource
					var w = $gallery.width();
					var h = $resource.outerHeight();

					// Set the height of the resourceer
					$gallery.css( 'height', h+'px' );

					// Set the initial position of the width & make sure it's visible
					$resource.css( 'left', 0 );
					$resource.show();

					// If there are any images, reset height after loading
					if( $resource.find('img').length > 0 ) {

						$resource.find('img').each( function(index) {

							jQuery(this).load( function() {

								// Find the height of the resource
								var h = $resource.outerHeight();

								// Set the height of the resourceer
								$gallery.css( 'height', h+'px' );
							});
						});
					}
			  }

				// Show the new resource
				function mtphr_galleries_slide_left_in( $gallery, $resource, $prev, speed, ease ) {

					// Find the dimensions of the resource
					var w = $gallery.width();
					var h = $resource.outerHeight();

					// Set the initial position of the width & make sure it's visible
					$resource.css( 'left', w+'px' );
					$resource.show();

					// Resize the resourceer
					$gallery.stop(true,true).animate( {
						height: h+'px'
					}, speed, ease, function() {
					});

					// Slide the resource in
					$resource.stop(true,true).animate( {
						left: '0'
					}, speed, ease, function() {
					});
			  }

			  // Hide the old resource
			  function mtphr_galleries_slide_left_out( $gallery, $resource, $next, speed, ease ) {

			    // Find the dimensions of the resource
					var w = $gallery.width();
					var h = $resource.outerHeight();

					// Slide the resource in
					$resource.stop(true,true).animate( {
						left: '-'+w+'px'
					}, speed, ease, function() {
						// Hide the resource
						$resource.hide();
						$gallery.prepend($resource);
					});
			  }




			  // Initialize the resources and resourceer
				function mtphr_galleries_slide_right_init( $gallery, resources, speed, ease ) {

					// Get the first resource
					$resource = resources[0];

					// Find the dimensions of the resource
					var w = $gallery.width();
					var h = $resource.outerHeight();

					// Set the height of the resourceer
					$gallery.css( 'height', h+'px' );

					// Set the initial position of the width & make sure it's visible
					$resource.css( 'left', 0 );
					$resource.show();

					// If there are any images, reset height after loading
					if( $resource.find('img').length > 0 ) {

						$resource.find('img').each( function(index) {

							jQuery(this).load( function() {

								// Find the height of the resource
								var h = $resource.outerHeight();

								// Set the height of the resourceer
								$gallery.css( 'height', h+'px' );
							});
						});
					}
			  }

				// Show the new resource
				function mtphr_galleries_slide_right_in( $gallery, $resource, $prev, speed, ease ) {

					// Find the dimensions of the resource
					var w = $gallery.width();
					var h = $resource.outerHeight();

					// Set the initial position of the width & make sure it's visible
					$resource.css( 'left', '-'+w+'px' );
					$resource.show();

					// Resize the resourceer
					$gallery.stop(true,true).animate( {
						height: h+'px'
					}, speed, ease, function() {
					});

					// Slide the resource in
					$resource.stop(true,true).animate( {
						left: '0'
					}, speed, ease, function() {
					});
			  }

			  // Hide the old resource
			  function mtphr_galleries_slide_right_out( $gallery, $resource, $next, speed, ease ) {

			    // Find the dimensions of the resource
					var w = $gallery.width();
					var h = $resource.outerHeight();

					// Slide the resource in
					$resource.stop(true,true).animate( {
						left: w+'px'
					}, speed, ease, function() {
						// Hide the resource
						$resource.hide();
						$gallery.prepend($resource);
					});
			  }




			  // Initialize the resources and resourceer
				function mtphr_galleries_slide_down_init( $gallery, resources, speed, ease ) {

					// Get the first resource
					$resource = resources[0];

					// Find the height of the resource
					var h = $resource.outerHeight();

					// Set the height of the resourceer
					$gallery.css( 'height', h+'px' );

					// Set the initial position of the width & make sure it's visible
					$resource.css( 'top', 0 );
					$resource.show();

					// If there are any images, reset height after loading
					if( $resource.find('img').length > 0 ) {

						$resource.find('img').each( function(index) {

							jQuery(this).load( function() {

								// Find the height of the resource
								var h = $resource.outerHeight();

								// Set the height of the resourceer
								$gallery.css( 'height', h+'px' );
							});
						});
					}
			  }

				// Show the new resource
				function mtphr_galleries_slide_down_in( $gallery, $resource, $prev, speed, ease ) {

					// Find the height of the resource
					var h = $resource.outerHeight();

					// Set the initial position of the width & make sure it's visible
					$resource.css( 'top', '-'+h+'px' );
					$resource.show();

					// Resize the resourceer
					$gallery.stop(true,true).animate( {
						height: h+'px'
					}, speed, ease, function() {
					});

					// Slide the resource in
					$resource.stop(true,true).animate( {
						top: '0'
					}, speed, ease, function() {
					});
			  }

			  // Hide the old resource
			  function mtphr_galleries_slide_down_out( $gallery, $resource, $next, speed, ease ) {

			    // Find the height of the next resource
					var h = $next.outerHeight();

					// Slide the resource in
					$resource.stop(true,true).animate( {
						top: h+'px'
					}, speed, ease, function() {
						// Hide the resource
						$resource.hide();
						$gallery.prepend($resource);
					});
			  }




			  // Initialize the resources and resourceer
				function mtphr_galleries_slide_up_init( $gallery, resources, speed, ease ) {

					// Get the first resource
					$resource = resources[0];

					// Find the height of the resource
					var h = $resource.outerHeight();

					// Set the height of the resourceer
					$gallery.css( 'height', h+'px' );

					// Set the initial position of the width & make sure it's visible
					$resource.css( 'top', 0 );
					$resource.show();

					// If there are any images, reset height after loading
					if( $resource.find('img').length > 0 ) {

						$resource.find('img').each( function(index) {

							jQuery(this).load( function() {

								// Find the height of the resource
								var h = $resource.outerHeight();

								// Set the height of the resourceer
								$gallery.css( 'height', h+'px' );
							});
						});
					}
			  }

				// Show the new resource
				function mtphr_galleries_slide_up_in( $gallery, $resource, $prev, speed, ease ) {

					// Find the height of the resource
					var h = $resource.outerHeight();

					// Set the initial position of the width & make sure it's visible
					$resource.css( 'top', $prev.outerHeight()+'px' );
					$resource.show();

					// Resize the resourceer
					$gallery.stop(true,true).animate( {
						height: h+'px'
					}, speed, ease, function() {
					});

					// Slide the resource in
					$resource.stop(true,true).animate( {
						top: '0'
					}, speed, ease, function() {
					});
			  }

			  // Hide the old resource
			  function mtphr_galleries_slide_up_out( $gallery, $resource, $next, speed, ease ) {

			    // Find the height of the next resource
					var h = $resource.outerHeight();

					// Slide the resource in
					$resource.stop(true,true).animate( {
						top: '-'+h+'px'
					}, speed, ease, function() {
						// Hide the resource
						$resource.hide();
						$gallery.prepend($resource);
					});
			  }




		    /**
		     * Navigation clicks
		     *
		     * @since 1.0.0
		     */
		    if( $nav_prev ) {

		    	$nav_prev.bind('click', function( e ) {
		    		e.preventDefault();

		    		if(vars.running) return false;

			    	// Find the new resource
			    	var new_resource = parseInt(vars.current-1);
						if( new_resource < 0 ) {
							new_resource = vars.count-1;
						}
						if( settings.nav_reverse ) {
							if( settings.rotate_type == 'slide_left' ) {
								rotate_adjustment = 'slide_right';
							} else if( settings.rotate_type == 'slide_right' ) {
								rotate_adjustment = 'slide_left';
							} else if( settings.rotate_type == 'slide_down' ) {
								rotate_adjustment = 'slide_up';
							} else if( settings.rotate_type == 'slide_up' ) {
								rotate_adjustment = 'slide_down';
							}
							vars.reverse = 1;
						}
						mtphr_galleries_update( new_resource );
		    	});

		    	$nav_next.bind('click', function(e) {
		    		e.preventDefault();

		    		if(vars.running) return false;

			    	// Find the new resource
			    	var new_resource = parseInt(vars.current + 1);
						if( new_resource == vars.count ) {
							new_resource = 0;
						}
						mtphr_galleries_update( new_resource );
		    	});
		    }




		    /**
		     * Nav controls
		     *
		     * @since 1.0.0
		     */
		    $nav_controls.children('a').bind('click', function( e ) {
	    		e.preventDefault();

	    		// Find the new resource
		    	var new_resource = parseInt( $(this).attr('href') );

	    		if(vars.running) return false;
	    		if(new_resource == vars.current) return false;

		    	var reverse = ( new_resource < vars.current ) ? 1 : 0;

	    		if( settings.nav_reverse && reverse ) {
						if( settings.rotate_type == 'slide_left' ) {
							rotate_adjustment = 'slide_right';
						} else if( settings.rotate_type == 'slide_right' ) {
							rotate_adjustment = 'slide_left';
						} else if( settings.rotate_type == 'slide_down' ) {
							rotate_adjustment = 'slide_up';
						} else if( settings.rotate_type == 'slide_up' ) {
							rotate_adjustment = 'slide_down';
						}
						vars.reverse = 1;
					}
					mtphr_galleries_update( new_resource );
	    	});




				/* --------------------------------------------------------- */
				/* !Gallery swipe - 1.0.5 */
				/* --------------------------------------------------------- */

				$gallery.swipe( {
					triggerOnTouchEnd : true,
					allowPageScroll: 'vertical',
					swipeStatus : function(event, phase, direction, distance, duration, fingers) {
						if ( phase =="end" ) {
							if (direction == "right") {

								if(vars.running) return false;

					    	// Find the new resource
					    	var new_resource = parseInt(vars.current-1);
								if( new_resource < 0 ) {
									new_resource = vars.count-1;
								}
								if( settings.rotate_type == 'slide_left' || settings.rotate_type == 'slide_right' ) {
									rotate_adjustment = 'slide_right';
								}
								if( settings.nav_reverse ) {
									if( settings.rotate_type == 'slide_down' ) {
										rotate_adjustment = 'slide_up';
									} else if( settings.rotate_type == 'slide_up' ) {
										rotate_adjustment = 'slide_down';
									}
									vars.reverse = 1;
								}
								mtphr_galleries_update( new_resource );

							} else if (direction == "left") {

								if(vars.running) return false;

					    	// Find the new resource
					    	var new_resource = parseInt(vars.current + 1);
								if( new_resource == vars.count ) {
									new_resource = 0;
								}
								if( settings.rotate_type == 'slide_left' || settings.rotate_type == 'slide_right' ) {
									rotate_adjustment = 'slide_left';
								}
								mtphr_galleries_update( new_resource );
							}
						}
					}
				});




		    /**
		     * Resize listener
		     * Reset the resourceer width
		     *
		     * @since 1.0.0
		     */
		    $(window).resize( function() {
			    gallery_width = $gallery.width();
			    mtphr_galleries_resize_resources();
		    });




		    // Trigger the afterLoad callback
        settings.after_load.call(this, $gallery);

			});
		}
	};




	/**
	 * Setup the class
	 *
	 * @since 1.0.0
	 */
	$.fn.mtphr_gallery_slider = function( method ) {

		if ( methods[method] ) {
			return methods[method].apply( this, Array.prototype.slice.call(arguments, 1) );
		} else if ( typeof method === 'object' || !method ) {
			return methods.init.apply( this, arguments );
		} else {
			$.error( 'Method ' +  method + ' does not exist in mtphr_gallery_slider' );
		}
	};

})( jQuery );
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

				// Add any set options
				if (options) {
					$.extend(settings, options);
				}
				
				// Useful variables. Play carefully.
        var vars = {
	        count						: 0,
	        previous				: null,
	        current					: 0,
	        next						: null,
	        reverse					: 0,
	        running					: 0,
	        speed						: settings.rotate_speed,
	        ease						: settings.rotate_ease,
        };

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
					
					// Setup the resource
		    	mtphr_galleries_setup_resource( 0 );

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
		     * @since 2.0.5
		     */
		    function mtphr_galleries_update( new_resource ) {
		    
		    	if( new_resource != vars.current ) {
	
			    	// Clear the interval
			    	if( settings.auto_rotate ) {
				    	clearInterval( gallery_delay );
				    }
				    
				    // Set the next variable
				    vars.next = new_resource;
	
			    	// Trigger the before change callback
	          settings.before_change.call( $gallery_container, $gallery );
	          $gallery_container.trigger('mtphr_galleries_before_change_single', [resources, vars]);
	          $('body').trigger('mtphr_galleries_before_change', [$gallery_container, resources, vars]);
	
	          // Set the running variable
	          vars.running = 1;
	
				    // Rotate the current resource out
						mtphr_galleries_out( new_resource );
	
						// Rotate the new resource in
						mtphr_galleries_in( new_resource );
	
						// Set the previous & current resource
						vars.previous = vars.current;
						vars.current = new_resource;
	
						// Trigger the after change callback
						after_change_timeout = setTimeout( function() {
						
							mtphr_galleries_resize();
						
							// Clear the next variable
							vars.next = null;
	
							settings.after_change.call( $gallery_container, $gallery );
							$gallery_container.trigger('mtphr_galleries_after_change_single', [resources, vars]);
							$('body').trigger('mtphr_galleries_after_change', [$gallery_container, resources, vars]);
	
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
		    
		    /* --------------------------------------------------------- */
		    /* !Setup the resources - 2.0.0 */
		    /* --------------------------------------------------------- */
		    
		    function mtphr_galleries_setup_resource( new_resource ) {
			    
			    var $resource = $(resources[new_resource]);
		    	if( $resource.hasClass('mtphr-gallery-resource-video') && $resource.find('.mejs-container').length == 0  ) {
			    	$resource.children('video').mediaelementplayer({
							width: '100%',
							height: '100%',
							videoVolume: 'horizontal'
						});
		    	} else if( $resource.hasClass('mtphr-gallery-resource-audio') && $resource.find('.mejs-container').length == 0  ) {
			    	$resource.children('audio').mediaelementplayer({
							width: '100%',
							height: '100%'
						});
		    	} else if( $resource.hasClass('mtphr-gallery-resource-youtube') || $resource.hasClass('mtphr-gallery-resource-vimeo')  ) { 		
		    		var w = $gallery.width(),
								h = w/16*9;
						$resource.find('iframe').width(w).height(h);
		    	}
		    }
		    
		    function mtphr_galleries_reset_resource( current_resource ) {
			    
			    var $resource = $(resources[current_resource]);
		    	if( $resource.hasClass('mtphr-gallery-resource-video') && ($resource.find('.mejs-container').length > 0)  ) {
			    	$resource.find('video')[0].player.pause();
		    	} else if( $resource.hasClass('mtphr-gallery-resource-audio') && ($resource.find('.mejs-container').length > 0)  ) {
			    	$resource.find('audio')[0].player.pause();
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
		    	
		    	// Setup the resource
		    	mtphr_galleries_setup_resource( new_resource );

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
			    
			    // Reset the resource
		    	mtphr_galleries_reset_resource( vars.current );

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

				    var hd_height = gallery_width*.5625;

				    if( $(resources[i]).hasClass('mtphr-gallery-resource-video') || $(resources[i]).hasClass('mtphr-gallery-resource-audio') ) {
				    	$(resources[i]).height( hd_height+'px' );
			    	} 
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
			  
			  
			  
			  
			  
			  /* --------------------------------------------------------- */
			  /* !Set the next item */
			  /* --------------------------------------------------------- */
			  
			  function mtphr_galleries_next() {
				  
				  if(vars.running) return false;

		    	// Find the new resource
		    	var new_resource = parseInt(vars.current + 1);
					if( new_resource == vars.count ) {
						new_resource = 0;
					}
					mtphr_galleries_update( new_resource );
			  }
			  
			  /* --------------------------------------------------------- */
			  /* !Set the previous item */
			  /* --------------------------------------------------------- */
			  
			  function mtphr_galleries_prev() {
				  
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
			  }



		    /* --------------------------------------------------------- */
		    /* !Listen for directional navigation clicks */
		    /* --------------------------------------------------------- */
		    
		    if( $nav_prev ) {

		    	$nav_prev.bind('click', function( e ) {
		    		e.preventDefault();
						mtphr_galleries_prev();
		    	});

		    	$nav_next.bind('click', function(e) {
		    		e.preventDefault();
						mtphr_galleries_next();
		    	});
		    }



		    /* --------------------------------------------------------- */
		    /* !Listen for navigation clicks */
		    /* --------------------------------------------------------- */
		    
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
				
				
				
				/* --------------------------------------------------------- */
		    /* !Listen for external events - 2.0.0 */
		    /* --------------------------------------------------------- */

		    $gallery_container.on('mtphr_gallery_next', function( e ) {
		    	if(vars.running) return false;
		    	mtphr_galleries_next();
				});
				
				$gallery_container.on('mtphr_gallery_prev', function( e ) {
					if(vars.running) return false;
		    	mtphr_galleries_prev();
				});
				
				$gallery_container.on('mtphr_gallery_goto', function( e, pos ) {
					if(vars.running) return false;
		    	mtphr_galleries_update( parseInt(pos) );
				});

				
				
				/* --------------------------------------------------------- */
				/* !iFrame resize */
				/* --------------------------------------------------------- */
				
				function mtphr_galleries_resize_iframe() {
				
					$('.mtphr-galleries-iframe').each( function() {
						
						var w = $(this).parent().width(),
								h = w/16*9;
								
						$(this).width(w).height(h);
					});
				}
				
				
				/* --------------------------------------------------------- */
				/* !Resize */
				/* --------------------------------------------------------- */
				
				function mtphr_galleries_resize() {
					gallery_width = $gallery.width();
			    mtphr_galleries_resize_resources();
			    mtphr_galleries_resize_iframe();
				}





		    /**
		     * Resize listener
		     * Reset the resourceer width
		     *
		     * @since 1.0.0
		     */
		    $(window).resize( function() {
			    mtphr_galleries_resize();
		    });




		    // Trigger the afterLoad callback
        settings.after_load.call($gallery_container, $gallery);
        $gallery_container.trigger('mtphr_galleries_after_load_single', [resources, vars]);
        $('body').trigger('mtphr_galleries_after_load', [$gallery_container, resources, vars]);

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
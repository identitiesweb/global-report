		</div><!-- /.content -->

		<!-- jQuery -->
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		
		<script type="text/javascript">
			
		// AJAX Functions
		// ================
		// Ajax functions are written inline to take advantage of 
		// PHP functions to provide contextual data

			var	pageNumber = 1;
			var pageId = $('.main-entry:first').attr('data-id');
			
			// Perform WP_query to get the correct next article
			function loadPost() {
				$('.spinner').show();

				// Start building the data passed to the ajax function
				var dataArray = {
					'action': 'infinite_scroll',
					'page_id': pageId
				};

				// Add some details

				// Search Query
				<?php if (is_search()): ?>
					// pageNumber++; // overwrite page start to compensate not being able to pass page id
					var searchQuery = "<?php echo get_search_query(); ?>";
					dataArray['search_query'] = searchQuery;
				// Page and Date
				<?php else: ?>
					var pageDate = <?php echo get_post_time('"c"', true, get_the_ID()); ?>;
					dataArray['page_date'] = pageDate;
				<?php endif; ?>
				// Category
				<?php if ( get_query_var('cat') != null ) : ?>
					var catId = <?php echo get_query_var('cat'); ?>;
					dataArray['cat_id'] = catId;
				<?php endif; ?>

				// Page number
				dataArray['page_no'] = pageNumber;

				// Make the call
				$.ajax({
						url: "<?php bloginfo('wpurl') ?>/wp-admin/admin-ajax.php",
						type:'POST',
						data: dataArray, 
						success: function(html){
							$('.spinner').hide();
							$(".main").append(html);
							if ($('.main').height() < $(window).height()) {
								loadPost(pageNumber);
								pageNumber++;
							}
						}
				});
			}

			// Get the permalink for a post
			function pushState(id) {
				var dataArray = {
					'action': 'get_permalink',
					'new_url_page_id': id
				};
				$.ajax({
					url: "<?php bloginfo('wpurl') ?>/wp-admin/admin-ajax.php",
					type: 'POST',
					data: dataArray,
					success: function(permalink) {
						// console.log('ajax return value: ' + permalink);
						window.history.pushState(null, null, permalink);
					}
				});
			}
		</script>
		<!-- Script -->
		<script src="<?php bloginfo('template_url'); ?>/js/script.js" type="text/javascript"></script>

		<?php wp_footer(); ?>
	</body>
</html>
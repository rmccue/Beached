				</div>
			</div>

			<div id="footer">
				<p>Copyright &copy; Ryan McCue. Powered by <a href="http://wordpress.org/">WordPress</a> and the <a href="http://github.com/rmccue/Beached">Beached theme</a>.</p>
				<?php wp_footer() ?>
			</div>
		</div>

		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.1/jquery.min.js" type="text/javascript"></script>
		<script src="<?php echo get_stylesheet_directory_uri() ?>/twitter.js" type="text/javascript"></script>
		<script type="text/javascript" charset="utf-8">
			$(document).ready(function () {
				$.getJSON("http://search.twitter.com/search.json?q=from%3Armccue&callback=?",
					function (data) {
						var status = data["results"][0];
						$('#twitter span').html(ify.clean(status.text));
						$('#twitter .date a').text(relative_time(status.created_at)).attr('href', 'http://twitter.com/rmccue/status/' + status.id);
					}
				);
			});
		</script>
	</body>
</html>
		<?php get_header(); ?>

<?php

require_once 'dist/vendor/mobile_detect/Mobile_Detect.php';
$detect = new Mobile_Detect;

//var_dump($detect);

?>


		<div id="lvc_fullpage"> <!-- Fullpage -->


			<div id="lvc_homepage" class="section" data-anchor="homepage"> <!-- Homepage -->


<?php if( $detect->isiOS() ){ ?>

				<div class="container bg_picture">

					<div class="row">

						<div class="col-xs-12 lvc_logo">

							<img src="<?php echo get_bloginfo('template_url');?>/imgs/logo.png" class="img-responsive">

						</div>

						<div class="hidden-xs col-xs-12 lvc_mainmenu">

							<ul>

								<li><a href="#concept">Concept</a></li>

								<li><a href="#newspress">News & Press</a></li>

								<li><a href="#gallery">Gallery</a></li>

								<li><a href="#place">The estate</a></li>

								<li><a href="#areas">Surrounding areas</a></li>

								<li><a href="#features">Exclusive features</a></li>

								<li><a href="#artdevivre">Other activities</a></li>

								<li><a href="#contact">Who we are</a></li>

							</ul>

						</div>

					</div>					

						<div class="col-xs-12 ios_picture">

						<img src="<?php echo get_bloginfo('template_url');?>/imgs/chateau2.jpg" class="img-responsive">

						</div>

					<div class="baseline_homepage">

						<strong>The New Epitome of the French Art de vivre</strong>

					</div>

				</div>


<?php }else{ ?>


				<div class="container bg_video">

					<div class="row">

						<div class="col-xs-12 lvc_logo">

							<h1>La Vie de Château</h1>

						</div>

						<div class="hidden-xs col-xs-12 lvc_mainmenu">

							<ul>

								<li><a href="#concept">Concept</a></li>

								<li><a href="#newspress">News & Press</a></li>

								<li><a href="#gallery">Gallery</a></li>

								<li><a href="#place">The estate</a></li>

								<li><a href="#areas">Surrounding areas</a></li>

								<li><a href="#features">Exclusive features</a></li>

								<li><a href="#artdevivre">Other activities</a></li>

								<li><a href="#contact">Who we are</a></li>

							</ul>

						</div>

					</div>					

					<div class="row bg_video">

						<div class="trame">

						</div>

					</div>

					<div class="baseline_homepage">

						<strong>The New Epitome of the French Art de vivre</strong>

					</div>

				</div>


<?php } ?>



			</div> <!-- END Homepage -->





			<div id="lvc_concept" class="section" data-anchor="concept"> <!-- Concept -->


				<div class="container">

					<div class="row">

						<div class="col-xs-12">

							<h2><?php echo get_the_title(4); ?></h2>

							<strong><?php echo get('the_concept_contents_baseline',1,1,4); ?></strong>

						</div>

					</div>

					<div class="row">

						<div class="col-sm-6">

							<p><?php echo get('the_concept_contents_text_1',1,1,4); ?></p>

						</div>

						<div class="col-sm-6">

							<p><?php echo get('the_concept_contents_text_2',1,1,4); ?></p>

						</div>

					</div>

				</div>


			</div> <!-- END Concept -->




			<div id="lvc_newspress" class="section" data-anchor="newspress"><!-- News & press -->


				<div class="news_overlay">

				<div class="news_table">

					<div class="news_box">

						<div class="container">

							<div class="row">

								<div class="col-sm-8 col-sm-offset-2 col-xs-12 news_over_text">

								</div>

							</div>

						</div>

					</div>

				</div>

				</div>


				<div class="container">

					<div class="row newspress_title">

						<div class="col-sm-12">

							<h2><?php echo get_the_title(161); ?></h2>

						</div>

					</div>

					<div class="row">

						<div class="col-sm-5 col-sm-offset-1 section_news">

							<div class="row">

								<div class="col-sm-12 title">

								<h3>News</h3>

								</div>

							<?php 

							query_posts('post_type=post&posts_per_page=5');

							if (have_posts()) : while (have_posts()) : the_post();

							?>

								<div class="col-sm-12 news_btn">

								<span class="news_date"><?php echo get_the_date('m-j-Y'); ?></span>

								<span class="access_news"><?php the_title(); ?></span>

								<div class="news_txt"><?php the_content(); ?></div>

								</div>

							<?php 

							endwhile;

							endif;

							wp_reset_query();

							?>

							</div>

						</div>

						<div class="col-sm-5 section_press">

							<div class="row">

							<div class="col-sm-12 title">

								<h3>Press</h3>

							</div>

							<?php $press_conts = get_group('press',161);

							foreach ($press_conts as $press_cont){

							$press_name = $press_cont['press_name'][1];

							$press_file = $press_cont['press_file'][1];

							?>

							<div class="col-sm-12">

							<span class="news_date">Download</span>

							<a href="<?php echo $press_file; ?>" target="_blank"><?php echo $press_name; ?></a>

							</div>

							<?php }; ?>

							</div>

						</div>

					</div>

				</div>

			</div>



			<div id="lvc_gallery" class="section" data-anchor="gallery"> <!-- Gallery -->


				<div class="container-fluid hidden-xs">

					<div class="row">

						<?php

						$gal_txt1 = get('the_gallery_textes_text_1',1,1,5);

						$gal_txt2 = get('the_gallery_textes_text_2',1,1,5);

						$gal_pics = get_field('the_gallery_images_image',1,5);


						shuffle($gal_pics);

						$i=1;

						foreach ($gal_pics as $gal_pic){

							if ( $i > 8 ){ break; }

							echo '<div class="col-sm-3 col-xs-6 blox_container">';

							if ($i == 1){

								echo '<div class="hidden-xs hover_gal_pics">';

								echo '<span>';

								echo $gal_txt1;	

								echo '</span>';

								echo '</div>';

							}

							if ($i == 8){

								echo '<div class="hidden-xs hover_gal_pics">';

								echo '<span>';

								echo $gal_txt2;	

								echo '</span>';

								echo '</div>';

							}


							echo '<img src="'.$gal_pic['original'].'" class="img-responsive square-'.$i.' blox">';

							echo '</div>';		

							$i++;

						};

						?>	


					</div>

				</div>


				<div class="container-fluid visible-xs">

					<div class="row">

						<?php

						$gal_txt1 = get('the_gallery_textes_text_1',1,1,5);

						$gal_txt2 = get('the_gallery_textes_text_2',1,1,5);

						$gal_pics = get_field('the_gallery_images_image',1,5);


						shuffle($gal_pics);

						$i=1;

						foreach ($gal_pics as $gal_pic){

							if ( $i > 6 ){ break; }

							echo '<div class="col-sm-3 col-xs-4 blox_container">';

							echo '<img src="'.$gal_pic['original'].'" class="img-responsive square-'.$i.' blox">';

							echo '</div>';

							$i++;

						};

						echo '<div class="col-xs-12 place_txt_mob">';

						echo $gal_txt1;

						echo '</div>';

						echo '<div class="col-xs-12 place_txt_mob">';

						echo $gal_txt2;

						echo '</div>';

						?>	



					</div>

				</div>


			</div> <!-- END Gallery -->


			<div id="lvc_place" class="section" data-anchor="place"> <!-- The Place -->

				<div class="place_overlay">

					<div class="place_box">

						<div class="container">

							<div class="row">

								<div class="col-sm-8 col-xs-12 place_over_pict">

								</div>

								<div class="col-sm-4 col-xs-12 place_over_text">

								</div>

							</div>

						</div>

					</div>

				</div>


				<div class="container-fluid">

					<?php 

					$categories = get_categories( array (
						'taxonomy' => 'pictures_areas',
						'orderby' => 'count', 
						'order' => 'desc'
						)
					); 
/*
					var_dump($categories);*/

					foreach ($categories as $category_place){

						$data_catname = $category_place->slug;

						query_posts( array( 
							'post_type' => 'the_place',
							'pictures_areas' => $data_catname,
							'posts_per_page' => 6, 
							'orderby' => 'rand'
							) 
						);

						$split = 0;

						$switch = 0;

						?>

						<div class="slide" data-anchor="<?php echo $data_catname; ?>">

							<div class="container-fluid">

								<div class="row blox_place_container">

									<div class="col-xs-12 col-sm-6 blox_place_col">

										<div class="row">


											<?php if (have_posts()) : while (have_posts()) : the_post(); ?> <!-- Loop The Place -->

												<?php 

												$myid = get_the_ID();

												if ( $split++ == 3 ){

													echo '</div></div><div class="col-xs-12 col-sm-6 blox_place_col"><div class="row">';

													$split = 1;	

													if ( $switch == 0 ){

														$switch = 1;

													} else {

														$switch = 0;

													}	

												}

												if ( $switch == 0 ){ 

													if ( $split == 1){

														echo '<div class="col-xs-4 col-sm-12 pic_gal" data-id="'.$myid.'">';

														echo '<div class="the_contents_place">';

														the_content();

														echo '</div>';

														echo '<div class="txt_overflow gal_big">';

														echo '<div class="over_content">';

														echo '<h2 class="place_title">';

														the_title();

														echo '</h2>';

														echo '</div>';

														echo '</div>';

														

														the_post_thumbnail('thetheme-place', array( 'class'	=> "img-responsive" ));

														echo '</div>';

													}else{

														echo '<div class="col-xs-4 col-sm-6 pic_gal" data-id="'.$myid.'">';

														echo '<div class="the_contents_place">';

														the_content();

														echo '</div>';

														echo '<div class="txt_overflow gal_small">';

														echo '<div class="over_content">';

														echo '<h2 class="place_title">';

														the_title();

														echo '</h2>';

														echo '</div>';

														echo '</div>';

														

														the_post_thumbnail('thetheme-place', array( 'class'	=> "img-responsive" ));

														echo '</div>';

													}

												}

												if ( $switch == 1 ){ 

													if ( $split == 3){

														echo '<div class="col-xs-4 col-sm-12 pic_gal" data-id="'.$myid.'">';

														echo '<div class="the_contents_place">';

														the_content();

														echo '</div>';

														echo '<div class="txt_overflow gal_big">';

														echo '<div class="over_content">';

														echo '<h2 class="place_title">';

														the_title();

														echo '</h2>';

														echo '</div>';

														echo '</div>';

														

														the_post_thumbnail('thetheme-place', array( 'class'	=> "img-responsive" ));

														echo '</div>';

													}else{

														echo '<div class="col-xs-4 col-sm-6 pic_gal" data-id="'.$myid.'">';

														echo '<div class="the_contents_place">';

														the_content();

														echo '</div>';

														echo '<div class="txt_overflow gal_small">';

														echo '<div class="over_content">';

														echo '<h2 class="place_title">';

														the_title();

														echo '</h2>';

														echo '</div>';

														echo '</div>';

														

														the_post_thumbnail('thetheme-place', array( 'class'	=> "img-responsive" ));

														echo '</div>';

													}

												}						

												?>


											<?php endwhile; ?> <!-- WHILE Loop The Place -->

										<?php endif; ?> <!-- END Loop The Place -->

										<?php wp_reset_query(); ?> <!-- RESET Loop The Place -->

									</div>

								</div>

							</div>

						</div>

					</div>

					<?php } ?>


					<div class="row place_sub"><!-- sub menu -->

						<ul>

							<?php 

							$categories = get_categories( array (
								'taxonomy' => 'pictures_areas',
								'orderby' => 'count', 
								'order' => 'desc'
								)
							); 

							foreach ($categories as $category_place){

								$data_catmenu = $category_place->slug;

								$data_catnames = $category_place->name;

								echo '<li>';

								echo '<a href="#place/'.$data_catmenu.'">';

								echo $data_catnames;

								echo '</a>';

								echo '</li>';

							}

							?>

						</ul>

					</div>

				</div>


			</div> <!-- END The Place -->


			<div id="lvc_areas" class="section" data-anchor="areas"> <!-- Surounding Areas -->

				<div class="container-fluid">

					<div class="row areas_title">

						<h2><?php echo get_the_title(44); ?></h2>

					</div>

					<div class="area_slides">

						<?php

						$areas_conts = get_group('areas_descriptions',44);

						foreach ($areas_conts as $area_cont){

							$area_title = $area_cont['areas_descriptions_name'][1];

							$area_bline = $area_cont['areas_descriptions_baseline'][1];

							$area_pics =  $area_cont['areas_descriptions_background_image'][1]['original'];

							?>

							<div class="fp-tableCell" data-hash="<?php echo sanitize_title($area_title); ?>" style='background-image:url(<?php echo $area_pics; ?>)'>

								<div class="the_area_contents">

									<div class="the_area_texts">

										<span class="the_area_borders">

											<h3 class="the_area_title"><?php echo $area_title; ?></h3>

											<p class="the_area_base"><?php echo $area_bline; ?></p>

										</span>

									</div>									

								</div>

							</div>




							<?php } ?>	

						</div>		

						<div class="row areas_sub">				

							<div class="areas_footer">

								<div class="col-xs-12">

									<?php

									$areas_conts = get_group('areas_descriptions',44);

									foreach ($areas_conts as $area_cont){

										$area_links = $area_cont['areas_descriptions_name'][1];

										$arealinkssani = sanitize_title($area_links);

										echo '<a href="#' .$arealinkssani. '">';

										echo $area_links;

										echo '</a>';

									}

									?>

								</div>

							</div>

						</div>

					</div>

			</div>
			<div id="lvc_areas" class="section" data-anchor="areas"> <!-- Surounding Areas -->


					<div class="container-fluid">

						<div class="row areas_title">

							<h2><?php echo get_the_title(153); ?></h2>

							<p><?php echo get('baseline_features',1,1,153);	?></p>

						</div>


						<?php 

						$features_slides = get_group('features_list', 153); 

						foreach ($areas_conts as $features_slide){

							$area_title = $area_cont['areas_descriptions_name'][1];

							$area_bline = $area_cont['areas_descriptions_baseline'][1];

							$area_pics =  $area_cont['areas_descriptions_background_image'][1]['original'];
							?>


							<div class="slide artde_slider" style="background-image:url(<?php echo $area_pics; ?>)">

								<div class="row">

									<div class="col-sm-6 col-sm-offset-3">

										<div class="row">

											<div class="col-sm-8 features_title">

												<h3><?php echo $area_title; ?></h3>

											</div>

											<div class="col-sm-4 features_texts">

												<p><?php echo $area_bline; ?></p>

											</div>

										</div>

									</div>

								</div>

							</div>

							<?php } ?>

					</div>


			</div>



				<div id="lvc_features" class="section" data-anchor="features"> <!-- Art de vivre -->


					<div class="container-fluid">

						<div class="row artdevivre_title">

							<h2><?php echo get_the_title(153); ?></h2>

							<p><?php echo get('baseline_features',1,1,153);	?></p>

						</div>


						<?php 

						$features_slides = get_group('features_list', 153); 

						foreach ($features_slides as $features_slide){

							$feat_title = $features_slide['features_list_feature_name'][1];

							$feat_text = $features_slide['features_list_feature_text'][1];

							$feat_pics = $features_slide['features_list_feature_image'][1]['original'];


							?>


							<div class="slide artde_slider" style="background-image:url(<?php echo $feat_pics; ?>)">

								<div class="row">

									<div class="col-sm-6 col-sm-offset-3">

										<div class="row">

											<div class="col-sm-8 features_title">

												<h3><?php echo $feat_title; ?></h3>

											</div>

											<div class="col-sm-4 features_texts">

												<p><?php echo $feat_text; ?></p>

											</div>

										</div>

									</div>

								</div>

							</div>

							<?php } ?>

					</div>


				</div>






				<div id="lvc_artdevivre" class="section" data-anchor="artdevivre"> <!-- Art de vivre -->


					<div class="container-fluid">

						<div class="row artdevivre_title">

							<h2><?php echo get_the_title(47); ?></h2>

							<p><?php echo get('baseline_text',1,1,47);	?></p>

						</div>

						<?php 

						$artde_slides = get_group('art_de_vivre_slides', 47); 

						foreach ($artde_slides as $art_slide){

							$art_title = $art_slide['art_de_vivre_slides_title'][1];

							$art_txt = $art_slide['art_de_vivre_slides_text'][1];

							$art_pics = $art_slide['art_de_vivre_slides_pics'][1]['original'];


							?>


							<div class="slide artde_slider" style="background-image:url(<?php echo $art_pics; ?>)">

								<div class="row">

									<div class="col-sm-6 col-sm-offset-3">

										<div class="row">

											<div class="col-sm-5">

												<h3><?php echo $art_title; ?></h3>

											</div>

											<div class="col-sm-7">

												<p><?php echo $art_txt; ?></p>

											</div>

										</div>

									</div>

								</div>

							</div>

							<?php } ?>

						</div>


					</div>


					<div id="lvc_contact" class="section" data-anchor="contact"> <!-- Contact -->

						<div class="container">

							<div class="row">

								<div class="col-xs-12 contact_title">

									<h2><?php echo get_the_title(51); ?></h2>

								</div>

							</div>

							<div class="row contact_desc">

								<?php echo get_post_field('post_content', 51); ?>

							</div>

							<div class="row contact_address">

								<div class="col-xs-12">

									<?php 

									$contact_logo = get('who_are_we_logo',1,1,51); 

									$contact_address = get('who_are_we_contacts',1,1,51);

									$contact_booking = get('who_are_we_booking_email',1,1,51);

									?>

									<img src="<?php echo $contact_logo; ?>" class="img-responsive logo_footer">

									<div class="contact_address">

										<?php echo $contact_address; ?>

									</div>

									<div class="contact_btn">

										<a href="mailto:<?php echo $contact_booking; ?>" target="_blank" title="reservation">ENQUIRIES</a>

									</div>

								</div>

							</div>

						</div>

					</div>


				</div> <!-- End Fullpage -->


				<script>
					var gal_pix = '<?php echo json_encode($gal_pics); ?>';
					gal_pix = JSON.parse(gal_pix);
					var url_js = '<?php echo get_bloginfo("stylesheet_directory"); ?>';
				</script>


				<?php get_footer(); ?>
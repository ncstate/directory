<?php get_header(); ?>

<body>
	
<?php include get_template_directory() . '/masthead.php'; ?>

<div id="event" class="container" role="main">

	<?php
		$event = get_post_custom();
		var_dump($event);
	?>
	
	<div class="main-content col-md-9">
		<h1><?php echo $event['first_name'][0] . " " . $event['last_name'][0]; ?></h1>
		<p class="title"><?php echo $event['title'][0]; ?></p>
		<p class="email"><a href="mailto:<?php echo $event['email'][0]; ?>"><?php echo $event['email'][0]; ?></a></p>
		<p class="phone"><a href="tel:<?php echo $event['phone'][0]; ?>"><?php echo $event['phone'][0]; ?></a></p>
		
		<?php if (have_posts()) :
		   while (have_posts()) :
		      the_post();
		         the_content();
		   endwhile;
		endif; ?>
	</div>
	
	<aside class="image col-md-3">
		<?php $img_url = wp_get_attachment_image_src($event['image'][0], array(200,200)); ?>
		<?php $img_alt = get_post_meta($event['image'][0], '_wp_attachment_image_alt', true); ?>
		<img src="<?php echo $img_url[0]; ?>" alt="<?php echo $img_alt; ?>" />
	</aside>

</div>

	<?php $map = get_post_meta(get_the_ID(), 'location_map', true); ?>
	
	<script>
	  function initialize() {
		  var myLatlng = new google.maps.LatLng(<?php  echo $map['lat']; ?>, <?php echo $map['lng']; ?>);
	      var mapCanvas = document.getElementById('map-canvas');
	      var mapOptions = {
	        center: myLatlng,
	        zoom: 17,
	        mapTypeId: google.maps.MapTypeId.ROADMAP,
			scrollwheel: false,
		  }
		  var map = new google.maps.Map(mapCanvas, mapOptions);
		  
		  var marker = new google.maps.Marker({
		      position: myLatlng,
		      map: map,
		      title:"<?php echo $event['title'][0]; ?>"
		  });
	  }
	  
	  google.maps.event.addDomListener(window, 'load', initialize);
	</script>
	
	<div id="map-canvas">
		
	</div>
 
<?php get_footer(); ?>
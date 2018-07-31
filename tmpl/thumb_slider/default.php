<?php
/**
 * @version    3.0.0
 * @package    SPEDI Article Gallery
 * @author     SPEDI srl - http://www.spedi.it
 * @copyright  Copyright (c) Spedi srl.
 * @license    GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die;

//asset
$tmpl = JFactory::getApplication()->getTemplate();
JHtml::_('jquery.framework');

// magnificPopup
$extensionPath = '/templates/'.$tmpl.'/dist/magnific/';
if(file_exists(JPATH_SITE.$extensionPath)){
	$document->addStyleSheet(JUri::base(true).'/templates/'.$tmpl.'/dist/magnific/magnific-popup.min.css');
	$document->addScript(JUri::base(true).'/templates/'.$tmpl.'/dist/magnific/jquery.magnific-popup.min.js');
}
else{
	$document->addStyleSheet(JUri::base(true).'/plugins/content/'.$this->plg_name.'/dist/magnific/magnific-popup.min.css');
	$document->addScript(JUri::base(true).'/plugins/content/'.$this->plg_name.'/dist/magnific/jquery.magnific-popup.min.js');
}
// swiper slider
$extensionPath = '/templates/'.$tmpl.'/dist/swiper/';
if(file_exists(JPATH_SITE.$extensionPath)){
	$document->addStyleSheet(JUri::base(true).'/templates/'.$tmpl.'/dist/swiper/swiper.min.css');
	$document->addScript(JUri::base(true).'/templates/'.$tmpl.'/dist/swiper/swiper.min.js');
}
else{
	$document->addStyleSheet(JUri::base(true).'/plugins/content/'.$this->plg_name.'/dist/swiper/swiper.min.css');
	$document->addScript(JUri::base(true).'/plugins/content/'.$this->plg_name.'/dist/swiper/swiper.min.js');
}

// template
$document->addStyleSheet(JURI::base(true).'/plugins/content/'.$this->plg_name.'/tmpl/'.$PlgTmplName.'/css/template.min.css');
$document->addScriptDeclaration("
	jQuery(document).ready(function($){

    var galleryTop = new Swiper('.plg-thumbSlide-".$galleryId." .gallery-top-".$galleryId."', {
      spaceBetween: 10,
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
    });
    var galleryThumbs = new Swiper('.plg-thumbSlide-".$galleryId." .gallery-thumbs-".$galleryId."', {
      spaceBetween: 10,
      centeredSlides: true,
      slidesPerView: 'auto',
      touchRatio: 0.2,
      slideToClickedSlide: true,
    });
    galleryTop.controller.control = galleryThumbs;
    galleryThumbs.controller.control = galleryTop;


    $('.plg-thumbSlide-".$galleryId." .gallery-top-".$galleryId."').magnificPopup({
	    delegate:'a.magnific-overlay',
	    type:'image',
	    gallery:{enabled:true}
	  })

	});
");

?>


<!-- Swiper -->
<div class="articleGgallery thumbSlide-gallery plg-thumbSlide-<?php echo $galleryId; ?> container-fluid px-0">
  <div class="swiper-container top gallery-top-<?php echo $galleryId; ?>">
    <div class="swiper-wrapper">
      <?php foreach($item['response'] as $img) : ?>
        <div class="swiper-slide" style="background-image:url(<?= $img->images[0]->source ?>); height: <?php echo $item['hSlider'] ?>">
          <a class="magnific-overlay" <?php if(isset($img->name)) : ?> title="<?= $img->name ?>" <?php endif; ?> href="<?= $img->images[0]->source ?>"></a>
        </div>
      <?php endforeach; ?>
			<?php if(isset($item['album_link']) AND $item['album_link'] != '') : ?>
				<div class="swiper-slide d-flex align-items-center justify-content-center bg-light" style="height: <?php echo $item['hSlider'] ?>">
					<a href="<?= $item['album_link'] ?>" class="text-center">
						<i class="far fa-plus fa-2x text-primary d-block"></i>
						<p class="b-block text-primary font-weight-light mb-0">Vedi di più</p>
					</a>
				</div>
			<?php endif; ?>
    </div>
    <!-- Add Arrows -->
    <div class="swiper-button-next swiper-button-white"></div>
    <div class="swiper-button-prev swiper-button-white"></div>
  </div>
  <div class="swiper-container thumbs gallery-thumbs-<?php echo $galleryId; ?>">
    <div class="swiper-wrapper">
      <?php foreach($item['response'] as $img) : ?>
        <div class="swiper-slide" style="background-image:url(<?= $img->images[0]->source ?>)"></div>
      <?php endforeach; ?>
			<?php if(isset($item['album_link']) AND $item['album_link'] != '') : ?>
				<div class="swiper-slide bg-white"></div>
			<?php endif; ?>
    </div>
  </div>
</div>

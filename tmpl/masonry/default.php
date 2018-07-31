<?php
/**
 * @version    2.0.0
 * @package    Spedi SPGallery PLuign
 * @author     SPEDI srl http://www.spedi.it
 * @copyright  Copyright (c) 1991 - 2016 Spedi srl. Tutti i diritti riservati.
 * @license    GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

$tmpl     = JFactory::getApplication()->getTemplate();
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
// masonry
$extensionPath = '/templates/'.$tmpl.'/dist/masonry/';
if(file_exists(JPATH_SITE.$extensionPath)){
	$document->addScript(JUri::base(true).'/templates/'.$tmpl.'/dist/masonry/masonry.min.js');
	$document->addScript(JUri::base(true).'/templates/'.$tmpl.'/dist/masonry/lazyload.min.js');
}
else{
	$document->addScript(JUri::base(true).'/plugins/content/'.$this->plg_name.'/dist/masonry/masonry.min.js');
	$document->addScript(JUri::base(true).'/plugins/content/'.$this->plg_name.'/dist/masonry/lazyload.min.js');
}

// template
$document->addStyleSheet(JURI::base(true).'/plugins/content/'.$this->plg_name.'/tmpl/'.$PlgTmplName.'/css/template.min.css');

$document->addScriptDeclaration("
	jQuery(document).ready(function($){
		var articleGgalleryMasonry = $('.plg-masonry-gallery-".$galleryId." .grid-".$galleryId."').masonry({
			itemSelector: '.grid-item-".$galleryId."',
			columnWidth: '.grid-sizer-".$galleryId."',
			percentPosition: true
		});

		articleGgalleryMasonry.imagesLoaded().progress( function() {
			articleGgalleryMasonry.masonry('layout');
		});

		$('.plg-masonry-gallery-".$galleryId."').magnificPopup({
	    delegate:'a.magnific-overlay-".$galleryId."',
	    type:'image',
	    gallery:{enabled:true}
	  })

		// file da 6x6 su card grandi
		if($('.plg-masonry-gallery-".$galleryId."').parent().width() > 576){
			$('.plg-masonry-gallery-".$galleryId."').addClass('largeGallery');
		}

	})
");
?>

<div class="articleGgallery masonry-gallery plg-masonry-gallery-<?php echo $galleryId; ?> container-fluid">
  <div class="row grid-<?php echo $galleryId; ?>">
		<div class="grid-gallery-image grid-sizer-<?php echo $galleryId; ?> col-12 col-sm-6 col-md-<?php echo $item['col'] ?>"></div>
    <?php foreach($item['response'] as $img) : ?>
	    <div class="grid-gallery-image grid-item-<?php echo $galleryId; ?> col-12 col-sm-6 col-md-<?php echo $item['col'] ?>">
	      <figure class="plg-image">
	        <img src="<?= $img->images[0]->source ?>" <?php if(isset($img->name)) : ?> alt="<?= $img->name ?>" <?php endif; ?> class="plg-no-lightbox" />
					<figcaption class="d-flex justify-content-center align-items-center">
						<i class="fas fa-image fa-2x"></i>
	          <!-- <h3><?php //echo $img->title ?></h3> -->
	        </figcaption>
	        <a class="magnific magnific-overlay-<?php echo $galleryId; ?>" <?php if(isset($img->name)) : ?> title="<?= $img->name ?>" <?php endif; ?> href="<?= $img->images[0]->source ?>"></a>
	      </figure>
	    </div>
    <?php endforeach; ?>
		<?php if(isset($item['album_link']) AND $item['album_link'] != '') : ?>
			<div class="cat-link grid-gallery-image grid-item-<?php echo $galleryId; ?> col-12 col-sm-6 col-md-<?php echo $item['col'] ?> bg-light">
				<figure class="plg-image py-3 d-flex justify-content-center align-items-center">
					<a href="<?= $item['album_link'] ?>" class="text-center">
						<i class="far fa-plus fa-2x text-primary d-block"></i>
						<p class="b-block text-primary font-weight-light mb-0">Vedi di più</p>
					</a>
				</figure>
			</div>
		<?php endif; ?>
  </div>
</div>

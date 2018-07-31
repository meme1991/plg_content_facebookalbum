<?php
/**
 * @version    1.0.0
 * @package    SPEDI Facebook Album
 * @author     SPEDI srl - http://www.spedi.it
 * @copyright  Copyright (c) Spedi srl.
 * @license    GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

//asset
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
// modernizr
$extensionPath = '/templates/'.$tmpl.'/dist/modernizr/';
if(file_exists(JPATH_SITE.$extensionPath)){
	$document->addScript(JUri::base(true).'/templates/'.$tmpl.'/dist/modernizr/modernizr-objectfit.js');
}
else{
	$document->addScript(JUri::base(true).'/plugins/content/'.$this->plg_name.'/dist/modernizr/modernizr-objectfit.js');
}

// template
$document->addStyleSheet(JURI::base(true).'/plugins/content/'.$this->plg_name.'/tmpl/'.$PlgTmplName.'/css/template.min.css');
$document->addScriptDeclaration("
	jQuery(document).ready(function($){
		// magnific popup
	  $('.plg-articleGgallery-".$galleryId."').magnificPopup({
	    delegate:'a.magnific-overlay',
	    type:'image',
	    gallery:{enabled:true}
	  })

		// modernizr
		if ( ! Modernizr.objectfit ) {
	    if($('.plg-articleGgallery-".$galleryId." figure').length){
	      $('.plg-articleGgallery-".$galleryId." figure').each(function () {
	        var container = $(this), imgUrl = container.find('img').prop('src');
	        if (imgUrl) {
	          container
	            .css('backgroundImage', 'url(' + imgUrl + ')')
	            .addClass('compat-object-fit')
	            .children('img').hide();
	        }
	      });
	    }
		}

		// file da 6x6 se le card sono molto grandi
		if($('.plg-articleGgallery-".$galleryId."').parent().width() > 576){
			$('.plg-articleGgallery-".$galleryId."').addClass('largeGallery');
		}

	});
");
?>

<div class="articleGgallery grid-gallery plg-articleGgallery-<?php echo $galleryId; ?> container-fluid">
  <div class="row">
    <?php foreach($item['response'] as $img) : ?>
    <div class="grid-gallery-image col-12 col-sm-6 col-md-<?php echo $item['col'] ?>">
      <figure class="plg-image">
        <img src="<?= $img->images[0]->source ?>" <?php if(isset($img->name)) : ?> alt="<?= $img->name ?>" <?php endif; ?> class="plg-no-lightbox" />
        <figcaption class="d-flex justify-content-center align-items-center">
					<i class="fas fa-image fa-2x"></i>
          <!-- <h3><?php //echo $img->title ?></h3> -->
        </figcaption>
        <a class="magnific-overlay" <?php if(isset($img->name)) : ?> title="<?= $img->name ?>" <?php endif; ?> href="<?= $img->images[0]->source ?>"></a>
      </figure>
    </div>
    <?php endforeach; ?>
		<?php if(isset($item['album_link']) AND $item['album_link'] != '') : ?>
			<div class="cat-link grid-gallery-image col-12 col-sm-6 col-md-<?php echo $item['col'] ?> bg-light">
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

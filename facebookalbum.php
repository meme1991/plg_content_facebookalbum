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

jimport('joomla.plugin.plugin');
if (version_compare(JVERSION, '1.6.0', 'ge')){
	jimport('joomla.html.parameter');
}

class plgContentFacebookAlbum extends JPlugin {

	var $plg_name = "facebookalbum";
	var $plg_tag  = "facebookalbum";

	function __construct( &$subject, $params ){
		parent::__construct( $subject, $params );

		// Define the DS constant under Joomla! 3.0+
		if (!defined('DS')){
			define('DS', DIRECTORY_SEPARATOR);
		}
	}

	// Joomla! 2.5+
	function onContentPrepare($context, &$row, &$params, $page = 0){
		$this->renderPhGallery($row, $params, $page = 0);
	}

	// The main function
	function renderPhGallery(&$row, &$params, $page = 0){

		// API
		jimport('joomla.filesystem.file');
		$mainframe    = JFactory::getApplication();
		$document     = JFactory::getDocument();
		$db           = JFactory::getDbo();
		$access_token	= $this->params->get('access_token', '');

		// Check se il plugin è attivato
		if (JPluginHelper::isEnabled('content', $this->plg_name) == false) return;

		// Salvare se il formato della pagina non è quello che vogliamo
		$allowedFormats = array('', 'html', 'feed', 'json');
		if (!in_array(JRequest::getCmd('format'), $allowedFormats)) return;

		// Controllo semplice delle prestazioni per determinare se il plugin dovrebbe elaborare ulteriormente
		if (JString::strpos($row->text, $this->plg_tag) === false) return;

		// Start Plugin
		$regex_one		= '/({facebookalbum\s*)(.*?)(})/si';
		$regex_all		= '/{facebookalbum\s*.*?}/si';
		//$matches 		= array();
		$count_matches	= preg_match_all($regex_all,$row->text,$matches,PREG_OFFSET_CAPTURE | PREG_PATTERN_ORDER);

		//var_dump($matches, $count_matches);

		for($i=0;$i<$count_matches;$i++){
			$tag	= $matches[0][$i][0];
			preg_match($regex_one,$tag,$phocagallery_parts);
			$parts = explode("|", $phocagallery_parts[2]);
			foreach($parts as $value){
				$values = explode("=", $value, 2);
				//es. $values[0] -> catid
				//es. $values[1] -> 1
				/*************************
								ALBUM_ID
				*************************/
				if($values[0] == 'album_id') {
					$param[$i]['album_id'] = $values[1];
				}
				// /*************************
				// 				 IMMAGINI
				// *************************/
				// if($values[0] == 'image') {
				// 	$imageId = explode(",", $values[1]);
				// 	$param[$i]['image'] = $imageId;
				// 	foreach ($param[$i]['image'] as $key => $img) {
				// 		$param[$i]['image'][$key] = 'id = '.$img;
				// 	}
				// 	$param[$i]['image'] = "(".implode(' OR ', $param[$i]['image']).")";
				// }
				/*************************
								 TEMPLATE
				*************************/
				if($values[0] == 'tmpl') {
					$param[$i]['tmpl'] = $values[1];
				}
				/*************************
				LIMITE IMMAGINI DA ESTRARRE
				*************************/
				if($values[0] == 'limit' AND $values[1] != '') {
					$param[$i]['limit'] = (int)$values[1];
				}
				/*************************
					   HEIGHT CONTAINER
				*************************/
				if($values[0] == 'hSlider' AND $values[1] != '') {
					$param[$i]['hSlider'] = $values[1].'px';
				}
				/*************************
					   IMMAGINI PER RIGA
				*************************/
				if($values[0] == 'col' AND $values[1] != '') {
					$param[$i]['col'] = $values[1];
				}
				/*************************
				MOSTRARE IL LINK ALLA CATEGORIA
				*************************/
				if($values[0] == 'album_link' AND $values[1] != '') {
					$param[$i]['album_link'] = $values[1];
				}
				/*************************
									 TAG
				*************************/
				$param[$i]['tag'] = $matches[0][$i][0];
			}

			$uri  = 'https://graph.facebook.com/v2.12/';
			$uri .= $param[$i]['album_id'].'/photos?fields=album%2Cimages%2Clink%2Cid%2Cname&limit='.$param[$i]['limit'];
			$uri .= '&access_token='.$access_token;
			$response = json_decode(file_get_contents($uri));
			// var_dump($access_token);
			// var_dump($response);


			// foreach ($response->data as $album) :
			//   /* salvo link immagine grande da mostrare nel lightbox */
			//   // $bigImg = $album->images[0]->source;
			//   /* link dell'immagine */
			//   // $linkFB = $album->link;
			//   /* descrizione della foto */
			//   // $photoTitle = (isset($album->name)) ? $album->name : "I nostri lavori";
			//   var_dump($album);
			// endforeach;


			// // Create a new query object.
			// $query = $db->getQuery(true);
			// // set query
			// $query->select($db->quoteName(array('title', 'filename')));
			// $query->from($db->quoteName('#__phocagallery'));
			// $query->where($db->quoteName('catid') . ' = '. $db->quote($param[$i]['catid']));
			// if(isset($param[$i]['image']))
			// 	$query->where($param[$i]['image']);
			// $query->order('ordering ASC');
			// if($param[$i]['limit'])
			// 	$query->setLimit($param[$i]['limit']);
			// // Reset the query using our newly populated query object.
			// $db->setQuery($query);
			// // Load the results as a list of stdClass objects (see later for more options on retrieving data).
			// $results = $db->loadObjectList();
			// if(empty($results))
			// 	return;
			//
			// // paramentri della categoria per il link alla fine
			// if($param[$i]['catLink']){
			// 	$query = $db->getQuery(true);
			// 	$query->select($db->quoteName(array('id', 'alias')));
			// 	$query->from($db->quoteName('#__phocagallery_categories'));
			// 	$query->where($db->quoteName('id') . ' = '. $db->quote($param[$i]['catid']));
			// 	$db->setQuery($query);
			// 	$catResult = $db->loadObjectList();
			// }

			/*************************
						QUAERY RESULT
			*************************/
			$param[$i]['response'] = $response->data;

			/*************************
			TEST DEI PARAMETRI OPZIONALI
			*************************/
			if(!isset($param[$i]['tmpl'])) $param[$i]['tmpl'] = 'thumb_slider';
			if(!isset($param[$i]['hSlider'])) $param[$i]['hSlider'] = '400px';
			if(!isset($param[$i]['col'])) $param[$i]['col'] = '4';
		}

		// ----------------------------------- Prepare the output -----------------------------------

		for($k=0;$k<$count_matches;$k++){
			// Fetch the template
			$item        = $param[$k];
			$PlgTmplName = $item['tmpl'];

			$galleryId = substr(md5($k.$item['tag']), 1, 5);

			// recupero del template
			ob_start();
			$templatePath = __DIR__.DS.'tmpl'.DS.$PlgTmplName.'/default.php';
			include ($templatePath);
			$getTemplate = ob_get_contents();
			ob_end_clean();

			// Output
			$plg_html = $getTemplate;
			// Do the replace
			$row->text = str_replace($item['tag'], $plg_html, $row->text);
		}

	} // END FUNCTION

} // END CLASS

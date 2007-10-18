<?php
/**
 * Shows list of all templates on the site.
 *
 * @author Yaron Koren
 */

if (!defined('MEDIAWIKI')) die();

global $IP;
require_once( "$IP/includes/SpecialPage.php" );

global $sfgSpecialPagesSpecialInit;
if ($sfgSpecialPagesSpecialInit) {
	global $wgSpecialPages;
	$wgSpecialPages['Templates'] = 'SFTemplates';

	class SFTemplates extends SpecialPage {

		/**
		 * Constructor
		 */
		public function __construct() {
			smwfInitUserMessages();
			parent::__construct('Templates', '', true);
		}

		function execute() {
			list( $limit, $offset ) = wfCheckLimits();
			$rep = new TemplatesPage();
			return $rep->doQuery( $offset, $limit );
		}
	}
} else {
	SpecialPage::addPage( new SpecialPage('Templates','',true,'doSpecialTemplates',false) );
}

class TemplatesPage extends QueryPage {
	function getName() {
		return "Templates";
	}

	function isExpensive() { return false; }

	function isSyndicated() { return false; }

	function getPageHeader() {
		global $wgUser;
		$sk = $wgUser->getSkin();
		$ct = SpecialPage::getPage('CreateTemplate');
		$create_template_link = $sk->makeKnownLinkObj($ct->getTitle(), $ct->getDescription());
		$header = "<p>" . $create_template_link . ".</p>\n";
		$header .= '<p>' . wfMsg('sf_templates_docu') . "</p><br />\n";
		return $header;
	}

	function getPageFooter() {
	}

	function getSQL() {
		$NStemp = NS_TEMPLATE;
		$dbr = wfGetDB( DB_SLAVE );
		$page = $dbr->tableName( 'page' );
		// QueryPage uses the value from this SQL in an ORDER clause,
		// so return page_title as title.
		return "SELECT 'Templates' as type,
			page_title as title,
			page_title as value
			FROM $page
			WHERE page_namespace = {$NStemp}";
               }

	function sortDescending() {
		return false;
	}

	function getCategoryDefinedByTemplate($template_article) {
		$template_text = $template_article->getContent();
		if (preg_match_all('/\[\[Category:([\w ]*)/', $template_text, $matches)) {
			// get the last match - if there's more than one
			// category tag, there's a good chance that the last
			// one will be the relevant one - the others are
			// probably part of inline queries
			return trim(end($matches[1]));
		}
		return "";
	}

	function formatResult($skin, $result) {
		$title = Title::makeTitle( NS_TEMPLATE, $result->value );
		$text = $skin->makeLinkObj( $title, $title->getText() );
		$category = $this->getCategoryDefinedByTemplate(new Article($title));
		if ($category != '')
			$text .= ' ' . wfMsg('sf_templates_definescat') . ' ' . sffLinkText(NS_CATEGORY, $category);
		return $text;
	}
}

function doSpecialTemplates() {
	list( $limit, $offset ) = wfCheckLimits();
	$rep = new TemplatesPage();
	return $rep->doQuery( $offset, $limit );
}

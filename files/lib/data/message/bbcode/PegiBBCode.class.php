<?php
// wcf imports
require_once(WCF_DIR.'lib/data/message/bbcode/BBCodeParser.class.php');
require_once(WCF_DIR.'lib/data/message/bbcode/BBCode.class.php');

/**
 * Parses the [pegi] bbcode tag, which inserts a defined PEGI Logo.
 *
 * @author	Cetheus
 * @copyright	2011 Cetheus.net <http://www.cetheus.net>
 * @license	Creative Commons BY-ND <http://creativecommons.org/licenses/by-nd/3.0/de/>
 * @package	net.cetheus.wcf.data.message.bbcode.pegi
 * @subpackage	data.message.bbcode
 * @category 	Community Framework
 */
class PegiBBCode implements BBCode {
	/**
	 * @see BBCode::getParsedTag()
	 */
	public function getParsedTag($openingTag, $content, $closingTag, BBCodeParser $parser) {
		// default values
		$age = '';
		$size = '64';  // Standard Size of 64 px width if no value given
		$output = '';
		
		// get attributes
		if (isset($openingTag['attributes'][0])) $age = $openingTag['attributes'][0];
		if (isset($openingTag['attributes'][1])) $size = $openingTag['attributes'][1];
		
		// display BBCode
		if ($parser->getOutputType() == 'text/html') {
			$output = '<div><img src="'.RELATIVE_WCF_DIR.'images/pegi/pegi_'.$age.'.svg" style="width: '.$size.'px;" /> '; // PEGI Age Rating		

			if (isset($content)) { // PEGI Warnings
				$pegivalues = explode(',', $content);
				foreach ($pegivalues as $pegivalue){
					if (file_exists(RELATIVE_WCF_DIR.'images/pegi/pegi_'.strtolower($pegivalue).'.svg')) {
						$output = $output.'<img src="'.RELATIVE_WCF_DIR.'images/pegi/pegi_'.strtolower($pegivalue).'.svg" style="width: '.$size.'px;" /> ';
					}
				}
			}

			$output = $output.'</div>';
		}
		else if ($parser->getOutputType() == 'text/plain') {
			$output = 'Rated: PEGI '.$age;

			if (isset($content)) { // PEGI Warnings
				$output = $output.":\n";
				$pegivalues = explode(',', $content);
				foreach ($pegivalues as $pegivalue){
					$output = $output."- ".$pegivalue."\n";
				}

			}
		}
		return $output;
	}
}
?>
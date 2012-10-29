<?php
/**
 * PHPExcel
 *
 * Copyright (c) 2006 - 2010 PHPExcel
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPExcel
 * @package    RichText
 * @copyright  Copyright (c) 2006 - 2010 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version    1.7.3c, 2010-06-01
 */


/**
 * RichText
 *
 * @category   PHPExcel
 * @package    RichText
 * @copyright  Copyright (c) 2006 - 2010 PHPExcel (http://www.codeplex.com/PHPExcel)
 */
class RichText implements IComparable
{
	/**
	 * Rich text elements
	 *
	 * @var RichText_ITextElement[]
	 */
	private $_richTextElements;

    /**
     * Create a new RichText instance
     *
     * @param 	Cell	$pParent
     * @throws	Exception
     */
    public function __construct(Cell $pCell = null)
    {
    	// Initialise variables
    	$this->_richTextElements = array();

    	// Rich-Text string attached to cell?
    	if (!is_null($pCell)) {
	    	// Add cell text and style
	    	if ($pCell->getValue() != "") {
	    		$objRun = new RichText_Run($pCell->getValue());
	    		$objRun->setFont(clone $pCell->getParent()->getStyle($pCell->getCoordinate())->getFont());
	    		$this->addText($objRun);
	    	}

	    	// Set parent value
	    	$pCell->setValueExplicit($this, Cell_DataType::TYPE_STRING);
    	}
    }

    /**
     * Add text
     *
     * @param 	RichText_ITextElement		$pText		Rich text element
     * @throws 	Exception
     * @return RichText
     */
    public function addText(RichText_ITextElement $pText = null)
    {
    	$this->_richTextElements[] = $pText;
    	return $this;
    }

    /**
     * Create text
     *
     * @param 	string	$pText	Text
     * @return	RichText_TextElement
     * @throws 	Exception
     */
    public function createText($pText = '')
    {
    	$objText = new RichText_TextElement($pText);
    	$this->addText($objText);
    	return $objText;
    }

    /**
     * Create text run
     *
     * @param 	string	$pText	Text
     * @return	RichText_Run
     * @throws 	Exception
     */
    public function createTextRun($pText = '')
    {
    	$objText = new RichText_Run($pText);
    	$this->addText($objText);
    	return $objText;
    }

    /**
     * Get plain text
     *
     * @return string
     */
    public function getPlainText()
    {
    	// Return value
    	$returnValue = '';

    	// Loop through all RichText_ITextElement
    	foreach ($this->_richTextElements as $text) {
    		$returnValue .= $text->getText();
    	}

    	// Return
    	return $returnValue;
    }

    /**
     * Convert to string
     *
     * @return string
     */
    public function __toString() {
    	return $this->getPlainText();
    }

    /**
     * Get Rich Text elements
     *
     * @return RichText_ITextElement[]
     */
    public function getRichTextElements()
    {
    	return $this->_richTextElements;
    }

    /**
     * Set Rich Text elements
     *
     * @param 	RichText_ITextElement[]	$pElements		Array of elements
     * @throws 	Exception
     * @return RichText
     */
    public function setRichTextElements($pElements = null)
    {
    	if (is_array($pElements)) {
    		$this->_richTextElements = $pElements;
    	} else {
    		throw new Exception("Invalid RichText_ITextElement[] array passed.");
    	}
    	return $this;
    }

	/**
	 * Get hash code
	 *
	 * @return string	Hash code
	 */
	public function getHashCode() {
		$hashElements = '';
		foreach ($this->_richTextElements as $element) {
			$hashElements .= $element->getHashCode();
		}

    	return md5(
    		  $hashElements
    		. __CLASS__
    	);
    }

	/**
	 * Implement PHP __clone to create a deep clone, not just a shallow copy.
	 */
	public function __clone() {
		$vars = get_object_vars($this);
		foreach ($vars as $key => $value) {
			if (is_object($value)) {
				$this->$key = clone $value;
			} else {
				$this->$key = $value;
			}
		}
	}
}

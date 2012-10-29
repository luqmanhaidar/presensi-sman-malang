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
 * @package    Worksheet
 * @copyright  Copyright (c) 2006 - 2010 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version    1.7.3c, 2010-06-01
 */


/**
 * Worksheet
 *
 * @category   PHPExcel
 * @package    Worksheet
 * @copyright  Copyright (c) 2006 - 2010 PHPExcel (http://www.codeplex.com/PHPExcel)
 */
class Worksheet implements IComparable
{
	/* Break types */
	const BREAK_NONE	= 0;
	const BREAK_ROW		= 1;
	const BREAK_COLUMN	= 2;

	/* Sheet state */
	const SHEETSTATE_VISIBLE 	= 'visible';
	const SHEETSTATE_HIDDEN 	= 'hidden';
	const SHEETSTATE_VERYHIDDEN = 'veryHidden';

	/**
	 * Invalid characters in sheet title
	 *
	 * @var array
	 */
	private static $_invalidCharacters = array('*', ':', '/', '\\', '?', '[', ']');

	/**
	 * Parent spreadsheet
	 *
	 * @var PHPExcel
	 */
	private $_parent;

	/**
	 * Cacheable collection of cells
	 *
	 * @var CachedObjectStorage_xxx
	 */
	private $_cellCollection = null;

	/**
	 * Collection of row dimensions
	 *
	 * @var Worksheet_RowDimension[]
	 */
	private $_rowDimensions = array();

	/**
	 * Default row dimension
	 *
	 * @var Worksheet_RowDimension
	 */
	private $_defaultRowDimension = null;

	/**
	 * Collection of column dimensions
	 *
	 * @var Worksheet_ColumnDimension[]
	 */
	private $_columnDimensions = array();

	/**
	 * Default column dimension
	 *
	 * @var Worksheet_ColumnDimension
	 */
	private $_defaultColumnDimension = null;

	/**
	 * Collection of drawings
	 *
	 * @var Worksheet_BaseDrawing[]
	 */
	private $_drawingCollection = null;

	/**
	 * Worksheet title
	 *
	 * @var string
	 */
	private $_title;

	/**
	 * Sheet state
	 *
	 * @var string
	 */
	private $_sheetState;

	/**
	 * Page setup
	 *
	 * @var Worksheet_PageSetup
	 */
	private $_pageSetup;

	/**
	 * Page margins
	 *
	 * @var Worksheet_PageMargins
	 */
	private $_pageMargins;

	/**
	 * Page header/footer
	 *
	 * @var Worksheet_HeaderFooter
	 */
	private $_headerFooter;

	/**
	 * Sheet view
	 *
	 * @var Worksheet_SheetView
	 */
	private $_sheetView;

	/**
	 * Protection
	 *
	 * @var Worksheet_Protection
	 */
	private $_protection;

	/**
	 * Collection of styles
	 *
	 * @var Style[]
	 */
	private $_styles = array();

	/**
	 * Conditional styles. Indexed by cell coordinate, e.g. 'A1'
	 *
	 * @var array
	 */
	private $_conditionalStylesCollection = array();

	/**
	 * Is the current cell collection sorted already?
	 *
	 * @var boolean
	 */
	private $_cellCollectionIsSorted = false;

	/**
	 * Collection of breaks
	 *
	 * @var array
	 */
	private $_breaks = array();

	/**
	 * Collection of merged cell ranges
	 *
	 * @var array
	 */
	private $_mergeCells = array();

	/**
	 * Collection of protected cell ranges
	 *
	 * @var array
	 */
	private $_protectedCells = array();

	/**
	 * Autofilter Range
	 *
	 * @var string
	 */
	private $_autoFilter = '';

	/**
	 * Freeze pane
	 *
	 * @var string
	 */
	private $_freezePane = '';

	/**
	 * Show gridlines?
	 *
	 * @var boolean
	 */
	private $_showGridlines = true;

	/**
	* Print gridlines?
	*
	* @var boolean
	*/
	private $_printGridlines = false;

	/**
	* Show row and column headers?
	*
	* @var boolean
	*/
	private $_showRowColHeaders = true;

	/**
	 * Show summary below? (Row/Column outline)
	 *
	 * @var boolean
	 */
	private $_showSummaryBelow = true;

	/**
	 * Show summary right? (Row/Column outline)
	 *
	 * @var boolean
	 */
	private $_showSummaryRight = true;

	/**
	 * Collection of comments
	 *
	 * @var Comment[]
	 */
	private $_comments = array();

	/**
	 * Active cell. (Only one!)
	 *
	 * @var string
	 */
	private $_activeCell = 'A1';

	/**
	 * Selected cells
	 *
	 * @var string
	 */
	private $_selectedCells = 'A1';

	/**
	 * Cached highest column
	 *
	 * @var string
	 */
	private $_cachedHighestColumn = 'A';

	/**
	 * Cached highest row
	 *
	 * @var int
	 */
	private $_cachedHighestRow = 1;

	/**
	 * Right-to-left?
	 *
	 * @var boolean
	 */
	private $_rightToLeft = false;

	/**
	 * Hyperlinks. Indexed by cell coordinate, e.g. 'A1'
	 *
	 * @var array
	 */
	private $_hyperlinkCollection = array();

	/**
	 * Data validation objects. Indexed by cell coordinate, e.g. 'A1'
	 *
	 * @var array
	 */
	private $_dataValidationCollection = array();

	/**
	 * Tab color
	 *
	 * @var Style_Color
	 */
	private $_tabColor;

	/**
	 * Create a new worksheet
	 *
	 * @param PHPExcel 		$pParent
	 * @param string 		$pTitle
	 */
	public function __construct(PHPExcel $pParent = null, $pTitle = 'Worksheet')
	{
		// Set parent and title
		$this->_parent = $pParent;
		$this->setTitle($pTitle);
		$this->setSheetState(Worksheet::SHEETSTATE_VISIBLE);

		$this->_cellCollection		= CachedObjectStorageFactory::getInstance($this);

		// Set page setup
		$this->_pageSetup 			= new Worksheet_PageSetup();

		// Set page margins
		$this->_pageMargins 		= new Worksheet_PageMargins();

		// Set page header/footer
		$this->_headerFooter 		= new Worksheet_HeaderFooter();

		// Set sheet view
		$this->_sheetView           = new Worksheet_SheetView();

    	// Drawing collection
    	$this->_drawingCollection 	= new ArrayObject();

    	// Protection
    	$this->_protection			= new Worksheet_Protection();

    	// Gridlines
    	$this->_showGridlines		= true;
		$this->_printGridlines		= false;

    	// Outline summary
    	$this->_showSummaryBelow	= true;
    	$this->_showSummaryRight	= true;

    	// Default row dimension
    	$this->_defaultRowDimension = new Worksheet_RowDimension(null);

    	// Default column dimension
    	$this->_defaultColumnDimension = new Worksheet_ColumnDimension(null);
	}


	public function disconnectCells() {
		$this->_cellCollection->unsetWorksheetCells();
		$this->_cellCollection = null;

		//	detach ourself from the workbook, so that it can then delete this worksheet successfully
		$this->_parent = null;
	}

	/**
	 * Return the cache controller for the cell collection
	 *
	 * @return CachedObjectStorage_xxx
	 */
	public function getCellCacheController() {
		return $this->_cellCollection;
	}	//	function getCellCacheController()


	/**
	 * Get array of invalid characters for sheet title
	 *
	 * @return array
	 */
	public static function getInvalidCharacters()
	{
		return self::$_invalidCharacters;
	}

	/**
	 * Check sheet title for valid Excel syntax
	 *
	 * @param string $pValue The string to check
	 * @return string The valid string
	 * @throws Exception
	 */
	private static function _checkSheetTitle($pValue)
	{
		// Some of the printable ASCII characters are invalid:  * : / \ ? [ ]
		if (str_replace(self::$_invalidCharacters, '', $pValue) !== $pValue) {
			throw new Exception('Invalid character found in sheet title');
		}

		// Maximum 31 characters allowed for sheet title
		if (Shared_String::CountCharacters($pValue) > 31) {
			throw new Exception('Maximum 31 characters allowed in sheet title.');
		}

		return $pValue;
	}

	/**
	 * Get collection of cells
	 *
	 * @param boolean $pSorted Also sort the cell collection?
	 * @return Cell[]
	 */
	public function getCellCollection($pSorted = true)
	{
		if ($pSorted) {
			// Re-order cell collection
			return $this->sortCellCollection();
		}
		if (!is_null($this->_cellCollection)) {
			return $this->_cellCollection->getCellList();
		}
		return array();
	}

	/**
	 * Sort collection of cells
	 *
	 * @return Worksheet
	 */
	public function sortCellCollection()
	{
		if (!is_null($this->_cellCollection)) {
			return $this->_cellCollection->getSortedCellList();
			}
		return array();
	}

	/**
	 * Get collection of row dimensions
	 *
	 * @return Worksheet_RowDimension[]
	 */
	public function getRowDimensions()
	{
		return $this->_rowDimensions;
	}

	/**
	 * Get default row dimension
	 *
	 * @return Worksheet_RowDimension
	 */
	public function getDefaultRowDimension()
	{
		return $this->_defaultRowDimension;
	}

	/**
	 * Get collection of column dimensions
	 *
	 * @return Worksheet_ColumnDimension[]
	 */
	public function getColumnDimensions()
	{
		return $this->_columnDimensions;
	}

	/**
	 * Get default column dimension
	 *
	 * @return Worksheet_ColumnDimension
	 */
	public function getDefaultColumnDimension()
	{
		return $this->_defaultColumnDimension;
	}

	/**
	 * Get collection of drawings
	 *
	 * @return Worksheet_BaseDrawing[]
	 */
	public function getDrawingCollection()
	{
		return $this->_drawingCollection;
	}

	/**
	 * Refresh column dimensions
	 *
	 * @return Worksheet
	 */
	public function refreshColumnDimensions()
	{
		$currentColumnDimensions = $this->getColumnDimensions();
		$newColumnDimensions = array();

		foreach ($currentColumnDimensions as $objColumnDimension) {
			$newColumnDimensions[$objColumnDimension->getColumnIndex()] = $objColumnDimension;
		}

		$this->_columnDimensions = $newColumnDimensions;

		return $this;
	}

	/**
	 * Refresh row dimensions
	 *
	 * @return Worksheet
	 */
	public function refreshRowDimensions()
	{
		$currentRowDimensions = $this->getRowDimensions();
		$newRowDimensions = array();

		foreach ($currentRowDimensions as $objRowDimension) {
			$newRowDimensions[$objRowDimension->getRowIndex()] = $objRowDimension;
		}

		$this->_rowDimensions = $newRowDimensions;

		return $this;
	}

    /**
     * Calculate worksheet dimension
     *
     * @return string  String containing the dimension of this worksheet
     */
    public function calculateWorksheetDimension()
    {
        // Return
        return 'A1' . ':' .  $this->getHighestColumn() . $this->getHighestRow();
    }

	/**
	 * Calculate widths for auto-size columns
	 *
	 * @param  boolean  $calculateMergeCells  Calculate merge cell width
	 * @return Worksheet;
	 */
	public function calculateColumnWidths($calculateMergeCells = false)
	{
		// initialize $autoSizes array
		$autoSizes = array();
		foreach ($this->getColumnDimensions() as $colDimension) {
			if ($colDimension->getAutoSize()) {
				$autoSizes[$colDimension->getColumnIndex()] = -1;
			}
		}

		// There is only something to do if there are some auto-size columns
		if (!empty($autoSizes)) {

			// build list of cells references that participate in a merge
			$isMergeCell = array();
			foreach ($this->getMergeCells() as $cells) {
				foreach (Cell::extractAllCellReferencesInRange($cells) as $cellReference) {
					$isMergeCell[$cellReference] = true;
				}
			}

			// loop through all cells in the worksheet
			foreach ($this->getCellCollection(false) as $cellID) {
				$cell = $this->getCell($cellID);
				if (isset($autoSizes[$cell->getColumn()])) {
					// Determine width if cell does not participate in a merge
					if (!isset($isMergeCell[$cell->getCoordinate()])) {
						// Calculated value
						$cellValue = $cell->getCalculatedValue();

						// To formatted string
						$cellValue = Style_NumberFormat::toFormattedString($cellValue, $this->getParent()->getCellXfByIndex($cell->getXfIndex())->getNumberFormat()->getFormatCode());

						$autoSizes[$cell->getColumn()] = max(
							(float)$autoSizes[$cell->getColumn()],
							(float)Shared_Font::calculateColumnWidth(
								$this->getParent()->getCellXfByIndex($cell->getXfIndex())->getFont(),
								$cellValue,
								$this->getParent()->getCellXfByIndex($cell->getXfIndex())->getAlignment()->getTextRotation(),
								$this->getDefaultStyle()->getFont()
							)
						);
					}
				}
			}

			// adjust column widths
			foreach ($autoSizes as $columnIndex => $width) {
				if ($width == -1) $width = $this->getDefaultColumnDimension()->getWidth();
				$this->getColumnDimension($columnIndex)->setWidth($width);
			}
		}

		return $this;
    }

    /**
     * Get parent
     *
     * @return PHPExcel
     */
    public function getParent() {
    	return $this->_parent;
    }

    /**
     * Re-bind parent
     *
     * @param PHPExcel $parent
     * @return Worksheet
     */
    public function rebindParent(PHPExcel $parent) {
		$namedRanges = $this->_parent->getNamedRanges();
		foreach ($namedRanges as $namedRange) {
			$parent->addNamedRange($namedRange);
		}

		$this->_parent->removeSheetByIndex(
			$this->_parent->getIndex($this)
		);
		$this->_parent = $parent;

		return $this;
    }

	/**
	 * Get title
	 *
	 * @return string
	 */
	public function getTitle()
	{
		return $this->_title;
	}

    /**
     * Set title
     *
     * @param string $pValue String containing the dimension of this worksheet
	 * @return Worksheet
     */
    public function setTitle($pValue = 'Worksheet')
    {
    	// Is this a 'rename' or not?
    	if ($this->getTitle() == $pValue) {
    		return;
    	}

		// Syntax check
		self::_checkSheetTitle($pValue);

    	// Old title
    	$oldTitle = $this->getTitle();

		// Is there already such sheet name?
		if ($this->getParent()->getSheetByName($pValue)) {
			// Use name, but append with lowest possible integer

			$i = 1;
			while ($this->getParent()->getSheetByName($pValue . ' ' . $i)) {
				++$i;
			}

			$altTitle = $pValue . ' ' . $i;
			$this->setTitle($altTitle);

			return;
		}

		// Set title
        $this->_title = $pValue;

    	// New title
    	$newTitle = $this->getTitle();
    	ReferenceHelper::getInstance()->updateNamedFormulas($this->getParent(), $oldTitle, $newTitle);

    	return $this;
    }

	/**
	 * Get sheet state
	 *
	 * @return string Sheet state (visible, hidden, veryHidden)
	 */
	public function getSheetState() {
		return $this->_sheetState;
	}

	/**
	 * Set sheet state
	 *
	 * @param string $value Sheet state (visible, hidden, veryHidden)
	 * @return Worksheet
	 */
	public function setSheetState($value = Worksheet::SHEETSTATE_VISIBLE) {
		$this->_sheetState = $value;
		return $this;
	}

    /**
     * Get page setup
     *
     * @return Worksheet_PageSetup
     */
    public function getPageSetup()
    {
    	return $this->_pageSetup;
    }

    /**
     * Set page setup
     *
     * @param Worksheet_PageSetup	$pValue
     * @return Worksheet
     */
    public function setPageSetup(Worksheet_PageSetup $pValue)
    {
   		$this->_pageSetup = $pValue;
   		return $this;
    }

    /**
     * Get page margins
     *
     * @return Worksheet_PageMargins
     */
    public function getPageMargins()
    {
    	return $this->_pageMargins;
    }

    /**
     * Set page margins
     *
     * @param Worksheet_PageMargins	$pValue
     * @return Worksheet
     */
    public function setPageMargins(Worksheet_PageMargins $pValue)
    {
   		$this->_pageMargins = $pValue;
   		return $this;
    }

    /**
     * Get page header/footer
     *
     * @return Worksheet_HeaderFooter
     */
    public function getHeaderFooter()
    {
    	return $this->_headerFooter;
    }

    /**
     * Set page header/footer
     *
     * @param Worksheet_HeaderFooter	$pValue
     * @return Worksheet
     */
    public function setHeaderFooter(Worksheet_HeaderFooter $pValue)
    {
    	$this->_headerFooter = $pValue;
    	return $this;
    }

    /**
     * Get sheet view
     *
     * @return Worksheet_HeaderFooter
     */
    public function getSheetView()
    {
    	return $this->_sheetView;
    }

    /**
     * Set sheet view
     *
     * @param Worksheet_SheetView	$pValue
     * @return Worksheet
     */
    public function setSheetView(Worksheet_SheetView $pValue)
    {
    	$this->_sheetView = $pValue;
    	return $this;
    }

    /**
     * Get Protection
     *
     * @return Worksheet_Protection
     */
    public function getProtection()
    {
    	return $this->_protection;
    }

    /**
     * Set Protection
     *
     * @param Worksheet_Protection	$pValue
     * @return Worksheet
     */
    public function setProtection(Worksheet_Protection $pValue)
    {
   		$this->_protection = $pValue;
   		return $this;
    }

    /**
     * Get highest worksheet column
     *
     * @return string Highest column name
     */
    public function getHighestColumn()
    {
		return $this->_cachedHighestColumn;
    }

    /**
     * Get highest worksheet row
     *
     * @return int Highest row number
     */
    public function getHighestRow()
    {
		return $this->_cachedHighestRow;
    }

    /**
     * Set a cell value
     *
     * @param string 	$pCoordinate	Coordinate of the cell
     * @param mixed 	$pValue			Value of the cell
     * @param bool 		$returnCell		Return the worksheet (false, default) or the cell (true)
     * @return Worksheet|Cell	Depending on the last parameter being specified
     */
    public function setCellValue($pCoordinate = 'A1', $pValue = null, $returnCell = false)
    {
		$cell = $this->getCell($pCoordinate);
		$cell->setValue($pValue);

		if ($returnCell) {
			return $cell;
		}
    	return $this;
    }

    /**
     * Set a cell value by using numeric cell coordinates
     *
     * @param string 	$pColumn		Numeric column coordinate of the cell
     * @param string 	$pRow			Numeric row coordinate of the cell
     * @param mixed 	$pValue			Value of the cell
	 * @param bool 		$returnCell		Return the worksheet (false, default) or the cell (true)
     * @return Worksheet|Cell	Depending on the last parameter being specified
     */
    public function setCellValueByColumnAndRow($pColumn = 0, $pRow = 0, $pValue = null, $returnCell = false)
    {
    	$cell = $this->getCell(Cell::stringFromColumnIndex($pColumn) . $pRow);
		$cell->setValue($pValue);

		if ($returnCell) {
			return $cell;
		}
    	return $this;
    }

    /**
     * Set a cell value
     *
     * @param string 	$pCoordinate	Coordinate of the cell
     * @param mixed 	$pValue			Value of the cell
     * @param string	$pDataType		Explicit data type
     * @return Worksheet
     */
    public function setCellValueExplicit($pCoordinate = 'A1', $pValue = null, $pDataType = Cell_DataType::TYPE_STRING)
    {
    	// Set value
    	$this->getCell($pCoordinate)->setValueExplicit($pValue, $pDataType);
    	return $this;
    }

    /**
     * Set a cell value by using numeric cell coordinates
     *
     * @param string 	$pColumn		Numeric column coordinate of the cell
     * @param string 	$pRow			Numeric row coordinate of the cell
     * @param mixed 	$pValue			Value of the cell
     * @param string	$pDataType		Explicit data type
     * @return Worksheet
     */
    public function setCellValueExplicitByColumnAndRow($pColumn = 0, $pRow = 0, $pValue = null, $pDataType = Cell_DataType::TYPE_STRING)
    {
    	return $this->getCell(Cell::stringFromColumnIndex($pColumn) . $pRow)->setValueExplicit($pValue, $pDataType);
    }

    /**
     * Get cell at a specific coordinate
     *
     * @param 	string 			$pCoordinate	Coordinate of the cell
     * @throws 	Exception
     * @return 	Cell 	Cell that was found
     */
    public function getCell($pCoordinate = 'A1')
    {
		// Check cell collection
		if ($this->_cellCollection->isDataSet($pCoordinate)) {
			return $this->_cellCollection->getCacheData($pCoordinate);
		}

		// Worksheet reference?
		if (strpos($pCoordinate, '!') !== false) {
			$worksheetReference = Worksheet::extractSheetTitle($pCoordinate, true);
			return $this->getParent()->getSheetByName($worksheetReference[0])->getCell($worksheetReference[1]);
		}

		// Named range?
		if ((!preg_match('/^'.Calculation::CALCULATION_REGEXP_CELLREF.'$/i', $pCoordinate, $matches)) &&
			(preg_match('/^'.Calculation::CALCULATION_REGEXP_NAMEDRANGE.'$/i', $pCoordinate, $matches))) {
			$namedRange = NamedRange::resolveRange($pCoordinate, $this);
			if (!is_null($namedRange)) {
				$pCoordinate = $namedRange->getRange();
				return $namedRange->getWorksheet()->getCell($pCoordinate);
			}
		}

    	// Uppercase coordinate
    	$pCoordinate = strtoupper($pCoordinate);

    	if (strpos($pCoordinate,':') !== false || strpos($pCoordinate,',') !== false) {
    		throw new Exception('Cell coordinate can not be a range of cells.');
    	} elseif (strpos($pCoordinate,'$') !== false) {
    		throw new Exception('Cell coordinate must not be absolute.');
    	} else {
			// Create new cell object

			// Coordinates
			$aCoordinates = Cell::coordinateFromString($pCoordinate);

			$cell = $this->_cellCollection->addCacheData($pCoordinate,new Cell($aCoordinates[0], $aCoordinates[1], null, Cell_DataType::TYPE_NULL, $this));
			$this->_cellCollectionIsSorted = false;

			if (Cell::columnIndexFromString($this->_cachedHighestColumn) < Cell::columnIndexFromString($aCoordinates[0]))
				$this->_cachedHighestColumn = $aCoordinates[0];

			if ($this->_cachedHighestRow < $aCoordinates[1])
				$this->_cachedHighestRow = $aCoordinates[1];

			// Cell needs appropriate xfIndex
			$rowDimensions    = $this->getRowDimensions();
			$columnDimensions = $this->getColumnDimensions();

			if ( isset($rowDimensions[$aCoordinates[1]]) && $rowDimensions[$aCoordinates[1]]->getXfIndex() !== null ) {
				// then there is a row dimension with explicit style, assign it to the cell
				$cell->setXfIndex($rowDimensions[$aCoordinates[1]]->getXfIndex());
			} else if ( isset($columnDimensions[$aCoordinates[0]]) ) {
				// then there is a column dimension, assign it to the cell
				$cell->setXfIndex($columnDimensions[$aCoordinates[0]]->getXfIndex());
			} else {
				// set to default index
				$cell->setXfIndex(0);
			}

			return $cell;
    	}
    }

    /**
     * Get cell at a specific coordinate by using numeric cell coordinates
     *
     * @param 	string $pColumn		Numeric column coordinate of the cell
     * @param 	string $pRow		Numeric row coordinate of the cell
     * @return 	Cell 		Cell that was found
     */
    public function getCellByColumnAndRow($pColumn = 0, $pRow = 0)
    {
		$columnLetter = Cell::stringFromColumnIndex($pColumn);
		$coordinate = $columnLetter . $pRow;

		if (!$this->_cellCollection->isDataSet($coordinate)) {
			$cell = $this->_cellCollection->addCacheData($coordinate, new Cell($columnLetter, $pRow, null, Cell_DataType::TYPE_NULL, $this));
			$this->_cellCollectionIsSorted = false;

			if (Cell::columnIndexFromString($this->_cachedHighestColumn) < $pColumn)
				$this->_cachedHighestColumn = $columnLetter;

			if ($this->_cachedHighestRow < $pRow)
				$this->_cachedHighestRow = $pRow;

			return $cell;
		}

		return $this->_cellCollection->getCacheData($coordinate);
    }

    /**
     * Cell at a specific coordinate exists?
     *
     * @param 	string 			$pCoordinate	Coordinate of the cell
     * @throws 	Exception
     * @return 	boolean
     */
    public function cellExists($pCoordinate = 'A1')
    {
    	// Worksheet reference?
		if (strpos($pCoordinate, '!') !== false) {
			$worksheetReference = Worksheet::extractSheetTitle($pCoordinate, true);
			return $this->getParent()->getSheetByName($worksheetReference[0])->cellExists($worksheetReference[1]);
		}

		// Named range?
		if ((!preg_match('/^'.Calculation::CALCULATION_REGEXP_CELLREF.'$/i', $pCoordinate, $matches)) &&
			(preg_match('/^'.Calculation::CALCULATION_REGEXP_NAMEDRANGE.'$/i', $pCoordinate, $matches))) {
			$namedRange = NamedRange::resolveRange($pCoordinate, $this);
			if (!is_null($namedRange)) {
				$pCoordinate = $namedRange->getRange();
				if ($this->getHashCode() != $namedRange->getWorksheet()->getHashCode()) {
					if (!$namedRange->getLocalOnly()) {
						return $namedRange->getWorksheet()->cellExists($pCoordinate);
					} else {
						throw new Exception('Named range ' . $namedRange->getName() . ' is not accessible from within sheet ' . $this->getTitle());
					}
				}
			}
		}

    	// Uppercase coordinate
    	$pCoordinate = strtoupper($pCoordinate);

    	if (strpos($pCoordinate,':') !== false || strpos($pCoordinate,',') !== false) {
    		throw new Exception('Cell coordinate can not be a range of cells.');
    	} elseif (strpos($pCoordinate,'$') !== false) {
    		throw new Exception('Cell coordinate must not be absolute.');
    	} else {
	    	// Coordinates
	    	$aCoordinates = Cell::coordinateFromString($pCoordinate);

	        // Cell exists?
			return $this->_cellCollection->isDataSet($pCoordinate);
    	}
    }

    /**
     * Cell at a specific coordinate by using numeric cell coordinates exists?
     *
     * @param 	string $pColumn		Numeric column coordinate of the cell
     * @param 	string $pRow		Numeric row coordinate of the cell
     * @return 	boolean
     */
    public function cellExistsByColumnAndRow($pColumn = 0, $pRow = 0)
    {
    	return $this->cellExists(Cell::stringFromColumnIndex($pColumn) . $pRow);
    }

    /**
     * Get row dimension at a specific row
     *
     * @param int $pRow	Numeric index of the row
     * @return Worksheet_RowDimension
     */
    public function getRowDimension($pRow = 0)
    {
    	// Found
    	$found = null;

        // Get row dimension
        if (!isset($this->_rowDimensions[$pRow])) {
        	$this->_rowDimensions[$pRow] = new Worksheet_RowDimension($pRow);

			if ($this->_cachedHighestRow < $pRow)
				$this->_cachedHighestRow = $pRow;
        }
        return $this->_rowDimensions[$pRow];
    }

    /**
     * Get column dimension at a specific column
     *
     * @param string $pColumn	String index of the column
     * @return Worksheet_ColumnDimension
     */
    public function getColumnDimension($pColumn = 'A')
    {
    	// Uppercase coordinate
    	$pColumn = strtoupper($pColumn);

    	// Fetch dimensions
    	if (!isset($this->_columnDimensions[$pColumn])) {
    		$this->_columnDimensions[$pColumn] = new Worksheet_ColumnDimension($pColumn);

			if (Cell::columnIndexFromString($this->_cachedHighestColumn) < Cell::columnIndexFromString($pColumn))
				$this->_cachedHighestColumn = $pColumn;
    	}
    	return $this->_columnDimensions[$pColumn];
    }

    /**
     * Get column dimension at a specific column by using numeric cell coordinates
     *
     * @param 	string $pColumn		Numeric column coordinate of the cell
     * @param 	string $pRow		Numeric row coordinate of the cell
     * @return 	Worksheet_ColumnDimension
     */
    public function getColumnDimensionByColumn($pColumn = 0)
    {
        return $this->getColumnDimension(Cell::stringFromColumnIndex($pColumn));
    }

    /**
     * Get styles
     *
     * @return Style[]
     */
    public function getStyles()
    {
    	return $this->_styles;
    }

    /**
     * Get default style of workbork.
     *
     * @deprecated
     * @return 	Style
     * @throws 	Exception
     */
    public function getDefaultStyle()
    {
    	return $this->_parent->getDefaultStyle();
    }

    /**
     * Set default style - should only be used by IReader implementations!
     *
     * @deprecated
     * @param 	Style $value
     * @throws 	Exception
     * @return Worksheet
     */
    public function setDefaultStyle(Style $pValue)
    {
		$this->_parent->getDefaultStyle()->applyFromArray(array(
			'font' => array(
				'name' => $pValue->getFont()->getName(),
				'size' => $pValue->getFont()->getSize(),
			),
		));
		return $this;
    }

    /**
     * Get style for cell
     *
     * @param 	string 	$pCellCoordinate	Cell coordinate to get style for
     * @return 	Style
     * @throws 	Exception
     */
    public function getStyle($pCellCoordinate = 'A1')
    {
		// set this sheet as active
		$this->_parent->setActiveSheetIndex($this->_parent->getIndex($this));

		// set cell coordinate as active
		$this->setSelectedCells($pCellCoordinate);

		return $this->_parent->getCellXfSupervisor();
    }

	/**
	 * Get conditional styles for a cell
	 *
	 * @param string $pCoordinate
	 * @return Style_Conditional[]
	 */
	public function getConditionalStyles($pCoordinate = 'A1')
	{
		if (!isset($this->_conditionalStylesCollection[$pCoordinate])) {
			$this->_conditionalStylesCollection[$pCoordinate] = array();
		}
		return $this->_conditionalStylesCollection[$pCoordinate];
	}

	/**
	 * Do conditional styles exist for this cell?
	 *
	 * @param string $pCoordinate
	 * @return boolean
	 */
	public function conditionalStylesExists($pCoordinate = 'A1')
	{
		if (isset($this->_conditionalStylesCollection[$pCoordinate])) {
			return true;
		}
		return false;
	}

	/**
	 * Removes conditional styles for a cell
	 *
	 * @param string $pCoordinate
	 * @return Worksheet
	 */
	public function removeConditionalStyles($pCoordinate = 'A1')
	{
		unset($this->_conditionalStylesCollection[$pCoordinate]);
		return $this;
	}

	/**
	 * Get collection of conditional styles
	 *
	 * @return array
	 */
	public function getConditionalStylesCollection()
	{
		return $this->_conditionalStylesCollection;
	}

	/**
	 * Set conditional styles
	 *
	 * @param $pCoordinate string E.g. 'A1'
	 * @param $pValue Style_Conditional[]
	 * @return Worksheet
	 */
	public function setConditionalStyles($pCoordinate = 'A1', $pValue)
	{
		$this->_conditionalStylesCollection[$pCoordinate] = $pValue;
		return $this;
	}

    /**
     * Get style for cell by using numeric cell coordinates
     *
     * @param 	int $pColumn	Numeric column coordinate of the cell
     * @param 	int $pRow		Numeric row coordinate of the cell
     * @return 	Style
     */
    public function getStyleByColumnAndRow($pColumn = 0, $pRow = 0)
    {
    	return $this->getStyle(Cell::stringFromColumnIndex($pColumn) . $pRow);
    }

    /**
     * Set shared cell style to a range of cells
     *
     * Please note that this will overwrite existing cell styles for cells in range!
     *
     * @deprecated
     * @param 	Style	$pSharedCellStyle	Cell style to share
     * @param 	string			$pRange				Range of cells (i.e. "A1:B10"), or just one cell (i.e. "A1")
     * @throws	Exception
     * @return Worksheet
     */
     public function setSharedStyle(Style $pSharedCellStyle = null, $pRange = '')
    {
		$this->duplicateStyle($pSharedCellStyle, $pRange);
		return $this;
    }

    /**
     * Duplicate cell style to a range of cells
     *
     * Please note that this will overwrite existing cell styles for cells in range!
     *
     * @param 	Style	$pCellStyle	Cell style to duplicate
     * @param 	string			$pRange		Range of cells (i.e. "A1:B10"), or just one cell (i.e. "A1")
     * @throws	Exception
     * @return Worksheet
     */
    public function duplicateStyle(Style $pCellStyle = null, $pRange = '')
    {
    	// make sure we have a real style and not supervisor
		$style = $pCellStyle->getIsSupervisor() ? $pCellStyle->getSharedComponent() : $pCellStyle;

		// Add the style to the workbook if necessary
		$workbook = $this->_parent;
		if ($existingStyle = $this->_parent->getCellXfByHashCode($pCellStyle->getHashCode())) {
			// there is already such cell Xf in our collection
			$xfIndex = $existingStyle->getIndex();
		} else {
			// we don't have such a cell Xf, need to add
			$workbook->addCellXf($pCellStyle);
			$xfIndex = $pCellStyle->getIndex();
		}

		// Uppercase coordinate
    	$pRange = strtoupper($pRange);

   		// Is it a cell range or a single cell?
   		$rangeA 	= '';
   		$rangeB 	= '';
   		if (strpos($pRange, ':') === false) {
   			$rangeA = $pRange;
   			$rangeB = $pRange;
   		} else {
   			list($rangeA, $rangeB) = explode(':', $pRange);
   		}

   		// Calculate range outer borders
   		$rangeStart = Cell::coordinateFromString($rangeA);
   		$rangeEnd 	= Cell::coordinateFromString($rangeB);

   		// Translate column into index
   		$rangeStart[0]	= Cell::columnIndexFromString($rangeStart[0]) - 1;
   		$rangeEnd[0]	= Cell::columnIndexFromString($rangeEnd[0]) - 1;

   		// Make sure we can loop upwards on rows and columns
   		if ($rangeStart[0] > $rangeEnd[0] && $rangeStart[1] > $rangeEnd[1]) {
   			$tmp = $rangeStart;
   			$rangeStart = $rangeEnd;
   			$rangeEnd = $tmp;
   		}

   		// Loop through cells and apply styles
   		for ($col = $rangeStart[0]; $col <= $rangeEnd[0]; ++$col) {
   			for ($row = $rangeStart[1]; $row <= $rangeEnd[1]; ++$row) {
   				$this->getCell(Cell::stringFromColumnIndex($col) . $row)->setXfIndex($xfIndex);
   			}
   		}

   		return $this;
    }

    /**
     * Duplicate cell style array to a range of cells
     *
     * Please note that this will overwrite existing cell styles for cells in range,
     * if they are in the styles array. For example, if you decide to set a range of
     * cells to font bold, only include font bold in the styles array.
     *
     * @deprecated
     * @param	array			$pStyles	Array containing style information
     * @param 	string			$pRange		Range of cells (i.e. "A1:B10"), or just one cell (i.e. "A1")
     * @param 	boolean			$pAdvanced	Advanced mode for setting borders.
     * @throws	Exception
     * @return Worksheet
     */
    public function duplicateStyleArray($pStyles = null, $pRange = '', $pAdvanced = true)
    {
		$this->getStyle($pRange)->applyFromArray($pStyles, $pAdvanced);
    	return $this;
    }

    /**
     * Set break on a cell
     *
     * @param 	string			$pCell		Cell coordinate (e.g. A1)
     * @param 	int				$pBreak		Break type (type of Worksheet::BREAK_*)
     * @throws	Exception
     * @return Worksheet
     */
    public function setBreak($pCell = 'A1', $pBreak = Worksheet::BREAK_NONE)
    {
    	// Uppercase coordinate
    	$pCell = strtoupper($pCell);

    	if ($pCell != '') {
    		$this->_breaks[$pCell] = $pBreak;
    	} else {
    		throw new Exception('No cell coordinate specified.');
    	}

    	return $this;
    }

    /**
     * Set break on a cell by using numeric cell coordinates
     *
     * @param 	int 	$pColumn	Numeric column coordinate of the cell
     * @param 	int 	$pRow		Numeric row coordinate of the cell
     * @param 	int		$pBreak		Break type (type of Worksheet::BREAK_*)
     * @throws	Exception
     * @return Worksheet
     */
    public function setBreakByColumnAndRow($pColumn = 0, $pRow = 0, $pBreak = Worksheet::BREAK_NONE)
    {
    	return $this->setBreak(Cell::stringFromColumnIndex($pColumn) . $pRow, $pBreak);
    }

    /**
     * Get breaks
     *
     * @return array[]
     */
    public function getBreaks()
    {
    	return $this->_breaks;
    }

    /**
     * Set merge on a cell range
     *
     * @param 	string			$pRange		Cell range (e.g. A1:E1)
     * @throws	Exception
     * @return Worksheet
     */
    public function mergeCells($pRange = 'A1:A1')
    {
    	// Uppercase coordinate
    	$pRange = strtoupper($pRange);

    	if (strpos($pRange,':') !== false) {
    		$this->_mergeCells[$pRange] = $pRange;

			// make sure cells are created

			// get the cells in the range
			$aReferences = Cell::extractAllCellReferencesInRange($pRange);

			// create upper left cell if it does not already exist
			$upperLeft = $aReferences[0];
			if (!$this->cellExists($upperLeft)) {
				$this->getCell($upperLeft)->setValueExplicit(null, Cell_DataType::TYPE_NULL);
			}

			// create or blank out the rest of the cells in the range
			$count = count($aReferences);
			for ($i = 1; $i < $count; $i++) {
				$this->getCell($aReferences[$i])->setValueExplicit(null, Cell_DataType::TYPE_NULL);
			}

    	} else {
    		throw new Exception('Merge must be set on a range of cells.');
    	}

    	return $this;
    }

    /**
     * Set merge on a cell range by using numeric cell coordinates
     *
     * @param 	int $pColumn1	Numeric column coordinate of the first cell
     * @param 	int $pRow1		Numeric row coordinate of the first cell
     * @param 	int $pColumn2	Numeric column coordinate of the last cell
     * @param 	int $pRow2		Numeric row coordinate of the last cell
     * @throws	Exception
     * @return Worksheet
     */
    public function mergeCellsByColumnAndRow($pColumn1 = 0, $pRow1 = 0, $pColumn2 = 0, $pRow2 = 0)
    {
    	$cellRange = Cell::stringFromColumnIndex($pColumn1) . $pRow1 . ':' . Cell::stringFromColumnIndex($pColumn2) . $pRow2;
    	return $this->mergeCells($cellRange);
    }

    /**
     * Remove merge on a cell range
     *
     * @param 	string			$pRange		Cell range (e.g. A1:E1)
     * @throws	Exception
     * @return Worksheet
     */
    public function unmergeCells($pRange = 'A1:A1')
    {
    	// Uppercase coordinate
    	$pRange = strtoupper($pRange);

    	if (strpos($pRange,':') !== false) {
    		if (isset($this->_mergeCells[$pRange])) {
    			unset($this->_mergeCells[$pRange]);
    		} else {
    			throw new Exception('Cell range ' . $pRange . ' not known as merged.');
    		}
    	} else {
    		throw new Exception('Merge can only be removed from a range of cells.');
    	}

    	return $this;
    }

    /**
     * Remove merge on a cell range by using numeric cell coordinates
     *
     * @param 	int $pColumn1	Numeric column coordinate of the first cell
     * @param 	int $pRow1		Numeric row coordinate of the first cell
     * @param 	int $pColumn2	Numeric column coordinate of the last cell
     * @param 	int $pRow2		Numeric row coordinate of the last cell
     * @throws	Exception
     * @return Worksheet
     */
    public function unmergeCellsByColumnAndRow($pColumn1 = 0, $pRow1 = 0, $pColumn2 = 0, $pRow2 = 0)
    {
    	$cellRange = Cell::stringFromColumnIndex($pColumn1) . $pRow1 . ':' . Cell::stringFromColumnIndex($pColumn2) . $pRow2;
    	return $this->unmergeCells($cellRange);
    }

    /**
     * Get merge cells array.
     *
     * @return array[]
     */
    public function getMergeCells()
    {
    	return $this->_mergeCells;
    }

	/**
	 * Set merge cells array for the entire sheet. Use instead mergeCells() to merge
	 * a single cell range.
	 *
	 * @param array
	 */
	public function setMergeCells($pValue = array())
	{
		$this->_mergeCells = $pValue;

		return $this;
	}

    /**
     * Set protection on a cell range
     *
     * @param 	string			$pRange				Cell (e.g. A1) or cell range (e.g. A1:E1)
     * @param 	string			$pPassword			Password to unlock the protection
     * @param 	boolean 		$pAlreadyHashed 	If the password has already been hashed, set this to true
     * @throws	Exception
     * @return Worksheet
     */
    public function protectCells($pRange = 'A1', $pPassword = '', $pAlreadyHashed = false)
    {
    	// Uppercase coordinate
    	$pRange = strtoupper($pRange);

    	if (!$pAlreadyHashed) {
    		$pPassword = Shared_PasswordHasher::hashPassword($pPassword);
    	}
    	$this->_protectedCells[$pRange] = $pPassword;

    	return $this;
    }

    /**
     * Set protection on a cell range by using numeric cell coordinates
     *
     * @param 	int 	$pColumn1			Numeric column coordinate of the first cell
     * @param 	int 	$pRow1				Numeric row coordinate of the first cell
     * @param 	int 	$pColumn2			Numeric column coordinate of the last cell
     * @param 	int 	$pRow2				Numeric row coordinate of the last cell
     * @param 	string	$pPassword			Password to unlock the protection
     * @param 	boolean $pAlreadyHashed 	If the password has already been hashed, set this to true
     * @throws	Exception
     * @return Worksheet
     */
    public function protectCellsByColumnAndRow($pColumn1 = 0, $pRow1 = 0, $pColumn2 = 0, $pRow2 = 0, $pPassword = '', $pAlreadyHashed = false)
    {
    	$cellRange = Cell::stringFromColumnIndex($pColumn1) . $pRow1 . ':' . Cell::stringFromColumnIndex($pColumn2) . $pRow2;
    	return $this->protectCells($cellRange, $pPassword, $pAlreadyHashed);
    }

    /**
     * Remove protection on a cell range
     *
     * @param 	string			$pRange		Cell (e.g. A1) or cell range (e.g. A1:E1)
     * @throws	Exception
     * @return Worksheet
     */
    public function unprotectCells($pRange = 'A1')
    {
    	// Uppercase coordinate
    	$pRange = strtoupper($pRange);

    	if (isset($this->_protectedCells[$pRange])) {
    		unset($this->_protectedCells[$pRange]);
    	} else {
    		throw new Exception('Cell range ' . $pRange . ' not known as protected.');
    	}
    	return $this;
    }

    /**
     * Remove protection on a cell range by using numeric cell coordinates
     *
     * @param 	int 	$pColumn1			Numeric column coordinate of the first cell
     * @param 	int 	$pRow1				Numeric row coordinate of the first cell
     * @param 	int 	$pColumn2			Numeric column coordinate of the last cell
     * @param 	int 	$pRow2				Numeric row coordinate of the last cell
     * @param 	string	$pPassword			Password to unlock the protection
     * @param 	boolean $pAlreadyHashed 	If the password has already been hashed, set this to true
     * @throws	Exception
     * @return Worksheet
     */
    public function unprotectCellsByColumnAndRow($pColumn1 = 0, $pRow1 = 0, $pColumn2 = 0, $pRow2 = 0, $pPassword = '', $pAlreadyHashed = false)
    {
    	$cellRange = Cell::stringFromColumnIndex($pColumn1) . $pRow1 . ':' . Cell::stringFromColumnIndex($pColumn2) . $pRow2;
    	return $this->unprotectCells($cellRange, $pPassword, $pAlreadyHashed);
    }

    /**
     * Get protected cells
     *
     * @return array[]
     */
    public function getProtectedCells()
    {
    	return $this->_protectedCells;
    }

    /**
     * Get Autofilter Range
     *
     * @return string
     */
    public function getAutoFilter()
    {
    	return $this->_autoFilter;
    }

    /**
     * Set Autofilter Range
     *
     * @param 	string		$pRange		Cell range (i.e. A1:E10)
     * @throws 	Exception
     * @return Worksheet
     */
    public function setAutoFilter($pRange = '')
    {
    	// Uppercase coordinate
    	$pRange = strtoupper($pRange);

    	if (strpos($pRange,':') !== false) {
    		$this->_autoFilter = $pRange;
    	} else {
    		throw new Exception('Autofilter must be set on a range of cells.');
    	}
    	return $this;
    }

    /**
     * Set Autofilter Range by using numeric cell coordinates
     *
     * @param 	int 	$pColumn1	Numeric column coordinate of the first cell
     * @param 	int 	$pRow1		Numeric row coordinate of the first cell
     * @param 	int 	$pColumn2	Numeric column coordinate of the second cell
     * @param 	int 	$pRow2		Numeric row coordinate of the second cell
     * @throws 	Exception
     * @return Worksheet
     */
    public function setAutoFilterByColumnAndRow($pColumn1 = 0, $pRow1 = 0, $pColumn2 = 0, $pRow2 = 0)
    {
    	return $this->setAutoFilter(
    		Cell::stringFromColumnIndex($pColumn1) . $pRow1
    		. ':' .
    		Cell::stringFromColumnIndex($pColumn2) . $pRow2
    	);
    }

    /**
     * Get Freeze Pane
     *
     * @return string
     */
    public function getFreezePane()
    {
    	return $this->_freezePane;
    }

    /**
     * Freeze Pane
     *
     * @param 	string		$pCell		Cell (i.e. A1)
     * @throws 	Exception
     * @return Worksheet
     */
    public function freezePane($pCell = '')
    {
    	// Uppercase coordinate
    	$pCell = strtoupper($pCell);

    	if (strpos($pCell,':') === false && strpos($pCell,',') === false) {
    		$this->_freezePane = $pCell;
    	} else {
    		throw new Exception('Freeze pane can not be set on a range of cells.');
    	}
    	return $this;
    }

    /**
     * Freeze Pane by using numeric cell coordinates
     *
     * @param 	int 	$pColumn	Numeric column coordinate of the cell
     * @param 	int 	$pRow		Numeric row coordinate of the cell
     * @throws 	Exception
     * @return Worksheet
     */
    public function freezePaneByColumnAndRow($pColumn = 0, $pRow = 0)
    {
    	return $this->freezePane(Cell::stringFromColumnIndex($pColumn) . $pRow);
    }

    /**
     * Unfreeze Pane
     *
     * @return Worksheet
     */
    public function unfreezePane()
    {
    	return $this->freezePane('');
    }

    /**
     * Insert a new row, updating all possible related data
     *
     * @param 	int	$pBefore	Insert before this one
     * @param 	int	$pNumRows	Number of rows to insert
     * @throws 	Exception
     * @return Worksheet
     */
    public function insertNewRowBefore($pBefore = 1, $pNumRows = 1) {
    	if ($pBefore >= 1) {
    		$objReferenceHelper = ReferenceHelper::getInstance();
    		$objReferenceHelper->insertNewBefore('A' . $pBefore, 0, $pNumRows, $this);
    	} else {
    		throw new Exception("Rows can only be inserted before at least row 1.");
    	}
    	return $this;
    }

    /**
     * Insert a new column, updating all possible related data
     *
     * @param 	int	$pBefore	Insert before this one
     * @param 	int	$pNumCols	Number of columns to insert
     * @throws 	Exception
     * @return Worksheet
     */
    public function insertNewColumnBefore($pBefore = 'A', $pNumCols = 1) {
    	if (!is_numeric($pBefore)) {
    		$objReferenceHelper = ReferenceHelper::getInstance();
    		$objReferenceHelper->insertNewBefore($pBefore . '1', $pNumCols, 0, $this);
    	} else {
    		throw new Exception("Column references should not be numeric.");
    	}
    	return $this;
    }

    /**
     * Insert a new column, updating all possible related data
     *
     * @param 	int	$pBefore	Insert before this one (numeric column coordinate of the cell)
     * @param 	int	$pNumCols	Number of columns to insert
     * @throws 	Exception
     * @return Worksheet
     */
    public function insertNewColumnBeforeByIndex($pBefore = 0, $pNumCols = 1) {
    	if ($pBefore >= 0) {
    		return $this->insertNewColumnBefore(Cell::stringFromColumnIndex($pBefore), $pNumCols);
    	} else {
    		throw new Exception("Columns can only be inserted before at least column A (0).");
    	}
    }

    /**
     * Delete a row, updating all possible related data
     *
     * @param 	int	$pRow		Remove starting with this one
     * @param 	int	$pNumRows	Number of rows to remove
     * @throws 	Exception
     * @return Worksheet
     */
    public function removeRow($pRow = 1, $pNumRows = 1) {
    	if ($pRow >= 1) {
    		$objReferenceHelper = ReferenceHelper::getInstance();
    		$objReferenceHelper->insertNewBefore('A' . ($pRow + $pNumRows), 0, -$pNumRows, $this);
    	} else {
    		throw new Exception("Rows to be deleted should at least start from row 1.");
    	}
    	return $this;
    }

    /**
     * Remove a column, updating all possible related data
     *
     * @param 	int	$pColumn	Remove starting with this one
     * @param 	int	$pNumCols	Number of columns to remove
     * @throws 	Exception
     * @return Worksheet
     */
    public function removeColumn($pColumn = 'A', $pNumCols = 1) {
    	if (!is_numeric($pColumn)) {
    		$pColumn = Cell::stringFromColumnIndex(Cell::columnIndexFromString($pColumn) - 1 + $pNumCols);
    		$objReferenceHelper = ReferenceHelper::getInstance();
    		$objReferenceHelper->insertNewBefore($pColumn . '1', -$pNumCols, 0, $this);
    	} else {
    		throw new Exception("Column references should not be numeric.");
    	}
    	return $this;
    }

    /**
     * Remove a column, updating all possible related data
     *
     * @param 	int	$pColumn	Remove starting with this one (numeric column coordinate of the cell)
     * @param 	int	$pNumCols	Number of columns to remove
     * @throws 	Exception
     * @return Worksheet
     */
    public function removeColumnByIndex($pColumn = 0, $pNumCols = 1) {
    	if ($pColumn >= 0) {
    		return $this->removeColumn(Cell::stringFromColumnIndex($pColumn), $pNumCols);
    	} else {
    		throw new Exception("Columns can only be inserted before at least column A (0).");
    	}
    }

    /**
     * Show gridlines?
     *
     * @return boolean
     */
    public function getShowGridlines() {
    	return $this->_showGridlines;
    }

    /**
     * Set show gridlines
     *
     * @param boolean $pValue	Show gridlines (true/false)
     * @return Worksheet
     */
    public function setShowGridlines($pValue = false) {
    	$this->_showGridlines = $pValue;
    	return $this;
    }

	/**
	* Print gridlines?
	*
	* @return boolean
	*/
	public function getPrintGridlines() {
		return $this->_printGridlines;
	}

	/**
	* Set print gridlines
	*
	* @param boolean $pValue Print gridlines (true/false)
	* @return Worksheet
	*/
	public function setPrintGridlines($pValue = false) {
		$this->_printGridlines = $pValue;
		return $this;
	}

	/**
	* Show row and column headers?
	*
	* @return boolean
	*/
	public function getShowRowColHeaders() {
		return $this->_showRowColHeaders;
	}

	/**
	* Set show row and column headers
	*
	* @param boolean $pValue Show row and column headers (true/false)
	* @return Worksheet
	*/
	public function setShowRowColHeaders($pValue = false) {
		$this->_showRowColHeaders = $pValue;
		return $this;
	}

    /**
     * Show summary below? (Row/Column outlining)
     *
     * @return boolean
     */
    public function getShowSummaryBelow() {
    	return $this->_showSummaryBelow;
    }

    /**
     * Set show summary below
     *
     * @param boolean $pValue	Show summary below (true/false)
     * @return Worksheet
     */
    public function setShowSummaryBelow($pValue = true) {
    	$this->_showSummaryBelow = $pValue;
    	return $this;
    }

    /**
     * Show summary right? (Row/Column outlining)
     *
     * @return boolean
     */
    public function getShowSummaryRight() {
    	return $this->_showSummaryRight;
    }

    /**
     * Set show summary right
     *
     * @param boolean $pValue	Show summary right (true/false)
     * @return Worksheet
     */
    public function setShowSummaryRight($pValue = true) {
    	$this->_showSummaryRight = $pValue;
    	return $this;
    }

    /**
     * Get comments
     *
     * @return Comment[]
     */
    public function getComments()
    {
    	return $this->_comments;
    }

    /**
     * Get comment for cell
     *
     * @param 	string 	$pCellCoordinate	Cell coordinate to get comment for
     * @return 	Comment
     * @throws 	Exception
     */
    public function getComment($pCellCoordinate = 'A1')
    {
    	// Uppercase coordinate
    	$pCellCoordinate = strtoupper($pCellCoordinate);

    	if (strpos($pCellCoordinate,':') !== false || strpos($pCellCoordinate,',') !== false) {
    		throw new Exception('Cell coordinate string can not be a range of cells.');
    	} else if (strpos($pCellCoordinate,'$') !== false) {
    		throw new Exception('Cell coordinate string must not be absolute.');
    	} else if ($pCellCoordinate == '') {
    		throw new Exception('Cell coordinate can not be zero-length string.');
    	} else {
    		// Check if we already have a comment for this cell.
    		// If not, create a new comment.
    		if (isset($this->_comments[$pCellCoordinate])) {
    			return $this->_comments[$pCellCoordinate];
    		} else {
    			$newComment = new Comment();
    			$this->_comments[$pCellCoordinate] = $newComment;
    			return $newComment;
    		}
    	}
    }

    /**
     * Get comment for cell by using numeric cell coordinates
     *
     * @param 	int $pColumn	Numeric column coordinate of the cell
     * @param 	int $pRow		Numeric row coordinate of the cell
     * @return 	Comment
     */
    public function getCommentByColumnAndRow($pColumn = 0, $pRow = 0)
    {
    	return $this->getComment(Cell::stringFromColumnIndex($pColumn) . $pRow);
    }

    /**
     * Get selected cell
     *
     * @deprecated
     * @return string
     */
    public function getSelectedCell()
    {
    	return $this->getSelectedCells();
    }

    /**
     * Get active cell
     *
     * @return string Example: 'A1'
     */
    public function getActiveCell()
    {
    	return $this->_activeCell;
    }

    /**
     * Get selected cells
     *
     * @return string
     */
    public function getSelectedCells()
    {
    	return $this->_selectedCells;
    }

    /**
     * Selected cell
     *
     * @param 	string		$pCell		Cell (i.e. A1)
     * @return Worksheet
     */
    public function setSelectedCell($pCoordinate = 'A1')
    {
    	return $this->setSelectedCells($pCoordinate);
    }

    /**
     * Select a range of cells.
     *
     * @param 	string		$pCoordinate	Cell range, examples: 'A1', 'B2:G5', 'A:C', '3:6'
     * @throws 	Exception
     * @return Worksheet
     */
    public function setSelectedCells($pCoordinate = 'A1')
    {
		// Uppercase coordinate
    	$pCoordinate = strtoupper($pCoordinate);

		// Convert 'A' to 'A:A'
		$pCoordinate = preg_replace('/^([A-Z]+)$/', '${1}:${1}', $pCoordinate);

		// Convert '1' to '1:1'
		$pCoordinate = preg_replace('/^([0-9]+)$/', '${1}:${1}', $pCoordinate);

		// Convert 'A:C' to 'A1:C1048576'
		$pCoordinate = preg_replace('/^([A-Z]+):([A-Z]+)$/', '${1}1:${2}1048576', $pCoordinate);

    	// Convert '1:3' to 'A1:XFD3'
		$pCoordinate = preg_replace('/^([0-9]+):([0-9]+)$/', 'A${1}:XFD${2}', $pCoordinate);

    	if (strpos($pCoordinate,':') !== false || strpos($pCoordinate,',') !== false) {
			list($first, ) = Cell::splitRange($pCoordinate);
			$this->_activeCell = $first[0];
		} else {
			$this->_activeCell = $pCoordinate;
		}
		$this->_selectedCells = $pCoordinate;
    	return $this;
    }

    /**
     * Selected cell by using numeric cell coordinates
     *
     * @param 	int 	$pColumn	Numeric column coordinate of the cell
     * @param 	int 	$pRow		Numeric row coordinate of the cell
     * @throws 	Exception
     * @return Worksheet
     */
    public function setSelectedCellByColumnAndRow($pColumn = 0, $pRow = 0)
    {
    	return $this->setSelectedCells(Cell::stringFromColumnIndex($pColumn) . $pRow);
    }

    /**
	 * Get right-to-left
	 *
	 * @return boolean
     */
    public function getRightToLeft() {
    	return $this->_rightToLeft;
    }

    /**
     * Set right-to-left
     *
     * @param boolean $value Right-to-left true/false
     * @return Worksheet
     */
    public function setRightToLeft($value = false) {
    	$this->_rightToLeft = $value;
    	return $this;
    }

    /**
     * Fill worksheet from values in array
     *
     * @param array $source	Source array
     * @param mixed $nullValue Value in source array that stands for blank cell
     * @throws Exception
     * @return Worksheet
     */
    public function fromArray($source = null, $nullValue = null, $pCell = 'A1') {
    	if (is_array($source)) {
			// start coordinate
			list ($startColumn, $startRow) = Cell::coordinateFromString($pCell);
			$startColumn = Cell::columnIndexFromString($startColumn) - 1;

			// Loop through $source
			$currentRow = $startRow - 1;
			$rowData = null;
			foreach ($source as $rowData) {
				++$currentRow;

				$rowCount = count($rowData);
				for ($i = 0; $i < $rowCount; ++$i) {
					if ($rowData[$i] != $nullValue) {
						// Set cell value
						$this->getCell(Cell::stringFromColumnIndex($i + $startColumn) . $currentRow)
							->setValue($rowData[$i]);
					}
				}
			}
    	} else {
    		throw new Exception("Parameter \$source should be an array.");
    	}
    	return $this;
    }

    /**
     * Create array from worksheet
     *
     * @param mixed $nullValue Value treated as "null"
     * @param boolean $calculateFormulas Should formulas be calculated?
     * @return array
     */
    public function toArray($nullValue = null, $calculateFormulas = true) {
    	// Returnvalue
    	$returnValue = array();

        // Garbage collect...
        $this->garbageCollect();

    	// Get worksheet dimension
    	$dimension = explode(':', $this->calculateWorksheetDimension());
    	$dimension[0] = Cell::coordinateFromString($dimension[0]);
    	$dimension[0][0] = Cell::columnIndexFromString($dimension[0][0]) - 1;
    	$dimension[1] = Cell::coordinateFromString($dimension[1]);
    	$dimension[1][0] = Cell::columnIndexFromString($dimension[1][0]) - 1;

    	// Loop through cells
    	for ($row = $dimension[0][1]; $row <= $dimension[1][1]; ++$row) {
    		for ($column = $dimension[0][0]; $column <= $dimension[1][0]; ++$column) {
    			// Cell exists?
    			if ($this->cellExistsByColumnAndRow($column, $row)) {
    				$cell = $this->getCellByColumnAndRow($column, $row);

    				if ($cell->getValue() instanceof RichText) {
    					$returnValue[$row][$column] = $cell->getValue()->getPlainText();
    				} else {
	    				if ($calculateFormulas) {
	    					$returnValue[$row][$column] = $cell->getCalculatedValue();
	    				} else {
	    					$returnValue[$row][$column] = $cell->getValue();
	    				}
    				}

					$style = $this->_parent->getCellXfByIndex($cell->getXfIndex());

    				$returnValue[$row][$column] = Style_NumberFormat::toFormattedString($returnValue[$row][$column], $style->getNumberFormat()->getFormatCode());
    			} else {
    				$returnValue[$row][$column] = $nullValue;
    			}
    		}
    	}

    	// Return
    	return $returnValue;
    }

	/**
	 * Get row iterator
	 *
	 * @return Worksheet_RowIterator
	 */
	public function getRowIterator() {
		return new Worksheet_RowIterator($this);
	}

    /**
     * Run PHPExcel garabage collector.
     *
     * @return Worksheet
     */
    public function garbageCollect() {
    	// Build a reference table from images
    	$imageCoordinates = array();
  		$iterator = $this->getDrawingCollection()->getIterator();
   		while ($iterator->valid()) {
   			$imageCoordinates[$iterator->current()->getCoordinates()] = true;

   			$iterator->next();
   		}

		// Lookup highest column and highest row if cells are cleaned
		$highestColumn = -1;
		$highestRow    = 1;

    	// Find cells that can be cleaned
    	foreach ($this->_cellCollection->getCellList() as $coordinate) {
			preg_match('/^(\w+)(\d+)$/U',$coordinate,$matches);
			list(,$col,$row) = $matches;
			$column = Cell::columnIndexFromString($col);

			// Determine highest column and row
			if ($highestColumn < $column) {
				$highestColumn = $column;
			}
			if ($row > $highestRow) {
				$highestRow = $row;
			}
    	}

        // Loop through column dimensions
        foreach ($this->_columnDimensions as $dimension) {
        	if ($highestColumn < Cell::columnIndexFromString($dimension->getColumnIndex())) {
        		$highestColumn = Cell::columnIndexFromString($dimension->getColumnIndex());
        	}
        }

        // Loop through row dimensions
        foreach ($this->_rowDimensions as $dimension) {
        	if ($highestRow < $dimension->getRowIndex()) {
        		$highestRow = $dimension->getRowIndex();
        	}
        }

		// Cache values
		if ($highestColumn < 0) {
			$this->_cachedHighestColumn = 'A';
		} else {
			$this->_cachedHighestColumn = Cell::stringFromColumnIndex(--$highestColumn);
		}
		$this->_cachedHighestRow = $highestRow;

		// Return
    	return $this;
    }

	/**
	 * Get hash code
	 *
	 * @return string	Hash code
	 */
	public function getHashCode() {
    	return md5(
    		  $this->_title
    		. $this->_autoFilter
    		. ($this->_protection->isProtectionEnabled() ? 't' : 'f')
    		//. $this->calculateWorksheetDimension()
    		. __CLASS__
    	);
    }

    /**
     * Extract worksheet title from range.
     *
     * Example: extractSheetTitle('test!A1') ==> 'A1'
     * Example: extractSheetTitle('test!A1', true) ==> array('test', 'A1');
     *
     * @param string $pRange	Range to extract title from
     * @param bool $returnRange	Return range? (see example)
     * @return mixed
     */
    public static function extractSheetTitle($pRange, $returnRange = false) {
    	// Sheet title included?
    	if (strpos($pRange, '!') === false) {
    		return '';
    	}

    	// Position of separator exclamation mark
		$sep = strrpos($pRange, '!');

		// Extract sheet title
		$reference[0] = substr($pRange, 0, $sep);
		$reference[1] = substr($pRange, $sep + 1);

    	// Strip possible enclosing single quotes
    	if (strpos($reference[0], '\'') === 0) {
    		$reference[0] = substr($reference[0], 1);
    	}
    	if (strrpos($reference[0], '\'') === strlen($reference[0]) - 1) {
    		$reference[0] = substr($reference[0], 0, strlen($reference[0]) - 1);
    	}

    	if ($returnRange) {
    		return $reference;
    	} else {
    		return $reference[1];
    	}
    }

	/**
	 * Get hyperlink
	 *
	 * @param string $pCellCoordinate	Cell coordinate to get hyperlink for
	 */
	public function getHyperlink($pCellCoordinate = 'A1')
	{
		// return hyperlink if we already have one
		if (isset($this->_hyperlinkCollection[$pCellCoordinate])) {
			return $this->_hyperlinkCollection[$pCellCoordinate];
		}

		// else create hyperlink
		$this->_hyperlinkCollection[$pCellCoordinate] = new Cell_Hyperlink();
		return $this->_hyperlinkCollection[$pCellCoordinate];
	}

	/**
	 * Set hyperlnk
	 *
	 * @param string $pCellCoordinate	Cell coordinate to insert hyperlink
	 * @param 	Cell_Hyperlink	$pHyperlink
	 * @return Worksheet
	 */
	public function setHyperlink($pCellCoordinate = 'A1', Cell_Hyperlink $pHyperlink = null)
	{
		if ($pHyperlink === null) {
			unset($this->_hyperlinkCollection[$pCellCoordinate]);
		} else {
			$this->_hyperlinkCollection[$pCellCoordinate] = $pHyperlink;
		}
		return $this;
	}

	/**
	 * Hyperlink at a specific coordinate exists?
	 *
	 * @param string $pCellCoordinate
	 * @return boolean
	 */
	public function hyperlinkExists($pCoordinate = 'A1')
	{
		return isset($this->_hyperlinkCollection[$pCoordinate]);
	}

	/**
	 * Get collection of hyperlinks
	 *
	 * @return Cell_Hyperlink[]
	 */
	public function getHyperlinkCollection()
	{
		return $this->_hyperlinkCollection;
	}

	/**
	 * Get data validation
	 *
	 * @param string $pCellCoordinate	Cell coordinate to get data validation for
	 */
	public function getDataValidation($pCellCoordinate = 'A1')
	{
		// return data validation if we already have one
		if (isset($this->_dataValidationCollection[$pCellCoordinate])) {
			return $this->_dataValidationCollection[$pCellCoordinate];
		}

		// else create data validation
		$this->_dataValidationCollection[$pCellCoordinate] = new Cell_DataValidation();
		return $this->_dataValidationCollection[$pCellCoordinate];
	}

	/**
	 * Set data validation
	 *
	 * @param string $pCellCoordinate	Cell coordinate to insert data validation
	 * @param 	Cell_DataValidation	$pDataValidation
	 * @return Worksheet
	 */
	public function setDataValidation($pCellCoordinate = 'A1', Cell_DataValidation $pDataValidation = null)
	{
		if ($pDataValidation === null) {
			unset($this->_dataValidationCollection[$pCellCoordinate]);
		} else {
			$this->_dataValidationCollection[$pCellCoordinate] = $pDataValidation;
		}
		return $this;
	}

	/**
	 * Data validation at a specific coordinate exists?
	 *
	 * @param string $pCellCoordinate
	 * @return boolean
	 */
	public function dataValidationExists($pCoordinate = 'A1')
	{
		return isset($this->_dataValidationCollection[$pCoordinate]);
	}

	/**
	 * Get collection of data validations
	 *
	 * @return Cell_DataValidation[]
	 */
	public function getDataValidationCollection()
	{
		return $this->_dataValidationCollection;
	}

	/**
	 * Accepts a range, returning it as a range that falls within the current highest row and column of the worksheet
	 *
	 * @param 	string 	$range
	 * @return 	string	Adjusted range value
	 */
	public function shrinkRangeToFit($range) {
		$maxCol = $this->getHighestColumn();
		$maxRow = $this->getHighestRow();
		$maxCol = Cell::columnIndexFromString($maxCol);

		$rangeBlocks = explode(' ',$range);
		foreach ($rangeBlocks as &$rangeSet) {
			$rangeBoundaries = Cell::getRangeBoundaries($rangeSet);

			if (Cell::columnIndexFromString($rangeBoundaries[0][0]) > $maxCol) { $rangeBoundaries[0][0] = Cell::stringFromColumnIndex($maxCol); }
			if ($rangeBoundaries[0][1] > $maxRow) { $rangeBoundaries[0][1] = $maxRow; }
			if (Cell::columnIndexFromString($rangeBoundaries[1][0]) > $maxCol) { $rangeBoundaries[1][0] = Cell::stringFromColumnIndex($maxCol); }
			if ($rangeBoundaries[1][1] > $maxRow) { $rangeBoundaries[1][1] = $maxRow; }
			$rangeSet = $rangeBoundaries[0][0].$rangeBoundaries[0][1].':'.$rangeBoundaries[1][0].$rangeBoundaries[1][1];
		}
		unset($rangeSet);
		$stRange = implode(' ',$rangeBlocks);

		return $stRange;
	}


	/**
	 * Get tab color
	 *
	 * @return Style_Color
	 */
	public function getTabColor()
	{
		if (is_null($this->_tabColor))
			$this->_tabColor = new Style_Color();

		return $this->_tabColor;
	}

	/**
	 * Reset tab color
	 *
	 * @return Worksheet
	 */
	public function resetTabColor()
	{
		$this->_tabColor = null;
		unset($this->_tabColor);

		return $this;
	}

	/**
	 * Tab color set?
	 *
	 * @return boolean
	 */
	public function isTabColorSet()
	{
		return !is_null($this->_tabColor);
	}

	/**
	 * Copy worksheet (!= clone!)
	 *
	 * @return Worksheet
	 */
	public function copy() {
		$copied = clone $this;

		return $copied;
	}

	/**
	 * Implement PHP __clone to create a deep clone, not just a shallow copy.
	 */
	public function __clone() {
		foreach ($this as $key => $val) {
			if ($key == '_parent') {
				continue;
			}

			if (is_object($val) || (is_array($val))) {
				$this->{$key} = unserialize(serialize($val));
			}
		}
	}
}

<?php
/*
 * Table Print utilities
 * Copyright (C) 2012-2013 Paul Ionkin <paul.ionkin@gmail.com>
 * Copyright (C) 2015 Pitch <pitch314@gmail.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>
 */
/**
 * @file    TPrint.php
 *
 * Class for table print, to print ASCII tabular data.
 *
 * @author  pitch314
 * @version 1.0, 2015-09-13
 */

require_once("ITPrint.php");

/**
 * TPrint print class that implement table print utilities to print ASCII tabular data.
 * @author  pitch314
 * @version 1.0, 2015-09-13
 */
class TPrint implements ITPrint {
    /** String of table print result. */
    public    $sout        = "";
    /** Contain all table data and its printing.
     *
	 * {'label', 'label_align', data_align, 'data', 'current_width', 'max_width'}<br>
	 *	label			= column name.<br>
	 *	label_align		= how to align column caption. [value = ITPrint::TPALIGN_LEFT| ITPrint::TPALIGN_CENTER| ITPrint::TPALIGN_RIGHT]<br>
	 *	data_align		= how to align data in the column. [value = ITPrint::TPALIGN_LEFT| ITPrint::TPALIGN_CENTER| ITPrint::TPALIGN_RIGHT]<br>
	 * 	data 			= table data.<br>
	 *	current_width	= current column width. If max_width is positive then current_width <= max_width.<br>
	 *	max_width		= column width limit. if negative then no limit. [Default: -1]<br>
	 */
    protected $columns     = array();   //List of columns
    /** Number of table rows. */
    protected $nb_rows     = 0;         //Number of rows
    /** Number of table columns. */
    protected $nb_columns  = 0;         //Number of columns

    /** Number of spaces on the left side of the table. [Default: 0] */
    public $spaces_left    = 0;
    /** Number of spaces between columns (better even number). [Default: 2] */
    public $spaces_between = 2;
    /** Boolean to draw or not inner and outer borders. [Default: true] */
    public $show_borders   = TRUE;
    /** boolean to display or not table header row. [Default: true] */
    public $show_header    = TRUE;
    // var $min_column_width;
    // var $max_column_width;
    /** Contain symbols of table.
     *
	 * {'vborder', 'hborder', 'space', 'space_between', 'space_left'}<br>
	 *	vborder			= define vertical border representation. [Default: "|"]<br>
	 *	hborder			= define horizontal border representation. [Default: "="]<br>
	 *	space			= define space representation around data and borders. [Default: "."]<br>
	 *	space_between	= define space representation around data in cell or around border. Work with spaces_between property. [Default: " "]<br>
	 *	space_left		= define space representation around data in cell or around border. Work with spaces_left property. [Default: " "]<br>
	 */
    protected $symbols = array('vborder' => "|", 'hborder' => "=", 'space' => ".", 'space_between' => " ", 'space_left' => " ");

/**
 * {@inheritDoc}
 */
//// TPrint *tprint_create (FILE *fout, gboolean borders, gboolean show_header, gint spaces_left, gint spaces_between);
//// struct table_print_t* table_print_create(FILE *fout, int show_borders, int show_header, int spaces_left, int spaces_between);
    public function tprint_create($show_borders, $show_header, $spaces_left, $spaces_between) {
        $this->show_borders = $show_borders;
        $this->show_header = $show_header;
        $this->spaces_left = $spaces_left;
        $this->spaces_between = $spaces_between;
        $this->nb_rows = 0;
        $this->nb_columns = 0;
        //$this->min_column_width;
        //$this->max_column_width;
    }

/**
 * Constructeur.
 * 
 * @see TPrint#tprint_create
 */
    function __construct($show_borders = TRUE, $show_header = TRUE, $spaces_left = 0, $spaces_between = 2) {
        $this->tprint_create($show_borders, $show_header, $spaces_left, $spaces_between);
    }

/**
 * {@inheritDoc}
 */
//// void tprint_free (TPrint *tprint);
//// void table_print_free(struct table_print_t *tp);
    public function tprint_free() {
        unset($columns);
    }

/**
 * Destructeur.
 * 
 * @see TPrint#ttprint_free
 */
    function __destruct() {
        $this->tprint_free();
    }


/**
 * {@inheritDoc}
 * @param   max_width   (integer) limit automatic column length to a maximum
 *                       [Default=-1] if negative else no limit
 */
//// void tprint_column_add (TPrint *tprint, const gchar *caption, TPrintAlign caption_align, TPrintAlign data_align);
//// void table_print_column_add(struct table_print_t *tp, const char *caption, enum table_print_align_t caption_align, enum table_print_align_t data_align);
    public function tprint_column_add($label, $label_align, $data_align, $max_width = -1) {
        
        $this->columns[] = array(
            'label'         => $label,
            'label_align'   => $label_align,
            'data_align'    => $data_align,
            'data'          => array(),
            'current_width' => (int)strlen($label),
            'max_width'     => $max_width,
        );
        $this->nb_columns++;
    }

/**
 * {@inheritDoc}
 */
//// void table_print_add_row(struct table_print_t *tp, const char* fmt, ...)  __attribute__ ((format (printf, 2, 3)));    
    public function tprint_row_add($data) {
        $ln_data = count($data);

        for($i=0 ; $i < $this->nb_columns ; $i++) {
            if($i < $ln_data) {
                $this->tprint_data_add($i, $data[$i]);
            } else {
                $this->tprint_data_add($i, "");
            }
        }
        // $this->nb_rows++;    //No need because managed in tprint_data_add()
        if($i < $ln_data) {
            //$data est trop long
            //->Warning all data haven't added
        }
    }

/**
 * {@inheritDoc}
 */
//// void table_print_data_add_int32(struct table_print_t *tp, int col, int data);
//// void tprint_data_add_int32 (TPrint *tprint, gint col, gint32 data);
//// void tprint_data_add_uint64 (TPrint *tprint, gint col, guint64 data);
//// void tprint_data_add_str (TPrint *tprint, gint col, const gchar *data);
//// void tprint_data_add_double (TPrint *tprint, gint col, gdouble data);
    public function tprint_data_add($num_col, $data) {
        $this->columns[$num_col]['data'][] = $data;
        if($this->columns[$num_col]['current_width'] < strlen($data)) {
            $this->columns[$num_col]['current_width'] = strlen($data);
        }
        if(count($this->columns[$num_col]['data']) > $this->nb_rows) {
            $this->nb_rows = count($this->columns[$num_col]['data']);
            //Add something to other column for table uniformisation ?
        }
    }

/**
 * {@inheritDoc}
 */
//// void tprint_print (TPrint *tprint);
//// void table_print_print(struct table_print_t *tp);
    public function tprint_print() {
        $symbol         = $this->symbols;
        $vborder_length = strlen($symbol['vborder']);
        $full_width     = $vborder_length;
        $beginrow       = "";   //string before a table row
        $hline          = "";   //string for a horizontal border line

        foreach($this->columns as $col) {
            if($col['max_width'] != -1 && $col['current_width'] > $col['max_width']) {
                $full_width += $col['max_width'];
            } else {
                $full_width += $col['current_width'];
            }
            $full_width += $this->spaces_between + $vborder_length;
        }
        $beginrow = sprintf("%'".$symbol['space_left'].$this->spaces_left."s", ""); //"%'.9d\n"

        if($this->show_borders) {
            $hline  = $beginrow; 
            $hline .= sprintf(" %'".$symbol['hborder'].($full_width - 2)."s \n", ""); //"%'=9d\n"
        } else {
            $symbol['vborder'] = $symbol['hborder'] = "";
            $hline = "";
        }

        if($this->show_header) {
            $this->sout .= $hline;
            
            $this->sout .= $beginrow;
            foreach($this->columns as $col) {
                $this->sout .= $this->print_cell($col['label'], $col['current_width'], $this->spaces_between, $col['label_align'], $symbol);
            }
            $this->sout .= $symbol['vborder'] . "\n";
        } else {
            //néant
        }

        $this->sout .= $hline;  //Data begin
        for($i=0 ; $i < $this->nb_rows ; $i++) {
            $this->sout .= $beginrow;
            foreach($this->columns as $col) {
                $this->sout .= $this->print_cell($col['data'][$i], $col['current_width'], $this->spaces_between, $col['data_align'], $symbol);
            }
            $this->sout .= $symbol['vborder'] . "\n";
        }
        $this->sout .= $hline;  //Data ending
        return $this->sout;
    }

/**
 * Print a cell in a string.
 *
 * @param   cell            cell data
 * @param   cell_width      (positive integer) with a the cell
 * @param   spaces_between  (positive integer) spaces after and before border cell (because is a sum, better even number) (ex. 4 : |..data..|)
 * @param   cell_align      how to align data in the cell [value = ITPrint::TPALIGN_LEFT| ITPrint::TPALIGN_CENTER| ITPrint::TPALIGN_RIGHT]
 * @param   symbol          symbols for print. Should be a associative array('vborder' => "|", 'space'' => ".", 'space_between' => " ")
 * @return      (string) cell of the ASCII tabular data of table
 */
    static public function print_cell($cell, $cell_width, $spaces_between, $cell_align, $symbol) {
        $str = "";
        $align = '+';

        if($cell_align == ITPrint::TPALIGN_LEFT) {
            $align = '-';
        }
        $str .= $symbol["vborder"];
        $str .= sprintf("%'".$symbol['space_between'].(int)($spaces_between / 2)."s", "");
        if($cell_align != ITPrint::TPALIGN_CENTER) {
            $str .= sprintf("%'".$symbol["space"].$align.$cell_width."s", $cell);
        } else {
            $str .= sprintf("%'".$symbol["space"]. (int)(($cell_width - strlen($cell)) / 2)."s", "");
            $str .= $cell;
            $str .= sprintf("%'".$symbol["space"]. ($cell_width - strlen($cell) - (int)(($cell_width - strlen($cell)) / 2))."s", "");
        }
        $str .= sprintf("%'".$symbol['space_between'].(int)($spaces_between / 2)."s", "");
        return $str;
    }

//Specific class method :
/**
 * Setter of symbols class property.
 *
 * Exemple : <code>$tp->setSymbols("|", "=", ".", " ", "_");</code> give with (cell_width=10, spaces_between=2, spaces_left=3)
 *     ___| data cells | ....ft.... |
 *         =========================
 *
 * @param   vborder        (string) define vertical border representation (ex. "|")
 * @param   hborder        (char) define horizontal border representation (ex. "=")
 * @param   space          (char) define space representation around data and borders. (ex. ".")
 * @param   space_between  (char) define space representation around data in cell or around border. Work with spaces_between property. (ex. " ")
 * @param   space_left     (char) define space representation around data in cell or around border. Work with spaces_left property. (ex. " ")
 */
    public function setSymbols($vborder, $hborder, $space, $space_between = " ", $space_left = " ") {
        $this->symbols['vborder']       = $vborder;
        $this->symbols['hborder']       = $hborder;
        $this->symbols['space']         = $space;
        $this->symbols['space_between'] = $space_between;
        $this->symbols['space_left']    = $space_left;
    }
/** Getter of symbols class property. */
    public function getSymbols() {
        return $this->symbols;
    }

/**
 * Getter of table widths for row and column.
 *
 * @param   $associative    (boolean)[Default=FALSE] to have an associative array.
 * @return      an array within row and columns numbers (Array('nb_rows', 'nb_columns'))
 */
    public function getWitdh($associative = FALSE) {
        if($associative) {
            return array('nb_rows'    => $this->nb_rows,
                         'nb_columns' => $this->nb_columns);
        } else {
            return array($this->nb_rows, $this->nb_columns);
        }
    }

} //End of class TPrint implements ITPrint

?>
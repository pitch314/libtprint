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
 * @file    ITPrint.php
 *
 * Class interface for table print, to print ASCII tabular data.
 *
 * @author  pitch314
 * @version 1.0, 2015-09-13
 */

/**
 * Class interface for table print utilities to print ASCII tabular data.
 * @author  pitch314
 * @version 1.0, 2015-09-13
 */
interface ITPrint {
/** Specify left align */
    const TPALIGN_LEFT   = 0;
/** Specify center align */
    const TPALIGN_CENTER = 1;
/** Specify right align */
    const TPALIGN_RIGHT  = 2;

/**
 * Create TPrint object.
 *
 * @param   show_borders    (boolean) set to TRUE to draw inner and outer borders
 * @param   show_header     (boolean) set to TRUE to display table header row
 * @param   spaces_left     (integer) spaces on the left side of the table
 * @param   spaces_between  (integer) spaces between columns (better even number)
 */
    public function tprint_create($show_borders, $show_header, $spaces_left, $spaces_between);
    
/**
 * Destroy TPrint object. (WARNING : Class can't self-destroy)
 */
    public function tprint_free();

/**
 * Append column to the table.
 *
 * @param   label         label of the column, can be NULL
 * @param   label_align   how to align column label [value = ITPrint::TPALIGN_LEFT| ITPrint::TPALIGN_CENTER| ITPrint::TPALIGN_RIGHT]
 * @param   data_align    how to align data in the column [value = ITPrint::TPALIGN_LEFT| ITPrint::TPALIGN_CENTER| ITPrint::TPALIGN_RIGHT]
 */
    public function tprint_column_add($label, $label_align, $data_align);

/**
 * Append row to the table.
 *
 * @param   data        (array) row data to add (data must be string convertible)
 */
    public function tprint_row_add($data);

/**
 * Append data in a specific column to the table.
 *
 * @param   num_col     (positive integer)column number
 * @param   data        data to add at column num_col (must be string convertible)
 */
    public function tprint_data_add($num_col, $data);

/**
 * Output table in a string.
 *
 * @return      (string) ASCII tabular data of table
 */
    public function tprint_print();
}

?>
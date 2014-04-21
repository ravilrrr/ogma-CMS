<?php

/**
 *	OGMA CMS Tables Module
 *
 *	@package ogmaCMS
 *	@author Mike Swan / n00dles101
 *	@copyright 2013 Mike Swan / n00dles101
 *	@since 1.0.0
 *
 */

class Table {
    
    public $tableOutput = "";
    public $table = "";
    public $tableRows = array();
    
    public function __construct($table) {
        $this->tableRows = new Matrix($table);
        $this->table     = $table;
        //print_r($this->tableRows->tableOptions);
    }
    
    public function getField($name, $label, $type, $options, $value = '') {
        if (@!include_once(Core::getAdminPath() . '/fields/' . $type . '.field.php')) {
            $this->tableOutput .= "Field not Found..." . $type;
        }
        if (class_exists($type)) {
            $id               = $options['id'];
            $value            = stripslashes(htmlentities($value, ENT_QUOTES, "UTF-8"));
            $options['table'] = $this->table;
            $options['id']    = $id;
            $options['field'] = str_replace('-' . $id, '', $name);
            $getValue         = new $type($name, $label, $type, $options, $value);
            $this->tableOutput .= $getValue->value;
        }
    }
    
    public function createTable($query, $fields, $options) {
        
        $rows = $this->tableRows->query($query);
        
        $saveas = 'id';
        //print_r($rows);
        $this->tableOutput .= '<table class="table table-bordered table-striped table-hover">';
        $this->tableOutput .= '<thead>';
        $this->tableOutput .= '  <tr>';
        /*
        <?php echo __("NAME"); ?></th>
        */
        foreach ($fields as $item => $value) {
            # code...
            $this->tableOutput .= '<th class="table-' . strtolower($value) . '" date-sort="' . strtolower($value) . '" >' . $item . '</th>';
            
        }
        $this->tableOutput .= '<th class="table-options">' . __("OPTIONS") . '</th>';
        $this->tableOutput .= '  </tr>';
        $this->tableOutput .= '</thead>';
        $this->tableOutput .= '<tbody> ';
        
        foreach ($rows as $row) {
            $this->tableOutput .= '<tr>';
            foreach ($fields as $item) {
                $this->tableOutput .= '<td>';
                $options['id'] = $row['id'];
                $this->tableOutput .= $this->getField($item . '-' . $row['id'], '', $this->tableRows->tableFields[$item], $options, $row[$item]);
                // .$row[$item].
                // $this->tableOutput .= $this->tableRows->tableFields[$item];
                
                $this->tableOutput .= '</td>';
            }
            
            $this->tableOutput .= '<td>';
            $this->tableOutput .= '<div class="btn-group">';
            $this->tableOutput .= '<button class="btn" onclick="location.href=\'' . Core::getFilenameId() . '.php?action=edit&amp;id=' . $row['id'] . '\'">' . __("EDIT") . '</button>';
            $this->tableOutput .= '<button class="btn dropdown-toggle" data-toggle="dropdown">';
            $this->tableOutput .= '    <span class="caret"></span>';
            $this->tableOutput .= '  </button>';
            $this->tableOutput .= ' <ul class="dropdown-menu">';
            $this->tableOutput .= '  <li><a href="#" data-nonce="' . Security::getNonce('deleterecord', Core::getFilenameId() . '.php') . '" data-slug="' . $row['id'] . '"  data-table="' . $this->table . '" class="delButton">' . __("DELETE") . '</a></li>';
            $this->tableOutput .= '</ul>';
            $this->tableOutput .= '</div>';
            $this->tableOutput .= '</td>';
            
            $this->tableOutput .= '</tr>';
        }
        
        
        $this->tableOutput .= ' </tbody>';
        $this->tableOutput .= '</table>';
        
    }
    
    
    public function endTable($query, $fields, $options) {
        
    }
    
    public function show() {
        echo $this->tableOutput;
    }
    
    public static function doPagination($page, $totalRecords) {
        
        echo '<div class="pagination pagination-small">';
        echo ' <ul>';
        if ($page == 0) {
            echo "<li class='disabled' ><span>Prev</span></li>";
        } else {
            echo "<li  ><a href='testbed.php?page=" . ($page - 1) . "'>Prev</a></li>";
        }
        
        for ($i = 0; $i < $totalRecords / 25; $i++) {
            echo ' <li';
            if ($i == $page)
                echo " class=' active' ";
            echo '><a href="testbed.php?page=' . ($i) . '">';
            echo $i;
            echo '</a></li>';
        }
        
        
        if ($page == round($totalRecords / 25) - 1) {
            echo "<li class='disabled' ><span>Next</span></li>";
        } else {
            echo "<li ><a href='testbed.php?page=" . ($page + 1) . "'>Next</a></li>";
        }
        echo '  </ul>';
        echo '</div>';
        
        
    }
    
}
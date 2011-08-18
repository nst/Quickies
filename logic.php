<?php

require_once('markdown.php');

class DBObject {
    
}

class Category extends DBObject {
    var $id;
    var $name;
    
    public static $table_name = 'q_category';
    
    public function delete() {
        $query = "DELETE FROM ".Category::$table_name." WHERE id = ".$this->id." LIMIT 1";
        $result = mysql_query($query) or die("mysql_error in delete: <b>".$query."</b> ". mysql_error());
        return 1;
    }
    
    public static function Create($name_param=null) {
        if($name_param == null) return;
        
        $name_param_safe = mysql_real_escape_string(stripslashes($name_param));
        
        $query = "INSERT INTO ".Category::$table_name." (name) VALUES ('".$name_param_safe."')";
        
        //echo $query;

    	$result = mysql_query($query) or die("mysql_error in insert: ". mysql_error());
    	
    	return 1;
    }
    
    public static function AllObjects($where_clause=null) {
        
        $query = "SELECT C.id, C.name FROM ".Category::$table_name." AS C";
        
        if($where_clause) {
            $query .= " WHERE (".$where_clause.")";
        }
        
        $query .= " ORDER BY C.name";
        
        $result = mysql_query($query) or die("Error in query: ". mysql_error());
        
        $a = array();

        while (list($cat_id, $cat_name) = mysql_fetch_row($result)) {
            $c = new Category();
            $c->id = $cat_id;
            $c->name = $cat_name;
            array_push($a, $c);
        }
        
        return $a;
    }
    
    public static function CategoriesWithId($id) {
        $id_int = (int)$id;
        $where_clause = "C.id = ".$id_int;
        return Category::AllObjects($where_clause);
    }

    public static function CategoryWithId($id) {
        $cs = Category::CategoriesWithId($id);
        return $cs[0];
    }
}

class Note extends DBObject {
    var $id;
    var $title;
    var $text;
    var $timestamp;
    var $category_id;
    var $category_name;
    
    public static $table_name = 'q_note';

    public function delete() {
        $query = "DELETE FROM ".Note::$table_name." WHERE id = ".$this->id." LIMIT 1";
        $result = mysql_query($query) or die("mysql_error in delete: <b>".$query."</b> ". mysql_error());
        return 1;
    }
    
    public function update($req_cat_id, $req_note_title, $req_note_text) {

        $safe_cat_id = mysql_real_escape_string((int)$req_cat_id);
        $safe_note_title = mysql_real_escape_string(stripslashes($req_note_title));
        $safe_note_text = mysql_real_escape_string(stripslashes($req_note_text));

    	$query = "UPDATE ".Note::$table_name." SET title = '".$safe_note_title."', text = '".$safe_note_text."', category_id = '".$safe_cat_id."' WHERE id = ".$this->id." LIMIT 1";
        //die($query);
    	$result = mysql_query($query) or die("mysql_error in update: <b>".$query."</b> ". mysql_error());
    }

    public static function AllObjectsCount() {
        $query = "SELECT COUNT(*) FROM ".Note::$table_name;
        $result = mysql_query($query) or die("Error in query: ". mysql_error());
        while (list($count) = mysql_fetch_row($result)) {
            return $count;
        }
        return 0;
    }
    
    public static function Create($req_cat_id, $req_title, $req_text) {
        if($req_cat_id == null || $req_title == null || $req_text == null) return;
        
        $safe_cat_id = mysql_real_escape_string((int)$req_cat_id);
        $safe_title = mysql_real_escape_string(stripslashes($req_title));
        $safe_text = mysql_real_escape_string(stripslashes($req_text));
        
        $query = "INSERT INTO ".Note::$table_name." (category_id, title, text) VALUES ('".$safe_cat_id."', '".$safe_title."', '".$safe_text."')";
        
    	$result = mysql_query($query) or die("mysql_error in insert: ". mysql_error());
    	
    	return 1;
    }
    
    public static function AllObjects($where_clause=null, $order_clause=null, $limit=0) {
        
        $query = "SELECT N.id, N.title, N.text, N.timestamp, N.category_id, C.id, C.name FROM ".Note::$table_name." AS N, ".Category::$table_name." AS C WHERE C.id = N.category_id";
        
        if($where_clause) {
            $query .= " AND (".$where_clause.")";
        }
                
        $query .= " ORDER BY ".$order_clause." C.name, N.title";
        
        if($limit != 0) {
            $query .= " LIMIT ".(int)$limit;
        }

        $result = mysql_query($query) or die("Error in query: ". mysql_error());
        
        $a = array();
    
        while (list($note_id, $note_title, $note_text, $note_timestamp, $note_category_id, $category_id, $category_name) = mysql_fetch_row($result)) {
            $n = new Note();
            $n->id = $note_id;
            $n->title = $note_title;
            $n->text = $note_text;
            $n->timestamp = $note_timestamp;
            $n->category_id = $note_category_id;
            $n->category_name = $category_name;            
            array_push($a, $n);
        }
                
        return $a;
    }
    
    public static function LatestNotes() {
        return Note::AllObjects(null, "N.timestamp DESC,", 15);
    }
    
    public static function AllNotesIncludingOnesWithoutCategory() {
        $a1 = Note::NotesWithMissingCategory();
        $a2 = Note::AllObjects();
        
        foreach($a2 as $n) {
            array_push($a1, $n);
        }

        return $a1;
    }
    
    public static function NotesWithMissingCategory() {
        $query = "SELECT N.id, N.title, N.text, N.timestamp, N.category_id FROM ".Note::$table_name." AS N WHERE category_id NOT IN (SELECT id FROM ".Category::$table_name.");";
        
        $result = mysql_query($query) or die("Error in query: ". mysql_error());
        
        $a = array();
    
        while (list($note_id, $note_title, $note_text, $note_timestamp, $note_category_id, $category_id) = mysql_fetch_row($result)) {
            $n = new Note();
            $n->id = $note_id;
            $n->title = $note_title;
            $n->text = $note_text;
            $n->timestamp = $note_timestamp;
            $n->category_id = 0;
            $n->category_name = "** no category **";            
            array_push($a, $n);
        }
        
        return $a;        

    }
    
    public static function NotesWithId($id) {
        $id_int = (int)$id;
        $where_clause = "N.id = ".$id_int;
        return Note::AllObjects($where_clause);
    }

    public static function NotesWithCatId($id) {
        $id_int = (int)$id;
        $where_clause = "C.id = ".$id_int;
        return Note::AllObjects($where_clause);
    }
    
    public static function NoteWithId($id) {
        $notes = Note::NotesWithId($id);
        
        $n = $notes[0];
        
        if($n) return $n;
        
        return Note::NoteWithIdNoCategory($id);
    }
    
    public static function NotesWithIdNoCategory($id) {
        $id_int = (int)$id;        
        
        $query = "SELECT id, title, text, timestamp, category_id FROM `".Note::$table_name."` WHERE id = ".$id_int.";";
        //echo $query;
        
        $result = mysql_query($query) or die("Error in query: ". mysql_error());
        
        $a = array();
    
        while (list($note_id, $note_title, $note_text, $note_timestamp, $note_category_id) = mysql_fetch_row($result)) {
            $n = new Note();
            $n->id = $note_id;
            $n->title = $note_title;
            $n->text = $note_text;
            $n->timestamp = $note_timestamp;
            $n->category_id = $note_category_id;    
            array_push($a, $n);
        }
        
        return $a;
    }
    
    public static function NoteWithIdNoCategory($id) {
        $a = Note::NotesWithIdNoCategory($id);
        if(count($a) > 0) {
            return $a[0];
        }
        return null;
    }
    
    public function NotesWithSearchString($s) {
        $ss = mysql_real_escape_string($s);
        $where_clause = "N.title LIKE '%$ss%' OR N.text LIKE '%$ss%'";
        return Note::AllObjects($where_clause);
    }
}

?>

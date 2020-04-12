<?ob_start();

header("Content-Type: text/html; charset=utf-8");
include_once "config.inc";
include_once "../logic.php";

$action = $_REQUEST['action'];
$req_note_id = (int) $_REQUEST['id'];
$req_cat_id = (int) $_REQUEST['cat'];
$req_cat_name = $_REQUEST['cat_name'];
$req_note_title = $_REQUEST['note_title'];
$req_note_text = $_REQUEST['note_text'];
$req_confirm_delete = $_REQUEST['confirm_delete'];
$req_cancel_delete = $_REQUEST['cancel_delete'];
$req_search_string = $_REQUEST['search_string'];

$display_mode = 'notes';

if(isset($_GET['categories'])) {
    $display_mode = "categories";
    echo $display_mode;
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title><?=$title_faq?></title>
	<link href="../style.css" rel="stylesheet" type="text/css" />
</head>
<body>

    <div id="pageTitle"><?=$title_faq?></div>

    <p>[<a href="<?=$PHP_SELF?>">notes</a>] [<a href="<?=$PHP_SELF?>?categories">categories</a>]</p>

<?

if($display_mode == 'categories') {

    if ($action == 'delete') {
        /////////////////////////
        echo "<h4>Delete Category</h4>\n";
        /////////////////////////
        
        $c = Category::CategoryWithId($req_cat_id);

    	if ($req_confirm_delete) {

            $c->delete();
            
            // TODO: add status
            
    		header("Location: $PHP_SELF?categories");
    		exit;
    	}

    	if ($req_cancel_delete) {
    	    header("Location: $PHP_SELF?categories");
    		exit;
    	}
        
        echo "<form method=\"post\" action=\"$PHP_SELF?categories&action=delete&cat=$c->id\">\n";
    		echo "Do you really want to delete category $c->id - ".stripslashes($c->name)."?<br>\n";
    		echo "<input type=\"submit\" name=\"cancel_delete\" value=\"Cancel\">\n";
    		echo "<input type=\"submit\" name=\"confirm_delete\" value=\"Delete\">\n";
    	echo "</form>\n";
    }

    if ($action == 'edit') {
        /////////////////////////
        echo "<h4>Edit Category</h4>\n";
        /////////////////////////

        $c = Category::CategoryWithId($req_cat_id);
        
        $action_link = $PHP_SELF."?categories&action=update&cat=".$req_cat_id;
        $button_title = "Edit";
    } else {
        /////////////////////////
        echo "<h4>Create category</h4>\n";
        /////////////////////////
        
        $c = null;
        $action_link = $PHP_SELF."?categories&action=create";
        $button_title = "Create";
    }
    
    echo "<table id=\"categoryEdit\">\n";
    echo "<form method=\"post\" action=\"".$action_link."\">\n";
    echo "<tr>\n";
    echo "<td><input type='text' name='cat_name' value=\"".htmlspecialchars($c->name)."\" /></td>\n";
    echo "<td><input type='submit' class='submit' value='".htmlspecialchars($button_title)."' /></td>\n";
    echo "</tr>\n";
    echo "</form>\n";    
    echo "</table>\n";	

    if ($action == 'create') {
        Category::Create($req_cat_name);
    	header("Location: $PHP_SELF?categories");
    	exit;
    } else if ($action == 'update') {
        $cat = Category::CategoryWithId($req_cat_id);
        $cat->update($req_cat_name);
    	header("Location: $PHP_SELF?categories");
    	exit;
    }

    /////////////////////////
    echo "<h4>Categories</h4>\n";
    /////////////////////////

    $categories = Category::allObjects();

    echo "<table>\n";

    echo "<p>".count($categories)." categories</p>\n";

	echo "<tr>\n";
		echo "<th>id</td>\n";
		echo "<th>name</td>\n";
		echo "</td>\n";
	echo "</tr>\n";

    foreach($categories as $c) {
    	echo "<tr>\n";
    	    echo "<td>".$c->id."</td>\n";
    		echo "<td><a href='?categories&action=edit&cat=".$c->id."'>edit</a></td>";
    		echo "<td><a href='?categories&action=delete&cat=".$c->id."'>delete</a></td>";

            echo "<td>".$c->name."</td>\n";
            echo "<td><a href='../index.php?cat=".$c->id."'>".$c->name."</a></td>";
    	echo "</tr>\n";
	}

    echo "</table>\n";

} else if ($display_mode == "notes") {

    if ($action == 'delete') {

    	if ($req_confirm_delete) {

            /////////////////////////
            echo "<h4>Delete Notes</h4>\n";
            /////////////////////////
            
            $n = Note::NoteWithIdNoCategory($req_note_id);
            
            $status = $n->delete();
            
            if($status == 0) {
                echo "error, cannot delete note";
                exit;
            }
            
    		header("Location: $PHP_SELF?notes");
    		exit;
    	}

    	if ($req_cancel_delete) {
    	    header("Location: $PHP_SELF?notes");
    		exit;
    	}
        
        $n = Note::NoteWithIdNoCategory($req_note_id);

        echo "<form method=\"post\" action=\"$PHP_SELF?notes&action=delete&id=$n->id\">\n";
    		echo "Do you really want to delete note $req_note_id - ".stripslashes($n->title)."?<br>\n";
    		echo "<input type=\"submit\" name=\"cancel_delete\" value=\"Cancel\">\n";
    		echo "<input type=\"submit\" name=\"confirm_delete\" value=\"Delete\">\n";
    	echo "</form>\n";
    }

    /////////////////////////
    echo "<h4>Search Notes</h4>\n";
    /////////////////////////
    ?>
    <div id="searchForm">
    <form action='<?=$PHP_SELF?>' method='post'>
    <table>
        <tr>
            <td><input type='text' name='search_string' value='<?=htmlentities($req_search_string)?>' /></td>
            <td><input type='submit' class='submit' value='Search' /></td>
        </tr>
    </table>
    </form>
    <?

    $categories = Category::allObjects();

    if ($action == 'create') {
    
        Note::Create($req_cat_id, $req_note_title, $req_note_text);
        
    	header("Location: $PHP_SELF");
    	exit;
    	
    } else if ($action == 'update') {

        $note = Note::NoteWithId($req_note_id);

        $note->update($req_cat_id, $req_note_title, $req_note_text);

    	header("Location: $PHP_SELF");
    	exit;

    } else {

        if ($action == 'edit') {
            /////////////////////////
            echo "<h4>Edit Note</h4>\n";
            /////////////////////////

            $n = Note::NoteWithId($req_note_id);
            
            $action_link = $PHP_SELF."?action=update&id=".$req_note_id;
        } else {
            /////////////////////////
            echo "<h4>Create Note</h4>\n";
            /////////////////////////

            $n = null;
            $action_link = $PHP_SELF."?action=create";
        }
        
        echo "<table id=\"noteEdit\">\n";
        echo "<form method=\"post\" action=\"".$action_link."\">\n";
        
        echo "<tr><th>category</th>";
        
        echo "<td><select name=\"cat\">\n";
        	$categories = Category::allObjects();

        	foreach ($categories as $c) {
        	    if($c->id == $n->category_id) {
        		    echo "<option selected=\"selected\" name = \"cat\" value=\"$c->id\">".$c->name."</option>\n";
        	    } else {
        		    echo "<option  name = \"cat\" value=\"$c->id\">".$c->name."</option>\n";
        		}
        	}
        echo "</select></td></tr>\n";

        echo "<tr><th>title</th>";

        echo "<td><input type=\"text\" size=\"93\" name=\"note_title\" value=\"".htmlspecialchars($n->title)."\" /></td>\n";
        echo "<tr><th>text</th><td><textarea rows=20 cols=80 wrap=\"off\" name=\"note_text\">".$n->text."</textarea></td>\n";
        echo "<tr><th></th><td><input type=\"submit\" class=\"submit\" name=\"submit\" value=\"Submit\"></td>\n";
        echo "</form>\n";
        
        echo "</table>\n";	
    }

    /////////////////////////
    echo "<h4>Notes</h4>\n";
    /////////////////////////

        $notes = array();

        if($req_search_string != "") {
            $notes = Note::NotesWithSearchString($req_search_string);            
        } else {
            $notes = Note::AllNotesIncludingOnesWithoutCategory();
        }
        
        echo "<table>\n";

        echo "<p>".count($notes)." notes</p>\n";

    	echo "<tr>\n";
    		echo "<th>id</td>\n";
    		echo "<th>edit</td>\n";
    		echo "<th>delete</td>\n";
    		echo "<th>category</td>\n";
    		echo "<th>title</td>\n";
    		echo "</td>\n";
    	echo "</tr>\n";

        foreach($notes as $n) {

        	echo "<tr>\n";
        	    echo "<td>".$n->id."</td>\n";
        		echo "<td><a href='?action=edit&id=".$n->id."'>edit</a></td>";
        		echo "<td><a href='?action=delete&id=".$n->id."'>delete</a></td>";

                echo "<td>".$n->category_name."</td>\n";
                echo "<td><a href='../index.php?id=".$n->id."'>".$n->title."</a></td>";
        	echo "</tr>\n";

    	}

    echo "</table>\n";
}

?>

<div id="footer"><?=$footer_text?></div>

</body>
</html>

<?ob_end_flush();?>

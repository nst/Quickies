<?PHP

header("Content-Type: text/html; charset=utf-8");

include_once "config.inc";
include_once "logic.php";

$req_note_id = (int) $_GET['id'];
$req_cat_id = (int) $_GET['cat'];
$req_search_string = $_POST['search_string'];

$display_mode = 'categories';

if(isset($_GET['index'])) {
    $display_mode = "titles";
} else if (isset($_GET['all']) || isset($_GET['latest']) || $req_note_id != "" || $req_cat_id != "" || $req_search_string != "") {
    $display_mode = "contents";
}

$page_title = $title_faq;

if($display_mode == 'categories') {
    $notes = Note::AllNotesIncludingOnesWithoutCategory();
} else if($req_note_id != 0) {
    $notes = Note::NotesWithId($req_note_id);
    if(count($notes) > 0) {
        $n = $notes[0];
        $page_title .= " > ".$n->category_name." > ".$n->title;
    }
} else if($req_cat_id != 0) {
    $c = Category::CategoryWithId($req_cat_id);
    $page_title .= " > ".$c->name;
    $notes = Note::NotesWithCatId($c->id);
} else if($req_search_string != '') {
    $notes = Note::NotesWithSearchString($req_search_string);
} else if (isset($_GET['latest'])) {
    $notes = Note::LatestNotes();
} else {
    $notes = Note::AllNotesIncludingOnesWithoutCategory();
}

$notes_count = Note::AllObjectsCount();

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    	<title><?=htmlspecialchars($page_title)?></title>
    	<link href="style.css" rel="stylesheet" type="text/css" />
        <link rel="shortcut icon" href="/favicon.ico" />
    </head>

    <body>
 
        <div id="pageTitle"><?=$title_faq?></div>

        <p>[<a href="<?=$PHP_SELF?>">categories</a>] 
           [<a href="<?=$PHP_SELF?>?index">index</a>] 
           [<a href="<?=$PHP_SELF?>?all">all (<?=$notes_count?>)</a>] 
           [<a href="<?=$PHP_SELF?>?latest">latest</a>]</p>

        <div id="searchForm">
        <form action='<?=$PHP_SELF?>' method='post'>
        <table>
            <tr>
                <td><input type='text' name='search_string' value='<?=$req_search_string?>' /></td>
                <td><input type='submit' class='submit' value='Search' /></td>
            </tr>
        </table>
        </form>

        </div>      
<?

$current_cat_id = "";

foreach($notes as $n) {

    if($n->category_id != $current_cat_id) {
        
        if($display_mode == 'categories') {
            echo "<div class=\"catTitleSmall\"><a href=\"$PHP_SELF?cat=$n->category_id\">".$n->category_name." (".count(Note::NotesWithCatId($n->category_id)).")</a></div>\n";                                                                        
        } else {
            if($current_cat_id != "") {
                echo "</ol>\n";
            }
            echo "<div class=\"catTitleBig\"><a href=\"$PHP_SELF?cat=$n->category_id\">".$n->category_name."</a></div>\n";                                                
            echo "<ol>\n";
        }
        
        $current_cat_id = $n->category_id;
    }
        
    if($display_mode == 'contents') {
        echo "<li class=\"itemsList\">\n";
        echo "<div class=\"noteTitleBig\"><a href=\"".$PHP_SELF."?id=".$n->id."\">".$n->title."</a></div>\n";
        echo "<div class=\"noteText\">".Markdown($n->text)."</div>\n";
        echo "</li>\n";
    } else if ($display_mode == 'titles') {
        echo "<li class=\"itemsList\">\n";
        echo "<div class=\"noteTitleSmall\"><a href=\"$PHP_SELF?id=$n->id\">".$n->title."</a></div>\n";
        echo "</li>\n";
    } else if ($display_mode == 'categories') {
        // nothing
    }


}

if($display_mode == 'contents') {
    echo "</ol>";
}

if($display_mode == 'titles') {
    echo "</ol>";
}

?>

     <div id="footer"><?=$footer_text?></div>

	<script type="text/javascript">

	  var _gaq = _gaq || [];
	  _gaq.push(['_setAccount', 'UA-987064-3']);
	  _gaq.push(['_trackPageview']);

	  (function() {
	    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();

	</script>

    </body>
</html>

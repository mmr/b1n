<?
/* $Id: topmenu.inc.php,v 1.24 2002/12/21 18:16:32 binary Exp $ */

$menu =array( array("name"    => "Home",
                    "link"    => b1n_URL . "?page0=",
                    "icon"    => "img/topmenu/home.gif",
                    "target"  => "_top"),
              array("name"    => "Admin",
                    "link"    => b1n_URL . "?page0=admin",
                    "icon"    => "img/topmenu/admin.gif",
                    "target"  => "toc"),
              array("name"    => "Itinerary",
                    "link"    => b1n_URL . "?page0=itinerary",
                    "icon"    => "img/topmenu/itinerary.gif",
                    "target"  => "content"),
              array("name"    => "Agent",
                    "link"    => b1n_URL . "?page0=agent",
                    "icon"    => "img/topmenu/agent.png",
                    "target"  => "toc"),
              array("name"    => "Pax",
                    "link"    => b1n_URL . "?page0=pax",
                    "icon"    => "img/topmenu/pax.png",
                    "target"  => "toc"),
              array("name"    => "Data",
                    "link"    => b1n_URL . "?page0=data",
                    "icon"    => "img/topmenu/data.gif",
                    "target"  => "toc"),
              array("name"    => "BookMark",
                    "link"    => b1n_URL . "?page0=bookmark",
                    "icon"    => "img/topmenu/bookmark.gif",
                    "target"  => "toc"),
              array("name"    => "Docs",
                    "link"    => b1n_URL . "?page0=docs",
                    "icon"    => "img/topmenu/docs.gif",
                    "target"  => "content"),
              array("name"    => "LogOut",
                    "link"    => b1n_URL . "?page0=logout",
                    "icon"    => "img/topmenu/logout.gif",
                    "target"  => "_top"));
?>
<html>
<head>
    <title>FlyWatch <?= b1n_VERSION ?> - TopMenu</title>
    <link rel='stylesheet' href='<?= b1n_CSS ?>' />
    <script language='Javascript'>
    function b1n_frameVerifySize(a)
    {
        var where_we_are        = parent.toc.location.href;
        var where_we_are_going  = a.href;
    
        var middle_frame = parent.document.getElementById("manInTheMiddle");
        // var frames = parent.document.getElementsByTagName("FRAMESET");
        // var middleFrame = frames[1];

        var r = new RegExp('itinerary|docs|noToc');

        var are_we_there = r.test(where_we_are);
        var are_we_going = r.test(where_we_are_going);

        // We are going to ...?
        if(are_we_going)
        {
            // Are we already there?
            if(are_we_there)
            {
                // Yes, we are already there, nothing to do.
                return;
            }
            else
            {
                // No, so, hide TOC
                parent.toc.location = '<?= b1n_URL ?>?page0=blank&toc=noToc';
                middle_frame.cols = '0, *'; 
            }

        }
        // No, we are not going to ...?
        // Are/Was we there?
        else if(are_we_there)
        {
            // Yes, we was there, get TOC back.
            middle_frame.cols = '<?= b1n_FRAMESET_MIDDLE_COLS ?>';
        }
    }
    </script>
</head>

<body noWrap scroll='no' class='menu'>
<div class='back'>
    <div class='section'>
        <span class='maintitle'><a href='<?= b1n_URL ?>' target='_top'>FlyWatch <?= b1n_VERSION ?></a></span>
        <br />
<?
$aux = array_shift($menu);

if(b1n_USEICONS)
{
    echo "<a class='menu' name='" . $aux['name'] . "' href='" . $aux['link'] . "' title='" . $aux['name'] . "' target='" . $aux['target'] . "' onClick='b1n_frameVerifySize(this.name);'><img src='" . $aux['icon'] . "' alt='" . $aux['name'] . "' border='0'></a>";

    foreach($menu as $item)
    {
        echo "<a class='menu' name='" . $aux['name'] . "' href='" . $item['link'] . "' title='" . $aux['name'] . "' target='" . $item['target'] . "' onClick='b1n_frameVerifySize(this.name);'><img src='" . $item['icon'] . "' alt='" . $item['name'] . "' border='0'></a>";
    }
}
else
{
    echo "[ <a class='menu' href='" . $aux['link'] . "' target='" . $aux['target'] . "' onClick='b1n_frameVerifySize(this);'>" . $aux['name'] . "</a> ]";

    foreach($menu as $item)
    {
        echo "&nbsp;&nbsp;-&nbsp;&nbsp;[ <a class='menu' href='" . $item['link'] . "' target='" . $item['target'] . "' onClick='b1n_frameVerifySize(this);'>". $item['name'] . "</a> ]";
    }
}

unset($menu);
?>
    </div>
</div>
</body>
</html>

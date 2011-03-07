<form id="searchform" action="<?php bloginfo('url'); ?>" method="get">
        <input type="text" name="s" id="s" value="<?php the_search_query(); ?>" />
        <input type="submit" id="searchsubmit" value="Search" />
</form>
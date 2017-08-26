<div class="upcoming-book mrgn-btm-15" >
          <?php
					if($cont_txt=$db->get_var("select TokenText from tokens where code='book_txt' and SiteID=".SITE_ID." and zStatus='1'")) {
					echo $cont_txt;
					}
					?>
        </div>
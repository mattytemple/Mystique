<?php /* Mystique/digitalnature */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php //language_attributes(); ?>>

<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

<title><?php wp_title('&laquo;', true, 'right'); if (get_mystique_option('seo')): if (get_query_var('cpage') ) print ' Page '.get_query_var('cpage').' &laquo; '; endif;?> <?php bloginfo('name'); ?></title>

<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="alternate" type="application/atom+xml" title="<?php bloginfo('name'); ?> Atom Feed" href="<?php bloginfo('atom_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<link rel="shortcut icon" href="<?php bloginfo('template_directory'); ?>/favicon.ico" />

<?php
if (is_singular() && get_option('thread_comments')) wp_enqueue_script('comment-reply');
if(get_mystique_option('jquery')):
 wp_enqueue_script('jquery');
 wp_enqueue_script('mystique',get_bloginfo('template_url').'/js/jquery.mystique.min.js',array(),false,true);
endif;

wp_head();
$user_head = get_mystique_option('head_code');
if ($user_head):
 $user_head = trim($user_head);
 $user_head = preg_replace('/\<\?php/', '', $user_head, 1); // remove "<?php"
 eval($user_head);
endif; ?>
</head>
<body class="<?php mystique_body_class() ?>">
 <div id="page">
  <div id="xmas">
  <div id="xmas-left">
  <div id="xmas-right">

  <div class="shadow-left page-content header-wrapper">
   <div class="shadow-right">

    <div id="header" class="bubbleTrigger">
      <div id="site-title">

       <?php
       // logo image?
       if(get_mystique_option('logo')): ?>
       <div id="logo"><a href="<?php bloginfo('url'); ?>/"><img src="<?php echo get_mystique_option('logo'); ?>" title="<?php bloginfo('name');  ?>" alt="<?php bloginfo('name');  ?>" /></a></div>
       <?php else: ?>
       <div id="logo"><a href="<?php bloginfo('url'); ?>/"><?php bloginfo('name'); ?></a></div>
       <?php endif;  ?>

       <?php if(get_bloginfo('description')): ?><p class="headline"><?php bloginfo('description'); ?></p><?php endif; ?>

      </div>

      <a href="<?php bloginfo('rss2_url'); ?>" class="nav-extra rss" title="<?php _e("RSS Feeds","mystique"); ?>"><span><?php _e("RSS Feeds","mystique"); ?></span></a>
      <?php
       //$twituser = get_mystique_option('twitter_id');
       // if(is_active_widget('TwitterWidget'))
       $twitinfo =  get_option('mystique-twitter');
       $twituser = $twitinfo['last_twitter_id'];

       if ($twituser): ?>
      <a href="http://www.twitter.com/<?php echo $twituser; ?>" class="nav-extra twitter" title="<?php _e("Follow me on Twitter!","mystique"); ?>"><span><?php _e("Follow me on Twitter!","mystique"); ?></span></a>
      <?php endif; ?>

      <ul id="navigation">

         <?php
          $navtype = get_mystique_option('navigation');
          if((get_option('show_on_front')<>'page') && get_mystique_option('exclude_home')<>'1'):
           if(is_home() && !is_paged()): ?>
            <li class="current_page_item" id="nav-home"><a class="home fadeThis" href="<?php echo get_settings('home'); ?>" title="<?php _e('You are Home','mystique'); ?>"><span class="title"><?php _e('Home','mystique'); ?></span><span class="pointer"></span></a></li>
           <?php else: ?>
            <li id="nav-home"><a class="home fadeThis" href="<?php echo get_option('home'); ?>" title="<?php _e('Click for Home','mystique'); ?>"><span class="title"><?php _e('Home','mystique'); ?></span><span class="pointer"></span></a></li>
          <?php
           endif;
          endif; ?>
         <?php
           if($navtype=='categories'):
            echo preg_replace('@\<li([^>]*)>\<a([^>]*)>(.*?)\<\/a>@i', '<li$1><a class="fadeThis"$2><span class="title">$3</span><span class="pointer"></span></a>', wp_list_categories('show_count=0&echo=0&title_li=&exclude='.get_mystique_option('exclude_categories')));
           elseif($navtype=='links'):

            $links = get_bookmarks(array(
            'orderby'        => 'name',
            'order'          => 'ASC',
            'limit'          => -1,
            'category'       => null,
            'category_name'  => get_mystique_option('navigation_links'),
            'hide_invisible' => true,
            'show_updated'   => 0,
            'include'        => null,
            'search'         => '.'));

            foreach ($links as $link)
             echo '<li><a class="fadeThis" href="'.$link->link_url.'" target="_blank"><span class="title">'.$link->link_name.'</span></a><li>';

           else:
             echo preg_replace('@\<li([^>]*)>\<a([^>]*)>(.*?)\<\/a>@i', '<li$1><a class="fadeThis"$2><span class="title">$3</span><span class="pointer"></span></a>', wp_list_pages('echo=0&orderby=name&title_li=&exclude='.get_mystique_option('exclude_pages')));
           endif;
          ?>

      </ul>
    </div>

   </div>
  </div>

  <!-- left+right bottom shadow -->
  <div class="shadow-left page-content main-wrapper">
   <div class="shadow-right">

     <?php if(is_page_template('featured-content.php') || (is_home() && get_mystique_option('featured_home'))): ?>
     <div id="featured-content" class="withSlider">
       <!-- block container -->
       <div class="slide-container">
        <ul class="slides">

        <?php
         $category = get_mystique_option('featured_content');
         $args = 'showposts=5&orderby=date';
         if($category) $args .= '&cat='.$category;

         $backup = $post;
         $query = new WP_Query($args);

         $count = 1;
         if ($query->have_posts()):
          while ($query->have_posts()):
           $query->the_post();

           if(function_exists('the_post_image'))
            if (has_post_image()) $post_thumb = true; else $post_thumb = false;

           ?>
           <!-- slide (100%) -->
           <li class="slide slide-<?php echo $count; ?> featured-content">
            <div class="slide-content clearfix">
             <div class="details clearfix">
             <?php if($post_thumb): ?>
               <a class="post-thumb alignleft" href="<?php the_permalink(); ?>"><?php the_post_image('thumbnail'); ?></a>
              <?php else:
               $image = get_first_image();
               if ($image): ?>
               <a class="post-thumb alignleft" href="<?php the_permalink(); ?>"><img height="150" src="<?php echo $image; ?>" alt="<?php the_title(); ?>" /></a>
               <?php endif; endif; ?>

               <h3><?php echo strip_string(50,get_the_title()); ?></h3>
              <div class="summary"><?php echo strip_string(300,get_the_excerpt()); ?></div>
             </div>
             <a href="<?php the_permalink(); ?>" rel="bookmark" class="readmore"><?php _e("Read more","mystique"); ?></a>
            </div>
           </li>
           <!-- /slide -->
		 <?php
           $count++;
          endwhile;
         endif;

         $post = $backup;
         wp_reset_query();

         ?>
        </ul>
       </div>
       <!-- /block container -->
    </div>
    <?php endif; ?>
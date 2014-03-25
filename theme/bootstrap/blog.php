<?php if(!defined('IN_OGMA')){ die('you cannot load this page directly.'); }
/****************************************************
*
* @File:      blog.php
* @Package:   GetSimple
* @Action:    Bootstrap3 for GetSimple CMS
*
*****************************************************/
?>
<?php include('header.inc.php'); ?>
      <div class="row">
        <div class="col-md-8">
        	<?php Actions::executeAction('bootstrap-blog-title'); ?>
          	<?php 
            $blogs = new Blog();

          	$uri = Url::getMatches();
            $route_match =  Url::getRouteMatch();

            switch ($route_match) {
              case 'blog-archive':
                $year = $uri[1];
                $month = $uri[2];
                $first = date('U', mktime(0, 0, 0, $month, 1, $year));
                $last = date('U', mktime(0, 0, 0, $month +1, 0, $year));
                $blogs->entries =  $blogs->blogs->reload()->find('status = Published')->dated('range', $first, $last)->order('pubdate','desc')->get();
                $blogs->displayBlogPosts(false);
                break;
              case 'blog':
                $record = $blogs->blogs->reload()->find('slug = '.$uri[1])->get();
                if (count($record)>0){
                  Core::mergePageInfo($record[0],'blog');
                  $blogs->displayBlogPost($record[0]['id'], true);
                } else {
                  Page::get404();
                }
                break; 
              case 'blog-tags':
                $tag = $uri[1];
                $blogs->entries =  $blogs->blogs->reload()->find('tags like '.$tag)->order('pubdate','desc')->get();
                $blogs->displayBlogPosts(false);
                break;
              default:
                
                $blogs->setPageSize(10);
                $blogs->displayBlogPosts(false);# code...
                break;
            }
          	
          	?>
        </div>
        
        <div class="col-md-4">
        	<?php 
        		$blogs->getArchives(__("BLOG_ARCHIVES"), true);
        		$blogs->getCategories(__("BLOG_CATEGORIES"),true);
          	Actions::executeAction('bootstrap-blog-sidebar'); 
          	?>
        </div>
      </div>

<?php include('footer.inc.php'); ?>

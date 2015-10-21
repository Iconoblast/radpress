<?php
/**
 * Template Name: Photoswipe with Isotope Filters from Options page.
 * 
 * Photoswipe WordPress implementation with ACF Gallery field
 * AND isotope filtering on the grid with filters that are set in an ACF Options page
 * What you need: WordPress v4+, ACF v5, Photoswipe http://photoswipe.com/
**/

//first set up your ACF Gallery field.  In this case the name of the gallery field is 'images'.  Confusing, sorry.
// More here: http://www.advancedcustomfields.com/resources/gallery/


// put the following into functions.php (uncommented of course):
//function isotope_scripts() {
//wp_enqueue_script( 'isotope' , get_template_directory_uri() . '/js/isotope.pkgd.min.js', array('jquery'), '20150415', true );
//wp_enqueue_script( 'imagesLoaded', get_stylesheet_directory_uri() . '/js/imagesloaded.pkgd.min.js');
//add_action( 'wp_enqueue_scripts', 'isotope_scripts' );

// and then go get the isotope script here: http://isotope.metafizzy.co/ and as imagesloaded here: https://github.com/desandro/imagesloaded

get_header(); ?>


	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main" style="max-width: 840px;overflow: hidden;">
    <!-- max-width and overflow:hidden is so that the isotope tiles don't get wacky when you click the filters. 
    There might be a better way around this, not sure yet -->

      <?php //this is your regular page content; change this to suit your theme

      while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'content', 'page' ); ?>				

			<?php endwhile; // end of the loop. ?>

<?php $images = get_field('images'); // ACF field gallery field name (name of gallery field is 'images' here)
if( $images ): ?>

<?php if( have_rows('flower_gallery_filters', 'option') ): // Create an options page and use a repeater field for the filters.  Here the repeater field name is 'flower_gallery_filters'
  
  ?>
<div class="navigate columns" id="filters">
    <ul>
    <li><a href="" data-filter="*" class="active buttonlink">All</a></li>
    <?php while( have_rows('flower_gallery_filters', 'option') ): the_row(); 

        // vars
        $filtername = get_sub_field('filter_name');
        $slug = sanitize_html_class( $filtername ); // sanitize so the filters can be used as CSS selectors for isotope
        ?>

        <li><a href="" data-filter=".<?php echo $slug; ?>" class="buttonlink"><?php echo $filtername; ?></a></li>

    <?php endwhile; ?>

    </ul>
</div>
<?php endif; ?>


  <div id="gallery_container" class="gallery_container my-gallery isotope" itemscope itemtype="http://schema.org/ImageGallery">
<?php foreach( $images as $image ): ?>
    <figure class="item <?php echo $image['caption']; ?>" itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject">
      <a href="<?php echo $image['url']; ?>" itemprop="contentUrl" data-size="<?php echo $image['width'] . 'x' . $image['height']; ?>">
          <img class="grid-sizer" src="<?php echo $image['sizes']['thumbnail']; ?>" itemprop="thumbnail" alt="<?php echo $image['alt']; ?>" title="<?php echo $image['title']; ?>" />
      </a>
          <figcaption itemprop="caption description">
                <h4><?php echo $image['title']; ?></h4>
                <?php echo $image['description']; ?>
          </figcaption>
                                          
    </figure>
<?php endforeach; ?>
  </div>
<?php endif; ?>


<!-- end orig gallery html-->


<!-- Photoswipe HTML.  Now put all of the following somewhere on the page. -->
<!-- Root element of PhotoSwipe. Must have class pswp. -->
<div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">

    <!-- Background of PhotoSwipe. 
         It's a separate element, as animating opacity is faster than rgba(). -->
    <div class="pswp__bg"></div>

    <!-- Slides wrapper with overflow:hidden. -->
    <div class="pswp__scroll-wrap">

        <!-- Container that holds slides. PhotoSwipe keeps only 3 slides in DOM to save memory. -->
        <!-- don't modify these 3 pswp__item elements, data is added later on. -->
        <div class="pswp__container">
            <div class="pswp__item"></div>
            <div class="pswp__item"></div>
            <div class="pswp__item"></div>
        </div>

        <!-- Default (PhotoSwipeUI_Default) interface on top of sliding area. Can be changed. -->
        <div class="pswp__ui pswp__ui--hidden">

            <div class="pswp__top-bar">

                <!--  Controls are self-explanatory. Order can be changed. -->

                <div class="pswp__counter"></div>

                <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>

                <button class="pswp__button pswp__button--share" title="Share"></button>

                <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>

                <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>

                <!-- Preloader demo http://codepen.io/dimsemenov/pen/yyBWoR -->
                <!-- element will get class pswp__preloader--active when preloader is running -->
                <div class="pswp__preloader">
                    <div class="pswp__preloader__icn">
                      <div class="pswp__preloader__cut">
                        <div class="pswp__preloader__donut"></div>
                      </div>
                    </div>
                </div>
            </div>

            <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                <div class="pswp__share-tooltip"></div> 
            </div>

            <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)">
            </button>

            <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)">
            </button>

            <div class="pswp__caption">
                <div class="pswp__caption__center"></div>
            </div>

          </div>

        </div>

</div>
<!-- end Photoswipe HTML -->

		

		</main><!-- #main -->
	</div><!-- #primary -->

<!-- script to set up isotope and photoswipe and init  -->
<script>
jQuery(document).ready(function($){
    var itemReveal = Isotope.Item.prototype.reveal;
    Isotope.Item.prototype.reveal = function() {
      itemReveal.apply( this, arguments );
      $( this.element ).removeClass('isotope-hidden');
    };

    var itemHide = Isotope.Item.prototype.hide;
    Isotope.Item.prototype.hide = function() {
      itemHide.apply( this, arguments );
      $( this.element ).addClass('isotope-hidden');
    };


    $('.isotope').isotope({
      itemSelector: '.item',
      masonry: {
        columnWidth: '.grid-sizer',
        isFitWidth: true,
        gutter: 10
      }, /* masonry */
      hiddenStyle: {
        opacity: 0
      },
      visibleStyle: {
        opacity: 1
      },
      transitionDuration: '0.5s',

    });
    // filter functions
    $('.navigate a').click(function(){
      var selector = $(this).attr('data-filter');
      $('.isotope').isotope({ filter: selector });
      return false;
       });
       $('.buttonlink').click(function(){
             $('.buttonlink').removeClass('active');
            $(this).addClass('active');
     });

    /*
    If you only need to handle the dynamic image filtering it is pretty simple.

    Instead of the `initPhotoSwipeFromDOM` code, you need to use
    */
    var initPhotoSwipeFromDOM = function(gallerySelector) {

      // parse slide data (url, title, size ...) from DOM elements 
      // (children of gallerySelector)
      var hash = '';
      var setHash = function setHash( newHash ){
        newHash = newHash || '';
        if(history.replaceState) {
            if (newHash[0] !== '#'){
              newHash = '#' + newHash;
            }
            history.replaceState(null, null, newHash);
        }
        else {
          location.hash = newHash;
        }
      };
      var parseThumbnailElements = function(el) {
        var thumbElements = $(el).children(':not(.isotope-hidden)').get(),
            numNodes = thumbElements.length,
            items = [],
            figureEl,
            linkEl,
            size,
            item;

        for(var i = 0; i < numNodes; i++) {

          figureEl = thumbElements[i]; // <figure> element

          // include only element nodes 
          if(figureEl.nodeType !== 1) {
            continue;
          }

          linkEl = figureEl.children[0]; // <a> element

          size = linkEl.getAttribute('data-size').split('x');

          // create slide object
          item = {
            src: linkEl.getAttribute('href'),
            w: parseInt(size[0], 10),
            h: parseInt(size[1], 10)
          };



          if(figureEl.children.length > 1) {
            // <figcaption> content
            item.title = figureEl.children[1].innerHTML; 
          }

          if(linkEl.children.length > 0) {
            // <img> thumbnail element, retrieving thumbnail url
            item.msrc = linkEl.children[0].getAttribute('src');
          } 

          item.el = figureEl; // save link to element for getThumbBoundsFn
          items.push(item);
        }

        return items;
      };

      // find nearest parent element
      var closest = function closest(el, fn) {
        return el && ( fn(el) ? el : closest(el.parentNode, fn) );
      };

      // triggers when user clicks on thumbnail
      var onThumbnailsClick = function(e) {
        e = e || window.event;
        e.preventDefault ? e.preventDefault() : e.returnValue = false;

        var eTarget = e.target || e.srcElement;

        // find root element of slide
        var clickedListItem = closest(eTarget, function(el) {
          return (el.tagName && el.tagName.toUpperCase() === 'FIGURE');
        });

        if(!clickedListItem) {
          return;
        }

        // find index of clicked item by looping through all child nodes
        // alternatively, you may define index via data- attribute
        var clickedGallery = clickedListItem.parentNode,
            childNodes = $(clickedListItem.parentNode).children(':not(.isotope-hidden)').get(),
            numChildNodes = childNodes.length,
            nodeIndex = 0,
            index;

        for (var i = 0; i < numChildNodes; i++) {
          if(childNodes[i].nodeType !== 1) { 
            continue; 
          }

          if(childNodes[i] === clickedListItem) {
            index = nodeIndex;
            break;
          }
          nodeIndex++;
        }



        if(index >= 0) {
          // open PhotoSwipe if valid index found
          var id = eTarget.getAttribute('title');
          hash = window.location.hash;
          setHash( 'img=' + encodeURIComponent(id) );
          openPhotoSwipe( index, clickedGallery );
        }
        return false;
      };

      // parse custom imag eid #img=imagetitle
      var photoswipeParseHash = function() {
        var hash = window.location.hash.substring(1),
            params = {};

        if(hash.length < 5) {
          return params;
        }

        var pair = hash.split('=');
        if (pair.length === 2){
          params[pair[0]] = pair[1];
        }
        
        return params;
      };

      var openPhotoSwipe = function(index, galleryElement, disableAnimation) {
        var pswpElement = document.querySelectorAll('.pswp')[0],
            gallery,
            options,
            items;

        items = parseThumbnailElements(galleryElement);

        // define options (if needed)
        options = {
          index: index,
          history: false,

          // define gallery index (for URL)
          galleryUID: galleryElement.getAttribute('data-pswp-uid'),

          getThumbBoundsFn: function(index) {
            // See Options -> getThumbBoundsFn section of documentation for more info
            var thumbnail = items[index].el.getElementsByTagName('img')[0], // find thumbnail
                pageYScroll = window.pageYOffset || document.documentElement.scrollTop,
                rect = thumbnail.getBoundingClientRect(); 

            return {x:rect.left, y:rect.top + pageYScroll, w:rect.width};
          }

        };

        if(disableAnimation) {
          options.showAnimationDuration = 0;
        }

        // Pass data to PhotoSwipe and initialize it
        gallery = new PhotoSwipe( pswpElement, PhotoSwipeUI_Default, items, options);
        gallery.listen('destroy',function() { 
          setHash( hash );
        });
        gallery.listen('afterChange',function(){
          var id = $(this.items[ this.getCurrentIndex() ].el).find('a img').attr('title');
          setHash( 'img=' + encodeURIComponent(id) );
        });
        gallery.init();
      };

      // loop through all gallery elements and bind events
      var galleryElements = document.querySelectorAll( gallerySelector );

      for(var i = 0, l = galleryElements.length; i < l; i++) {
        galleryElements[i].setAttribute('data-pswp-uid', i+1);
        galleryElements[i].onclick = onThumbnailsClick;
      }

      // Parse URL and open gallery if it contains #&pid=3&gid=1
      var hashData = photoswipeParseHash();
      if(hashData.hasOwnProperty('img')) {
        var gallery = $(galleryElements[0]),
            figure = gallery.find('img[title="'+ hashData['img'] +'"]').closest('figure'),
            index = gallery.children('figure').index(figure);
            
        openPhotoSwipe( index,  galleryElements[0], true );
      }
    };

    // execute above function
    initPhotoSwipeFromDOM('.gallery_container');
    $('.isotope').imagesLoaded().progress( function() {
      $('.isotope').isotope('layout');
    });

});
</script>



<?php get_footer(); ?>

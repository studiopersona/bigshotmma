import jQuery from 'jquery';

var Carousel = (function($, w, undefined) {

    var instance;

    var init = function(options) {
        var o = {
            $carouselHolder: {},
            $itemsWrap: {},
            $items: {},
            $navBtnsWrap: {},
            marginSize: 15, // in pixels
            itemsPerFrame: 4,
            heightToWidthRatio: 1,
        },
        itemWidth,
        itemsWrapWidth,
        numberOfItems,
        startPos,
        $leftBtn,
        $rightBtn,
        distanceToMove;

        $.extend(o, options);

        numberOfItems = o.$items.length;
        if ( numberOfItems < o.itemsPerFrame ) o.itemsPerFrame = numberOfItems;
        itemWidth = ( (o.$carouselHolder.outerWidth() / o.itemsPerFrame) - o.marginSize ) + ( o.marginSize /(o.itemsPerFrame + 1 ) );
        itemsWrapWidth = Math.ceil((itemWidth * numberOfItems) + (( numberOfItems -1 ) * o.marginSize));
        // console.log(o.$itemsWrap);
        // set the width of the carousel wrapper
        o.$itemsWrap.width(  itemsWrapWidth + 'px');

        o.$items.each(function(i, el) {
            $(this).outerWidth( itemWidth + 'px');
            $(this).outerHeight( $(this).children('.powerupsCarousel__itemButton').outerHeight() + 'px' );
            if ( i < numberOfItems - 1 ) {
                $(this).css('margin-right', o.marginSize);
            }
        });

        // set the height of the navigation wrapper to the height of the carousel items
        o.$navBtnsWrap.outerHeight($('.carousel__holder').outerHeight() + 'px');
        $('.carousel').outerHeight($('.carousel__holder').outerHeight() + 'px');

        startPos = o.$itemsWrap.offset().left;
        distanceToMove = (o.itemsPerFrame * itemWidth) + ((o.itemsPerFrame) * o.marginSize);

        $leftBtn = o.$navBtnsWrap.children('.carousel-nav').filter('[data-direction="forward"]');
        $rightBtn = o.$navBtnsWrap.children('.carousel-nav').filter('[data-direction="backward"]');
        console.log(o.$navBtnsWrap);
        console.log($rightBtn);
        // if all the items fit in one frame hide the navigation buttons
        if ( numberOfItems <= o.itemsPerFrame ) {
            o.$navBtnsWrap.addClass('navBtn--hide');
        }

        var determineDirection = function(e) {
            var direction = $(e.currentTarget).data('direction'),
                currentPos = o.$itemsWrap.offset().left;

           	// console.log(direction);

            e.preventDefault();

            if ( direction === 'forward' ) {
                changeFrame((currentPos - distanceToMove) - startPos);
            } else {
                changeFrame((currentPos + distanceToMove) - startPos);
            }
        };

        var changeFrame = function(moveTo) {
            moveTo = checkLimits(moveTo);

            o.$itemsWrap.css({
                'left': moveTo + 'px'
            });
        };

        var checkLimits = function(position) {
            var moveTo;

            if ( position > 0 ) {
                moveTo = 0;
                toggleRightNavBtn(false);
                toggleLeftNavBtn(true);
            } else if ( position <= -(itemsWrapWidth - o.$carouselHolder.width() - startPos)) {
                moveTo = -(itemsWrapWidth - o.$carouselHolder.width());
                toggleLeftNavBtn(false);
                toggleRightNavBtn(true);
            } else {
                moveTo = position;
                toggleLeftNavBtn(true);
                toggleRightNavBtn(true);
            }

            return moveTo;
        };

        var toggleLeftNavBtn = function(turnOn) {
            if ( turnOn ) {
                if ( $leftBtn.hasClass('disabled') ) {
                    $leftBtn.removeClass('disabled');
                }
            } else {
                if ( ! $leftBtn.hasClass('disabled') ) {
                    $leftBtn.addClass('disabled');
                }
            }
        };

        var toggleRightNavBtn = function(turnOn) {
            if ( turnOn ) {
                if ( $rightBtn.hasClass('disabled') ) {
                    $rightBtn.removeClass('disabled');
                }
            } else {
                if ( ! $rightBtn.hasClass('disabled') ) {
                    $rightBtn.addClass('disabled');
                }
            }
        };

        return {
            move: determineDirection,
        };

    };

    return {

        // Get the Singleton instance if one exists
        // or create one if it doesn't
        getInstance: function (options) {
          instance = init(options);

          return instance;
        }

    };
})(jQuery, window);

export default Carousel;
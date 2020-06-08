export const legacyApp = {
    ruletId: 1,
    list(id) {
        if($('.container').hasClass('game-start')) {
            return false;
        }

        this.ruletId = id;

        $('.rulet-list li').removeClass('active');
        $('.rulet-list li:nth-child('+id+')').addClass('active');

        let $elPrice = $("#boxPrice");
        $elPrice.html(
            `${_.round($elPrice.data("raw") * this.ruletId)}Р`
        );

        if(id == 1) {
            $('.rulet-1').fadeIn(500);
            $('.rulet-2, .rulet-3').fadeOut(500);
        }

        if(id == 2) {
            $('.rulet-1, .rulet-2').fadeIn(500);
            $('.rulet-3').fadeOut(500);
        }

        if(id == 3) {
            $('.rulet-1, .rulet-2, .rulet-3').fadeIn(500);
        }
    },

    rulets() {
        if($('.container').hasClass('game-start')) {
            return false;
        }

        this.clear();

        setTimeout(function() {
            $('.container').addClass('game-start');
            let random_start = 70,
                random_end = 100 - 5,
                allCycles = legacyApp.ruletId,
                array = [];

            for(let i=random_start;i<=random_end;i++){
                array.push(i);
            }

            for(let countCycles=1;countCycles<=allCycles;countCycles++){
                let size = 150 * 95;
                $('.rulet-'+countCycles+' ul').css({'transition': 7+countCycles+'s', 'transform': 'translate3d(-'+size+'px, 0, 0)'});
            }
        }, 100);

        setTimeout(function(){
            $('.container').removeClass('game-start');
        }, (7 + this.ruletId) * 1000);
    },

    clear() {
        $('.rulet ul').css({'transition': '0s', 'transform': 'translate3d(0px, 0, 0)'});
    }
};

$(document).ready(function(){
    $("ul.rulet-list li").click(function(e) {
        legacyApp.list(
            $(this).data('value')
        );
    });

    $('.faq-name').click(function(){
        let faq_id = $(this).attr('data-faq-id');
        if($('.faq-i:nth-child('+faq_id+')').hasClass('active')) {
            $('.faq-i:nth-child('+faq_id+')').removeClass('active');
        } else {
            $('.faq-i:nth-child('+faq_id+')').addClass('active');
        }
        $('.faq-i:nth-child('+faq_id+') .faq-mess').slideToggle();
    });

    for(let i = 1; i <= $('.faq-i').size(); i++){
        $('.faq-i:nth-child('+i+') .faq-name').attr('data-faq-id', i);
    }

    $('.profile-nav').on('click', 'li:not(.active)', function() {
        $(this).addClass('active').siblings().removeClass('active').closest('.container').find('.tab').hide().removeClass('active').eq($(this).index()).fadeIn(500).addClass('active');
    });

    $('.table').wrap('<div class="table-scroll"></div>');
});

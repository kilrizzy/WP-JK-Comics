(function($) {
    $(document).ready(function(){
        $('.comic-selector').change(function(){
            var comicSelection = $(this).val();
            if(comicSelection){
                window.location.href = comicSelection;
            }
        });
    });
})(jQuery);
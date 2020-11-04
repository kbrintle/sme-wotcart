$(function (){
    // Entry every filtering class below.
    var $chkbxFilter_tags =['compareIndividuallyWrappedCoils','compareInnerSpring','compareFoam', 'compareHybrid'];


    // Entry the block element that will be filtered below.
    var $chkbxFilter_blocks = ['section'];

    var $chkbxFilter_all = $('#all');

    //When you click "ALL", the other checkboxes turn off.
    $chkbxFilter_all.click(function() {
        $(".sort").prop('checked',false);
        $chkbxFilter_all.prop('checked',true);
    });

    //The action when the checkboxes is clicked.
    $("#select label input").click(function() {
        $(this).parent().toggleClass("selected");
        console.log(this);

        $.each($chkbxFilter_tags, function() {
            if($('#' + this).is(':checked')) {
                $("#result " + $chkbxFilter_blocks + ":not(." + this + ")").addClass('hidden-not-' + this);
                $chkbxFilter_all.prop('checked',false).parent().removeClass("selected");
            }
            else if($('#' + this).not(':checked')) {
                $("#result " + $chkbxFilter_blocks + ":not(." + this + ")").removeClass('hidden-not-' + this);
            }

        });

        //If all checkboxes is not selected, add class="selected" to "ALL".
        if ($('.sort:checked').length == 0 ){
            $chkbxFilter_all.prop('checked',true).parent().addClass("selected");
            $(".sort").parent().removeClass("selected");
        };
    });
});
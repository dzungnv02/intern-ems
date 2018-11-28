$(function () {
    var tab_headers = $('UL.nav#student_detail');
    var tab_contents = $('DIV.tab-content');

    if (tab_headers.length == 0) return false;

    var tab_col = $(tab_headers).find('LI');

    var tab_activate = (target) => {
        $(tab_col).removeClass('active');
        $(target).parent().addClass('active');
        $(tab_contents).find("DIV[role='tabpanel']").removeClass('active');
        $(tab_contents).find("DIV[role='tabpanel']#" + $(target).data('tab')).addClass('active');
    }

    $(tab_headers).find('A').bind('click', (e) => {
        tab_activate(e.target);
        e.preventDefault();
        e.stopPropagation();
    });


});
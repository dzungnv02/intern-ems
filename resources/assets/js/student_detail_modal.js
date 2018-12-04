$(function () {
    var modal_branch = $('DIV#branch-select-modal');
    var modal_teacher = $('DIV#teacher-select-modal');
    var modal_classes = $('DIV#classes-select-modal');
    var modal_staff = $('DIV#staff-select-modal');

    $('A#btn_register_branch').on('click', (e) => {
        modal_branch.modal('show');
    });

    $('A#btn_dependent_branch').on('click', (e) => {
        modal_branch.modal('show');
    });

    $('A#btn_staff').on('click', (e) => {
        console.log(modal_staff);
        modal_staff.modal('show');
    });

    $('A#btn_trial_class').on('click', (e) => {
        modal_classes.modal('show');
    });

    $('A#btn_teacher').on('click', (e) => {
        modal_teacher.modal('show');
    });
    
});
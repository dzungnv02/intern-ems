$(function () {
    var change_password_modal = $('DIV.modal#userchangepassword-modal');
    if (change_password_modal.length > 0) {

        var old_passwd = $(change_password_modal).find('DIV.modal-body FORM#frmChangePasswd INPUT#currentPassword');
        var new_passwd = $(change_password_modal).find('DIV.modal-body FORM#frmChangePasswd INPUT#newPassword');
        var new_passwd_confirm = $(change_password_modal).find('DIV.modal-body FORM#frmChangePasswd INPUT#newPasswordConfirm');
        var changeButton = $(change_password_modal).find('DIV.modal-footer BUTTON#btnChangePassword');

        var check_length = (str) => {
            if (str.length < 8) {
                return false;
            }
            else {
                return true;
            }
        }

        var validate = () => {
            var isValid = true;

            if ($(old_passwd).val().length === 0) {
                $(old_passwd).parent().addClass('has-error');
                $(old_passwd).parent().find('SPAN.help-block').text('Hãy nhập mật khẩu đang sử dụng!').show();
                isValid = false;
            }
            else {
                $(old_passwd).parent().removeClass('has-error');
                $(old_passwd).parent().find('SPAN.help-block').text('').hide();
            }

            if ($(new_passwd).val().length === 0) {
                $(new_passwd).parent().addClass('has-error');
                $(new_passwd).parent().find('SPAN.help-block').text('Hãy nhập mật khẩu mới!').show();
                isValid = false;
            }
            else if (!check_length($(new_passwd).val())) {
                $(new_passwd).parent().addClass('has-error');
                $(new_passwd).parent().find('SPAN.help-block').text('Mật khẩu mới phải có ít nhất 8 ký tự!').show();
                isValid = false;
            }
            else {
                $(new_passwd).parent().removeClass('has-error');
                $(new_passwd).parent().find('SPAN.help-block').text('').hide();
            }

            if ($(new_passwd).val().trim() !== $(new_passwd_confirm).val().trim()) {
                $(new_passwd_confirm).parent().addClass('has-error');
                $(new_passwd_confirm).parent().find('SPAN.help-block').text('Xác nhận mật khẩu không đúng!').show();
                isValid = false;
            }
            else {
                $(new_passwd_confirm).parent().removeClass('has-error');
                $(new_passwd_confirm).parent().find('SPAN.help-block').text('').hide();
            }

            return isValid;
        }

        var save_change_password = (data) => {
            $.ajax('/change-password', {
                method: 'POST',
                data: JSON.stringify(data),
                contentType: 'application/json',
                success: (response) => {
                    $(change_password_modal).find('DIV.modal-footer BUTTON#btnClose').click();
                    $(changeButton).button('reset');
                },
            });
        }

        $(changeButton).on('click', (e) => {
            $(e.target).button('loading');
            if (validate()) {
                var passwd  = $(old_passwd).val();
                $.ajax('/verify-password/'+ btoa(passwd), {
                    method: 'GET',
                    contentType: 'application/json',
                    success: (response) => {
                        if (response.matched) {
                            var data = {id: $('meta[name="user-id"]').attr('content'),'newPassword': $(new_passwd).val().trim()};
                            save_change_password(data);
                        }
                        else {
                            $(old_passwd).parent().addClass('has-error');
                            $(old_passwd).parent().find('SPAN.help-block').text('Mật khẩu không đúng!').show();
                            $(changeButton).button('reset');
                        }
                    },
                });
            }
            else {
                $(e.target).button('reset');
            }
        });

        $(change_password_modal).on('hide.bs.modal', (e) => {
            $(change_password_modal).find('DIV.modal-body FORM#frmChangePasswd')[0].reset();
        })



    }
});
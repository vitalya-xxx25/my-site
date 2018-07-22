;(function($) {
    var rolesAddEdit = (function(){
        var $this,
            $form = $('form.user-active-form'),
            userId = parseInt($('form.user-active-form').data('user_id'));

        function _setRole(params, callback) {
            $.post("/user/set-role", params)
                .done(function(data) {
                    if ((data.success||0)) {
                        callback();
                    }
                });
        }

        function _initHandlers() {
            $('.add-role-btn', $form).on('click', function() {
                var $thisBtn = $(this);

                _setRole({
                    action: 'add',
                    roleId: $thisBtn.closest('li').data('id'),
                    userId: userId
                }, function() {
                    var $rolesListGroup = $thisBtn.closest('.roles-list-group');
                    $rolesListGroup.find('.remove-role-btn').addClass('hidden');
                    $rolesListGroup.find('.add-role-btn').removeClass('hidden');

                    $thisBtn.closest('li').find('.remove-role-btn').removeClass('hidden');
                    $thisBtn.addClass('hidden');
                });
            });

            $('.remove-role-btn', $form).on('click', function() {
                var $thisBtn = $(this);

                _setRole({
                    action: 'remove',
                    roleId: $thisBtn.closest('li').data('id'),
                    userId: userId
                }, function() {
                    $thisBtn.closest('li').find('.add-role-btn').removeClass('hidden');
                    $thisBtn.addClass('hidden');
                });
            });
        }

        return {
            init: function() {
                $this = this;
                _initHandlers();
            }
        };
    })();

    $(document).ready(function(){
        rolesAddEdit.init();
    });
})(jQuery);
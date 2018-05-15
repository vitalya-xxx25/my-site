;(function($) {
    var permissionsAddEdit = (function(){
        var $this,
            $form = $('form.permissions-active-form'),
            permissionId = parseInt($('form.permissions-active-form').data('permis_id'));

        function _setRole(params, callback) {
            $.post("/permissions/set-role", params)
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
                    permissionId: permissionId
                }, function() {
                    $thisBtn.closest('li').find('.remove-role-btn').removeClass('hidden');
                    $thisBtn.addClass('hidden');
                });
            });

            $('.remove-role-btn', $form).on('click', function() {
                var $thisBtn = $(this);

                _setRole({
                    action: 'remove',
                    roleId: $thisBtn.closest('li').data('id'),
                    permissionId: permissionId
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
        permissionsAddEdit.init();
    });
})(jQuery);
<?php 
if (isset ($isAuth)) {
?>
<script>
! function (e) {
    jQuery.sessionTimeout = function (t) {
        function n(t) {
            switch (t) {
            case "start":
                i = setTimeout(function () {
                    e("#sessionTimeout-dialog").dialog("open"), r("start")
                }, u.warnAfter);
                break;
            case "stop":
                clearTimeout(i)
            }
        }

        function r(e) {
            switch (e) {
            case "start":
                s = setTimeout(function () {
                    window.location = u.redirUrl
                }, u.redirAfter - u.warnAfter);
                break;
            case "stop":
                clearTimeout(s)
            }
        }

        var session_timeout = <?php echo KS_Option::getOptionValue('session_timeout')? (int) KS_Option::getOptionValue('session_timeout'):600;?>;
        var session_timeout_before_forcelogout = <?php echo KS_Option::getOptionValue('session_timeout_before_forcelogout')? (int) KS_Option::getOptionValue('session_timeout_before_forcelogout'):120;?>;
        
        var i, s, o = {
                message: "Your session is about to expire after " + (session_timeout/60) + " minutes idle."
                    		+ "<br/>You have " + session_timeout_before_forcelogout + " seconds before force logout.",
                keepAliveUrl: "<?php echo KSCONFIG_URL;?>ks_builtin/keepalive.php",
                redirUrl: "<?php echo KSCONFIG_URL;?>ks_user/logout.php",
                logoutUrl: "<?php echo KSCONFIG_URL;?>ks_user/logout.php",
                warnAfter: 9e5,
                redirAfter: 12e5
            }, u = o;
        t && (u = e.extend(o, t)), e("body").append('<div title="Session Timeout" id="sessionTimeout-dialog">' + u.message + "</div>"), e("#sessionTimeout-dialog").dialog({
            autoOpen: !1,
            width: 400,
            modal: !0,
            closeOnEscape: !1,
            open: function () {
                e(".ui-dialog-titlebar-close").hide()
            },
            buttons: {
                "Log Out Now": function () {
                    window.location = u.logoutUrl
                },
                "Stay Connected": function () {
                    e(this).dialog("close"), e.ajax({
                        type: "POST",
                        url: u.keepAliveUrl
                    }), r("stop"), n("start")
                }
            }
        }), n("start")
    }
}(jQuery);

$(document).ready(function() {
    $.sessionTimeout({
        warnAfter: (<?php echo KS_Option::getOptionValue('session_timeout')?KS_Option::getOptionValue('session_timeout'):600;?> * 1000), 
        redirAfter: ((<?php echo KS_Option::getOptionValue('session_timeout')?KS_Option::getOptionValue('session_timeout'):600;?> + <?php echo KS_Option::getOptionValue('session_timeout_before_forcelogout')?KS_Option::getOptionValue('session_timeout_before_forcelogout'):120;?>) * 1000)
    });
});
</script>
<?php
}
?>
<h5 class="text-center">&copy; <?php echo date("Y");?> <?php echo KSCONFIG_SYSTEM_NAME;?>.</h5>
</body>
</html>
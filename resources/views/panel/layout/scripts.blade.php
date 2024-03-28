<!-- Libs JS -->
<script src="/assets/libs/apexcharts/dist/apexcharts.min.js" defer></script>
<script src="/assets/libs/jsvectormap/dist/js/jsvectormap.min.js" defer></script>
<script src="/assets/libs/jsvectormap/dist/maps/world.js" defer></script>
<script src="/assets/libs/jsvectormap/dist/maps/world-merc.js" defer></script>
<!-- Tabler Core -->
<script src="/assets/js/tabler.min.js" defer></script>
<script src="/assets/js/opai.min.js" defer></script>

<!-- AJAX CALLS -->
<script src="/assets/openai/js/jquery.js"></script>
<script src="/assets/openai/js/main.js"></script>
<script src="/assets/openai/js/toastr.min.js"></script>
<script src="/assets/libs/tom-select/dist/js/tom-select.base.min.js?1674944402" defer></script>

<script>
    var magicai_localize = {
        words: @json(__('global.Words')),
        images: @json(__('global.Images')),
        signup: @json(__('global.Sign Up')),
        save: @json(__('global.Save')),
        fname: @json(__('global.Error updating folder name')),
        saved: @json(__('global.Saved')),
        send: @json(__('global.Send')),
        requestsent: @json(__('global.Request Sent Succesfully')),
        please_input_topic: @json(__('global.Please input topic')),
        invitesent: @json(__('global.Invitation Sent Succesfully')),
        errorsend: @json(__('global.Error while sending information. Please contact us.')),
        generate: @json(__('global.Generate')),
        generated: @json(__('global.Generated')),
        please_wait: @json(__('global.Please Wait...')),
        sign_in: @json(__('Sign in')),
        login_redirect: @json(__('global.Login Successful, Redirecting...')),
        register_redirect: @json(__('global.Registration is complete. Redirecting...')),
        password_reset_link: @json(__('global.Password reset link sent succesfully. Please also check your spam folder.')),
        password_reset_done: @json(__('Password succesfully changed.')),
        password_reset: @json(__('Reset Password')),
        missing_email: @json(__('global.Please enter your email address.')),
        missing_password: @json(__('global.Please enter your password.')),
        content_copied_to_clipboard: @json(__('Content copied to clipboard.')),
        cannotwithdraw: @json(__('global.You cannot withdrawal with this amount. Please check')),
    }
</script>


<!-- PAGES JS-->
@guest()
<script src="/assets/js/panel/login_register.js"></script>
@endguest
<script src="/assets/js/panel/search.js"></script>

<script src="/assets/libs/list.js/dist/list.js" defer></script>



@if(isset($user_email_activated) && !$user_email_activated)
<div class="error">
    Caution<br/>
    You e-mail address has not been activated yet!
    Please go to your <a href="{{url('/settings')}}"> settings</a> and activate your
    e-mail address!
    @if($user_email_activation_expire - time() - 60*60*24*2 > 0)
    <br/><br/>
    If you activate your e-mail address within one day, you`ll receive a gift! Remaining time:
    <span id="email_valid_countdown"></span> h
    <script type="text/javascript">
        $(function () {
            $("#email_valid_countdown").countdown({
                until: +{{$user_email_activation_expire - time() - 60*60*24*2}}, compact: true, compactLabels: ['y', 'm', 'w', 'd'],
                description: ''
            });
        });
    </script>
    @endif
</div>
@endif
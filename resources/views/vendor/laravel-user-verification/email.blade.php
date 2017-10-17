Hi,
<br />
<br />
Welcome to SafePass!
<br />
<br />
Click here to verify your account: <a href="{{ $link = route('email-verification.check', $user->verification_token) . '?email=' . urlencode($user->email) }}">{{ $link }}</a>
<br />
<br />
If clicking above link does not work, copy and paste the url into a new browser window.
<br />
<br />
This is a post-only mailing. Replies to this message are not monitored or considered.
<br />
<br />
Regards,
<br />
SafePass Bot.

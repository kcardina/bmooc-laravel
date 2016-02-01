<h2>Sign in</h2>
<p>Using bMOOC for the first time? {!! HTML::link('auth/register','Create an account.', ['class'=>'emphasis', 'data-reveal-id'=>'signup', 'data-reveal-ajax'=>'true']) !!}</p>

{!! Form::open(array('data-abide', 'url'=>'/auth/login','method'=>'POST')) !!}
    <label>Email:
        <input type="email" required name="email" value="{{ old('email') }}">
    </label>
    <small class="error">Please enter a valid e-mail address.</small>

    <label>Password:
        <input type="password" required name="password" id="password">
    </label>
    <small class="error">Please enter your password.</small>

    <label>Remember me:
        <input type="checkbox" name="remember">
    </label>

    <input type="submit" class="full purple" value="Login" />
{!! Form::close() !!}

<a class="close-reveal-modal" aria-label="Close">&#215;</a>

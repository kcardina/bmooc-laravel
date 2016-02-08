<h2>Create an account</h2>
{!! Form::open(array('data-abide', 'url'=>'/auth/register','method'=>'POST')) !!}

<div>
    <label>
        Name:
        <input type="text" required name="name" value="{{ old('name') }}">
    </label>
    <small class="error">Please enter your name.</small>
</div>
<div>
    <label>
        Email:
        <input type="email" required name="email" value="{{ old('email') }}">
    </label>
    <small class="error">Please enter a valid e-mail address.</small>
</div>
<div>
    <label>
        Password:
        <input type="password" minlength="6" id="pwd" required name="password" pattern="^(.){6,}$">
    </label>
    <small class="error">Please enter a password (at least 6 characters).</small>
</div>
<div>
    <label>
        Confirm Password:
        <input type="password" required data-equalto="pwd" name="password_confirmation">
    </label>
    <small class="error">The passwords do not match.</small>
</div>
    <input type="submit" class="full purple" value="Create account" />

{!! Form::close() !!}

<p><small>Problems creating an account? {!! HTML::link('#', 'Send us a message', array('class'=>'emphasis', 'data-reveal-id' => 'feedback')) !!}.</small></p>

<a class="close-reveal-modal" aria-label="Close">&#215;</a>

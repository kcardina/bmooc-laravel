<h2>Create an account</h2>
{!! Form::open(array('data-abide', 'url'=>'/auth/register','method'=>'POST')) !!}

    <label>
        Name:
        <input type="text" required name="name" value="{{ old('name') }}">
    </label>
<br />
    <label>
        Email:
        <input type="email" required name="email" value="{{ old('email') }}">
    </label>
<br />
    <label>
        Password:
        <input type="password" required name="password">
    </label>
<br />
    <label>
        Confirm Password:
        <input type="password" required name="password_confirmation">
    </label>
<br />
    <input type="submit" class="full purple" value="Create account" />

{!! Form::close() !!}

<a class="close-reveal-modal" aria-label="Close">&#215;</a>

{!! Form::open(array('data-abide', 'url'=>'feedback','method'=>'POST', 'files'=>true)) !!}
        <small class="mailstatus error full"></small>
        <label for="fb_name">Name:
            <input type="text" id="fb_name" name="fb_name"/>
        </label>
        <label for="fb_mail">E-mail:
            <input type="email" id="fb_mail" name="fb_mail"/>
            <small class="error">Please enter a valid e-mail address.</small>
        </label>
        <label for="fb_msg">Message:
            <textarea required rows="5" id="fb_msg"></textarea>
            <small class="error">Please describe your remark, problem or suggestion.</small>
        </label>
        <input type="submit" class="purple full" value="Submit"/>
{!! Form::close() !!}

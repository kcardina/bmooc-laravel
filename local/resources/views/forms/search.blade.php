<form class="sort">
    <div class="row">
      {{--
       <div class="medium-4 columns form-inline">
        <label for="auteurs">Authors</label>
           <span class="field">
             <select name="auteurs" id="auteurs">
              <option value ="all">All</option>
                <option disabled>──────────</option>
                @foreach ($auteurs as $auteur)
                  @if(isset($search))
                       @if($auteur->id == $search['author'])
                        <option value="{{ $auteur->id }}" selected>{{ $auteur->name }}</option>
                        @else
                        <option value="{{ $auteur->id }}">{{ $auteur->name }}</option>
                        @endif
                    @else
                    <option value="{{ $auteur->id }}">{{ $auteur->name }}</option>
                    @endif
                @endforeach
               </select>
           </span>
       </div>
       --}}

       <div class="medium-4 columns">
          <div class="form-inline">
               <label for="author">Authors</label>
               <div class="field">
                   <div class="awesomplete">
                    <input class="dropdown-input" autocomplete="off" aria-autocomplete="list" id="author" list="authors-list" />
                   </div>
               </div>
               <datalist id="authors-list">
                    @foreach ($authors as $author)
                        <option>{{ $author->name }}</option>
                    @endforeach
                </datalist>
                <button class="dropdown-btn form-input" type="button">&darr;</button>
           </div>
       </div>

       <div class="medium-4 columns">
          <div class="form-inline">
               <label for="tag">Tags</label>
               <div class="field">
                   <div class="awesomplete">
                    <input class="dropdown-input" autocomplete="off" aria-autocomplete="list" id="tag" list="tags-list" />
                   </div>
               </div>
               <datalist id="tags-list">
                    @foreach ($tags as $tag)
                        <option>{{ $tag->tag }}</option>
                    @endforeach
                </datalist>
                <button class="dropdown-btn form-input" type="button">&darr;</button>
           </div>
       </div>

       <div class="medium-4 columns">
          <div class="form-inline">
               <label for="zoek">Search</label>
               <div class="field">
                @if(isset($search))
                    <input type="text" id="keyword" value="{{ $search['keyword'] }}"/>
                @else
                    <input type="text" id="keyword" />
                @endif
               </div>
           </div>
       </div>
    </div>
</form>

<script src="js/awesomplete.min.js" async onload="awesomplete_init();"></script>
<script>
    function awesomplete_init(){
        $('input.dropdown-input').each(function(){
            var comboplete = new Awesomplete('#' + $(this).attr('id'), {
               minChars: 0
            });
            var el = $(this).parents('.form-inline').children('button')[0];
            el.addEventListener("click", function(e) {
                if (comboplete.ul.childNodes.length === 0) {
                    comboplete.evaluate();
                }
                else if (comboplete.ul.hasAttribute('hidden')) {
                    comboplete.open();
                }
                else {
                    comboplete.close();
                }
            });
            $(this).on('awesomplete-open', function(){
                $(el).html('&uarr;');
            });
            $(this).on('awesomplete-close',function(){
                $(el).html("&darr;");
            });
            $(this).on('awesomplete-selectcomplete', function(){
                $(".sort").submit();
            });
        });
    }

    $(".sort input").keypress(function(event) {
        if (event.which == 13) {
            event.preventDefault();
            $(".sort").submit();
        }
    });

    $(".sort").submit(function(e){
        e.preventDefault();
        search();
    });

    function search(){
        var author = $(".sort input#author").val();
        if (author.trim() == "") author = "all";
        var tag = $(".sort input#tag").val();
        if (tag.trim() == "") tag = "all";
        var keyword = $(".sort input#keyword").val();
        window.location = "{{ URL::to('/') }}" + '/search/' + author + '/' + tag + (keyword?'/' + keyword:'');
    }
</script>

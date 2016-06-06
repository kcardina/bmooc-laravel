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
               <label for="tags">Authors</label>
               <div class="field">
                   <div class="awesomplete">
                    <input class="dropdown-input" autocomplete="off" aria-autocomplete="list" id="tags" list="tags-list" />
                   </div>
               </div>
               <datalist id="tags-list">
                    @foreach ($auteurs as $auteur)
                        <option>{{ $auteur->name }}</option>
                    @endforeach
                </datalist>
                <button class="dropdown-btn form-input" type="button">&#9660;</button>
           </div>
       </div>

       <div class="medium-4 columns">
          <div class="form-inline">
               <label for="tags">Tags</label>
               <div class="field">
                   <div class="awesomplete">
                    <input class="dropdown-input" autocomplete="off" aria-autocomplete="list" id="tags" list="tags-list" />
                   </div>
               </div>
               <datalist id="tags-list">
                    @foreach ($tags as $tag)
                        <option>{{ $tag->tag }}</option>
                    @endforeach
                </datalist>
                <button class="dropdown-btn form-input" type="button">&#9660;</button>
           </div>
       </div>

       <div class="medium-4 columns">
          <div class="form-inline">
               <label for="zoek">Search</label>
               <div class="field">
                @if(isset($search))
                    <input type="text" id="zoek" value="{{ $search['keyword'] }}"/>
                @else
                    <input type="text" id="zoek" />
                @endif
               </div>
           </div>
       </div>
    </div>
</form>
<script src="js/awesomplete.min.js" async onload="awesomeplete_init();"></script>
<script>
    function awesomeplete_init(){
        var comboplete = new Awesomplete('input.dropdown-input', {
           minChars: 0,
        });
        Awesomplete.$('.dropdown-btn').addEventListener("click", function(e) {
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
    }
</script>

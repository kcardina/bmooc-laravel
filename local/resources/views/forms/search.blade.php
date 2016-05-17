<form class="sort">
    <div class="row">
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
       <div class="medium-4 columns form-inline">
           <label for="tags">Tags</label>
           <span class="field">
           <select name="tags" id="tags">
              <option value="all">All</option>
                <option disabled>──────────</option>
                @foreach ($tags as $tag)
                  @if(isset($search))
                       @if($tag->id == $search['tag'])
                        <option value="{{ $tag->id }}" selected>{{ $tag->tag }}</option>
                        @else
                        <option value="{{ $tag->id }}">{{ $tag->tag }}</option>
                        @endif
                    @else
                    <option value="{{ $tag->id }}">{{ $tag->tag }}</option>
                    @endif
                @endforeach
                                       </select>
           </span>
       </div>
       <div class="medium-4 columns form-inline">
           <label for="zoek">Search</label>
           <span class="field">
            @if(isset($search))
                <input type="text" id="zoek" value="{{ $search['keyword'] }}"/>
            @else
                <input type="text" id="zoek" />
            @endif
           </span>
       </div>
    </div>
</form>

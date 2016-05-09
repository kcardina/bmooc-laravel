<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>bMOOC | @yield('title')</title>

    {!! HTML::style('css/foundation.css') !!}
    {!! HTML::style('css/admin.css') !!}
    {!! HTML::script('js/vendor/modernizr.js') !!}
    {!! HTML::style('https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css') !!}
</head>
<body>

   <header>
      <div class="row">
          <div class="columns">
              <h1>bMOOC</h1>
               <nav>
                  <div class="icon-bar two-up">
                      <a href="/admin/data" class="item {{ Menu::active('data') }}">
                        <i title="video" class="fa fa-area-chart"></i>
                        <label>Data</label>
                      </a>
                      <a href="/admin/actions" class="item {{ Menu::active('actions') }}">
                        <i title="video" class="fa fa-cog"></i>
                        <label>Actions</label>
                      </a>
                    </div>
               </nav>
          </div>
      </div>
   </header>

   <div class="row">
       <div class="columns">
           <nav class="inline slash center">
               <ul>
                   @yield('nav_secondary')
                </ul>
           </nav>
       </div>
   </div>

    <div class="container">
            @yield('content')
    </div>

    {!! HTML::script('js/vendor/jquery.js') !!}
    <script>
        var host = "{{ URL::to('/') }}";
    </script>
    @yield('scripts')
</body>
</html>

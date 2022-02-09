{{--
    WebframeworkDB (dnyWebFwDb) developed by Daniel Brendel

    (C) 2022 by Daniel Brendel

    Contact: dbrendel1988<at>gmail<dot>com
    GitHub: https://github.com/danielbrendel/

    Released under the MIT license
--}}

<div class="about">
    <div class="columns">
        <div class="column is-2"></div>

        <div class="column is-8">
            {!! \App\Models\AppModel::getAbout() !!}
        </div>
    
        <div class="column is-2"></div>
    </div>
</div>
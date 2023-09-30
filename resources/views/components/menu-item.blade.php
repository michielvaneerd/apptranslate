<li class="nav-item">
    <a href="{{ $item['route'] }}" class="nav-link @if(my_app_is_in_current_url($currentUrl, $item['route'])) active @endif">
        <i class="far fa-circle nav-icon"></i>
        <p>
            {{ $item['title'] }}
            @if(!empty($item['items']))<i class="fas fa-angle-left right"></i>@endif
        </p>
    </a>
    @if(!empty($item['items']))
    <x-menu :items="$item['items']"></x-menu>
    @endif
</li>
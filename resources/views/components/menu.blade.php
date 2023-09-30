<ul class="nav @if(!empty($root)) nav-pills nav-sidebar flex-column @else nav-treeview @endif" @if(!empty($root)) data-widget="treeview" role="menu" @endif>
    @foreach($items as $item)
    <x-menu-item :item="$item" :currentUrl="$currentUrl"></x-menu-item>
    @endforeach
</ul>
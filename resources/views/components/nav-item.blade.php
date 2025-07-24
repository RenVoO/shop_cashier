@props(['title', 'icon', 'routes'])
<li class="nav-item">
    <a href="{{ route($routes[0]) }}" 
    class="nav-link {{ in_array(request()->route()->getName(),$routes)? 'active': '' }}">
    <i class="nav-icon {{ $icon }}"></i>
    <p><?= $title ?></p>
    </a>
</li>
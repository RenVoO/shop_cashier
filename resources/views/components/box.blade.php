@props(['title', 'jumlah', 'route', 'icon', 'background'])

<div class="col-lg-3 col-6">
    <div class="small-box {{ $background }}">
        <div class="inner">
            <h3>{{ $jumlah }}</h3>
            <p>{{ $title }}</p>
        </div>
        <div class="icon">
            <i class="{{ $icon }}"></i>
        </div>
        <a href="{{ $route }}" class="small-box-footer">
            More info <i class="fas fa-arrow-circle-right"></i>
        </a>
    </div>
</div>

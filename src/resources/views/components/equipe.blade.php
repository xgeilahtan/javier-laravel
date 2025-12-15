{{-- resources/views/components/equipe.blade.php --}}
<div class="staff-member card">
    <div class="staff-image">
        <img src="{{ $image }}" class="card-img-top img-card-prof" alt="{{ $alt }}">
        <div class="staff-overlay">
            <p>{{ $specialty }}</p>
        </div>
    </div>
    <div class="staff-info card-body">
        <h5 class="card-title">{{ $name }}</h5>
    </div>
</div>
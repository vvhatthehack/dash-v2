<!--begin:: Avatar -->
<div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
    <a href="{{ route('user-management.users.show', $user) }}">
        @if($user->profile_photo_path)
            <div class="symbol symbol-label">
                <img alt="Logo" src="{{ asset('/' . Auth::user()->profile_photo_path) }}" />
            </div>
        @else
            <div class="symbol-label fs-3 {{ app(\App\Actions\GetThemeType::class)->handle('bg-light-? text-?', $user->name) }}">
                {{ substr($user->name, 0, 1) }}
            </div>
        @endif
    </a>
</div>
<!--end::Avatar-->
<!--begin::User details-->
<div class="d-flex flex-column">
    <a href="{{ route('user-management.users.show', $user) }}" class="text-gray-800 text-hover-primary mb-1">
        {{ $user->name }}
    </a>
    <span>{{ $user->email }}</span>
</div>
<!--begin::User details-->

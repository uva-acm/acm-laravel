@extends('layouts.app')

@section('title', $user->first_name . ' ' . $user->last_name . ' - ACM @ UVA')

@section('content')
    <div class="row">
        <div class="col-md-4 not-prose">
            <div class="bg-base-100 rounded-lg p-6 shadow-sm border border-base-300">
                <div class="text-center">
                    @if($user->image)
                        <img src="{{ Storage::url($user->image) }}" class="w-32 h-32 md:w-40 md:h-40 rounded-full object-cover mx-auto mb-4" alt="{{ $user->first_name }}">
                    @else
                        <div class="w-32 h-32 md:w-40 md:h-40 bg-secondary rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-secondary-content text-2xl md:text-3xl font-bold">
                                {{ strtoupper(substr($user->first_name, 0, 1) . substr($user->last_name, 0, 1)) }}
                            </span>
                        </div>
                    @endif
                    
                    <h2 class="text-xl md:text-2xl font-bold mb-2">{{ $user->first_name }} {{ $user->last_name }}</h2>
                    <p class="text-base-content/70 mb-4 flex items-center justify-center gap-2">
                        {{ $user->username }}
                        <a href="mailto:{{ $user->email }}" class="text-sm hover:text-primary transition-colors">
                            <i class="fa fa-envelope"></i>
                        </a>
                    </p>
                    
                    @if($user->bio)
                        <div class="mb-4">
                            <p class="text-sm leading-relaxed">{{ $user->bio }}</p>
                        </div>
                    @endif
                    
                    @if($badges->count() > 0)
                        <div class="mb-4">
                            <h3 class="font-semibold text-sm uppercase tracking-wide text-base-content/60 mb-2">Badges</h3>
                            <div class="flex flex-wrap gap-2 justify-center">
                                @foreach($badges as $badge)
                                    <x-badge :badge="$badge" />
                                @endforeach
                            </div>
                        </div>
                    @endif
                    
                    @auth
                        @if(auth()->user()->can('update', $user))
                            <button class="btn btn-primary btn-sm md:btn-md" onclick="document.getElementById('editProfileModal').showModal()">
                                Edit Profile
                            </button>
                        @endif
                    @endauth
                </div>
            </div>
        </div>

        <div class="col-md-8">
            @auth
                <h3>Events Attended ({{ $events_attended->count() }})</h3>
                @if($events_attended->count() > 0)
                    <div class="flex flex-wrap gap-8 p-8 bg-base-200 rounded-box not-prose">
                        @foreach($events_attended as $event)
                            @include('profile_event_card', ['event' => $event])
                        @endforeach
                    </div>
                @else
                    <p class="text-muted">No events attended yet.</p>
                @endif
            @endauth
            @guest
                <h3>Events Attended</h3>
                <p><a href="{{ route('login_page') }}?next={{ request()->path() }}">Log in</a> to view the events this user has attended!</p>
            @endguest
        </div>
    </div>
    
    @auth
        @if(auth()->check() && auth()->user()->can('update', $user))
            <dialog id="editProfileModal" class="modal not-prose">
                <div class="modal-box max-h-3/4 overflow-y-auto">
                    <h3 class="font-bold text-lg">Edit Profile</h3>
                    <form method="POST" action="{{ route('user.update', $user->username) }}" enctype="multipart/form-data">
                        @csrf
                        <fieldset class="fieldset bg-base-200 border-base-300 rounded-box border p-4 mb-4">
                            <legend class="fieldset-legend">Personal Information</legend>
                            
                            <label class="label">
                                <span class="label-text">Bio (Optional)</span>
                            </label>
                            <textarea name="bio" class="textarea textarea-bordered" rows="4">{{ $user->bio }}</textarea>
                        </fieldset>
                        
                        <fieldset class="fieldset bg-base-200 border-base-300 rounded-box border p-4 mb-4">
                            <legend class="fieldset-legend">Profile Media</legend>
                            
                            <label class="label">
                                <span class="label-text">Profile Image (Optional)</span>
                            </label>
                            <input type="file" name="image" class="file-input file-input-bordered" accept="image/*">
                            <div class="label">
                                <span class="label-text-alt text-base-content/60">Maximum file size: @maxUploadSize</span>
                            </div>
                        </fieldset>
                        
                        <div class="modal-action">
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                            <button type="button" class="btn" onclick="document.getElementById('editProfileModal').close()">Cancel</button>
                        </div>
                    </form>
                </div>
            </dialog>
        @endif
    @endauth
@endsection 
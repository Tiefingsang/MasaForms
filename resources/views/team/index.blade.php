{{-- resources/views/team/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Gestion d\'équipe')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="sm:flex sm:items-center sm:justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Gestion d'équipe</h1>
            <p class="mt-1 text-sm text-gray-600">
                Gérez les membres de votre équipe et leurs accès.
            </p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('team.create') }}"
               class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                <i class="fas fa-user-plus mr-2"></i>
                Inviter un membre
            </a>
        </div>
    </div>

    <!-- Membres actuels -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Membres de l'équipe</h3>
        </div>

        <div class="divide-y divide-gray-200">
            @forelse($teamMembers as $member)
                <div class="px-4 py-4 sm:px-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                <span class="text-gray-600 font-medium">{{ strtoupper(substr($member->name, 0, 1)) }}</span>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-900">{{ $member->name }}</p>
                                <p class="text-sm text-gray-500">{{ $member->email }}</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <select class="text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                    onchange="updateRole({{ $member->id }}, this.value)">
                                <option value="admin" {{ $member->pivot->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="editor" {{ $member->pivot->role == 'editor' ? 'selected' : '' }}>Éditeur</option>
                                <option value="viewer" {{ $member->pivot->role == 'viewer' ? 'selected' : '' }}>Lecteur</option>
                            </select>
                            <button onclick="removeMember({{ $member->id }})"
                                    class="text-red-600 hover:text-red-800">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="px-4 py-8 text-center text-gray-500">
                    <i class="fas fa-users text-3xl mb-2"></i>
                    <p>Aucun membre dans l'équipe pour le moment</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Invitations en attente -->
    @if($pendingInvitations->count() > 0)
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Invitations en attente</h3>
            </div>

            <div class="divide-y divide-gray-200">
                @foreach($pendingInvitations as $invitation)
                    <div class="px-4 py-4 sm:px-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $invitation->email }}</p>
                                <p class="text-sm text-gray-500">
                                    Rôle : {{ $invitation->role }} •
                                    Expire le {{ $invitation->expires_at->format('d/m/Y') }}
                                </p>
                            </div>
                            <form action="{{ route('team.cancel', $invitation) }}" method="POST">
                                @csrf
                                <button type="submit" class="text-red-600 hover:text-red-800">
                                    <i class="fas fa-times"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
function updateRole(userId, role) {
    fetch(`/team/members/${userId}/role`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ role: role })
    }).then(response => {
        if (response.ok) {
            location.reload();
        }
    });
}

function removeMember(userId) {
    if (confirm('Êtes-vous sûr de vouloir retirer ce membre ?')) {
        fetch(`/team/members/${userId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        }).then(response => {
            if (response.ok) {
                location.reload();
            }
        });
    }
}
</script>
@endpush
@endsection

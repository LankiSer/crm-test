<!-- New Chat Modal -->
<div class="modal fade" id="newChatModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">New Conversation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('chat.create') }}" method="POST">
                @csrf
                <input type="hidden" name="is_group" value="0">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="user_id" class="form-label">Select User</label>
                        <select class="form-select" name="user_id" id="user_id" required>
                            <option value="">-- Select a user --</option>
                            @foreach(App\Models\User::where('id', '!=', auth()->id())->pluck('name', 'id') as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="chat_name" class="form-label">Chat Name (Optional)</label>
                        <input type="text" class="form-control" id="chat_name" name="name" placeholder="Leave blank for default naming">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Start Chat</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- New Group Modal -->
<div class="modal fade" id="newGroupModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create Group Chat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('chat.create') }}" method="POST">
                @csrf
                <input type="hidden" name="is_group" value="1">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="group_name" class="form-label">Group Name</label>
                        <input type="text" class="form-control" id="group_name" name="name" placeholder="Enter group name" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description (Optional)</label>
                        <textarea class="form-control" id="description" name="description" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Select Members</label>
                        <div class="border p-2 rounded" style="max-height: 200px; overflow-y: auto;">
                            @foreach(App\Models\User::where('id', '!=', auth()->id())->pluck('name', 'id') as $id => $name)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="user_ids[]" value="{{ $id }}" id="user_{{ $id }}">
                                    <label class="form-check-label" for="user_{{ $id }}">
                                        {{ $name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Group</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Member Modal (for existing groups) -->
<div class="modal fade" id="addMemberModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Members</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ isset($currentRoom) ? route('chat.member.add', $currentRoom) : '#' }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Select Members to Add</label>
                        <div class="border p-2 rounded" style="max-height: 250px; overflow-y: auto;">
                            @if(isset($currentRoom) && $currentRoom->is_group)
                                @php 
                                    $existingMembers = $currentRoom->users->pluck('id')->toArray();
                                    $availableUsers = App\Models\User::whereNotIn('id', $existingMembers)->get();
                                @endphp
                                
                                @forelse($availableUsers as $user)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="user_ids[]" value="{{ $user->id }}" id="add_user_{{ $user->id }}">
                                        <label class="form-check-label" for="add_user_{{ $user->id }}">
                                            {{ $user->name }} ({{ $user->email }})
                                        </label>
                                    </div>
                                @empty
                                    <p class="text-muted">No more users available to add.</p>
                                @endforelse
                            @else
                                <p class="text-muted">Group information not available.</p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Members</button>
                </div>
            </form>
        </div>
    </div>
</div> 
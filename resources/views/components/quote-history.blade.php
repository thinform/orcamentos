<div class="mt-6 bg-white shadow overflow-hidden sm:rounded-lg">
    <div class="px-4 py-5 sm:px-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900">Histórico do Orçamento</h3>
    </div>
    <div class="border-t border-gray-200">
        <ul role="list" class="divide-y divide-gray-200">
            @forelse($history as $item)
                <li class="px-4 py-4 sm:px-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                @switch($item->action)
                                    @case('created')
                                        <svg class="h-6 w-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                        </svg>
                                        @break
                                    @case('updated')
                                        <svg class="h-6 w-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        @break
                                    @case('status_changed')
                                        <svg class="h-6 w-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                        </svg>
                                        @break
                                    @case('deleted')
                                        <svg class="h-6 w-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        @break
                                @endswitch
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $item->comment }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    Por {{ $item->user->name }} em {{ $item->created_at->format('d/m/Y H:i:s') }}
                                </div>
                                @if($item->changes)
                                    <div class="mt-2 text-sm text-gray-500">
                                        <details>
                                            <summary class="cursor-pointer text-indigo-600 hover:text-indigo-900">Ver alterações</summary>
                                            <div class="mt-2 space-y-1">
                                                @foreach($item->changes as $field => $value)
                                                    <div>
                                                        <span class="font-medium">{{ ucfirst($field) }}:</span>
                                                        <span>{{ is_array($value) ? json_encode($value) : $value }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </details>
                                    </div>
                                @endif
                            </div>
                        </div>
                        @if($item->action === 'status_changed')
                            <div class="flex items-center space-x-2">
                                @if($item->old_status)
                                    <span class="px-2 py-1 text-xs font-medium rounded-full
                                        {{ $item->old_status === 'approved' ? 'bg-green-100 text-green-800' : 
                                           ($item->old_status === 'rejected' ? 'bg-red-100 text-red-800' : 
                                            'bg-yellow-100 text-yellow-800') }}">
                                        {{ ucfirst($item->old_status) }}
                                    </span>
                                    <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                @endif
                                <span class="px-2 py-1 text-xs font-medium rounded-full
                                    {{ $item->new_status === 'approved' ? 'bg-green-100 text-green-800' : 
                                       ($item->new_status === 'rejected' ? 'bg-red-100 text-red-800' : 
                                        'bg-yellow-100 text-yellow-800') }}">
                                    {{ ucfirst($item->new_status) }}
                                </span>
                            </div>
                        @endif
                    </div>
                </li>
            @empty
                <li class="px-4 py-4 sm:px-6 text-gray-500 text-center">
                    Nenhum histórico encontrado.
                </li>
            @endforelse
        </ul>
    </div>
</div>
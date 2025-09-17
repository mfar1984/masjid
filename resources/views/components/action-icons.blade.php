@props([
    'record' => null,
    'editRoute' => '',
    'deleteAction' => '',
    'approveAction' => '',
    'rejectAction' => '',
    'layout' => 'desktop', // desktop or mobile
    'module' => null // module name for permission checking
])

@if($layout === 'desktop')
    <!-- Desktop Action Icons -->
    <td class="px-4 py-2 table-data text-center space-x-1">
        <!-- Accept/Reject for Pending Status -->
        @if($record->status === 'pending')
            <x-icons.approve-icon :id="$record->id" :nama="$record->nama" size="desktop" />
            <x-icons.reject-icon :id="$record->id" :nama="$record->nama" size="desktop" />
        @endif

        <!-- Standard Actions -->
        <x-icons.view-icon :route="$showRoute" size="desktop" />
        @if($module && auth()->user()->hasPermission($module, 'update'))
            <x-icons.edit-icon :route="$editRoute" size="desktop" />
        @endif

        <!-- Suspend/Unsuspend Actions -->
        @if($record->status === 'active')
            <x-icons.suspend-icon :id="$record->id" :nama="$record->nama" size="desktop" />
        @elseif($record->status === 'suspended')
            <x-icons.unsuspend-icon :id="$record->id" :nama="$record->nama" size="desktop" />
        @endif

        @if($module && auth()->user()->hasPermission($module, 'delete'))
            <x-icons.delete-icon :id="$record->id" :nama="$record->nama" size="desktop" />
        @endif
    </td>
@else
    <!-- Mobile Action Icons -->
    <div class="flex items-center space-x-2">
        <!-- Accept/Reject for Pending Status -->
        @if($record->status === 'pending')
            <x-icons.approve-icon :id="$record->id" :nama="$record->nama" size="mobile" />
            <x-icons.reject-icon :id="$record->id" :nama="$record->nama" size="mobile" />
        @endif

        <!-- Standard Actions -->
        <x-icons.view-icon :route="$showRoute" size="mobile" />
        @if($module && auth()->user()->hasPermission($module, 'update'))
            <x-icons.edit-icon :route="$editRoute" size="mobile" />
        @endif

        <!-- Suspend/Unsuspend Actions -->
        @if($record->status === 'active')
            <x-icons.suspend-icon :id="$record->id" :nama="$record->nama" size="mobile" />
        @elseif($record->status === 'suspended')
            <x-icons.unsuspend-icon :id="$record->id" :nama="$record->nama" size="mobile" />
        @endif

        @if($module && auth()->user()->hasPermission($module, 'delete'))
            <x-icons.delete-icon :id="$record->id" :nama="$record->nama" size="mobile" />
        @endif
    </div>
@endif

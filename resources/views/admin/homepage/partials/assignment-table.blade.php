<div class="mt-4">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h3 class="text-base font-semibold">{{ $title }}</h3>
        <a class="btn btn-sm" href="{{ $manageRoute }}">Manage {{ $title }}</a>
    </div>

    @if ($rows->isEmpty())
        <div role="alert" class="alert mt-4">
            <span>{{ $emptyText }}</span>
        </div>
    @else
        <div class="mt-4 overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th class="w-24">Order</th>
                        <th>Item</th>
                        <th class="w-32">Active</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rows as $index => $row)
                        <tr>
                            <td>
                                <input type="hidden" name="{{ $group }}[{{ $index }}][id]" value="{{ $row['id'] }}">
                                <input class="input input-sm w-20 @error($group . '.' . $index . '.sort_order') input-error @enderror" type="number" min="0" max="999" name="{{ $group }}[{{ $index }}][sort_order]" value="{{ $row['sort_order'] }}">
                                @error($group . '.' . $index . '.sort_order')
                                    <p class="label text-error">{{ $message }}</p>
                                @enderror
                            </td>
                            <td>
                                <div class="font-semibold">
                                    <a class="link link-hover" href="{{ $row['edit_url'] }}">{{ $row['title'] }}</a>
                                </div>
                                <div class="mt-1 line-clamp-2 text-sm leading-6 text-base-content/70">
                                    {{ strip_tags($row['description']) }}
                                </div>
                            </td>
                            <td>
                                <label class="flex items-center gap-2 text-sm">
                                    <input class="toggle toggle-sm" type="checkbox" name="{{ $group }}[{{ $index }}][is_active]" value="1" @checked($row['is_active'])>
                                    Active
                                </label>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

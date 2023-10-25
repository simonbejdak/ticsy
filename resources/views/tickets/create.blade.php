<x-app-layout>
    <form action="{{ route('tickets.store') }}" method="POST">
        @csrf
        <x-ticket-grid>
            <div class="font-light text-3xl">Create {{ $type->name ?? '' }}</div>
            <input type="hidden" name="type" value="{{ $type->id }}">
            <div class="flex flex-col space-y-2">
                <x-input-label for="category">Category</x-input-label>
                <select class="rounded-lg border border-gray-300 hover:cursor-pointer px-1 py-2" name="category" id="category" >
                    <option></option>
                    @foreach($categories as $category)
                        <option {{ ($category->id == old('category')) ? 'selected' : '' }} value="{{$category->id}}">{{ucfirst($category->name)}}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('category')" class="mt-2" />
            </div>

            <div class="flex flex-col space-y-2">
                <x-input-label class="font-bold text-lg" for="description">Description</x-input-label>
                <x-text-input
                    class="rounded-lg shadow-md p-2"
                    name="description"
                    id="description"
                    cols="30"
                    rows="1"
                    value="{{ old('description') ?? '' }}"
                ></x-text-input>
                <x-input-error :messages="$errors->get('description')" class="mt-2" />
            </div>
            <div class="flex flex-col space-y-2 mt-16">
                <x-secondary-button
                    class="rounded-lg py-2 bg-slate-800 text-white hover:bg-slate-600 shadow-md"
                    type="submit"
                >
                    Create
                </x-secondary-button>
            </div>
        </x-ticket-grid>
    </form>
</x-app-layout>

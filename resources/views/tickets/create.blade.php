<x-app-layout>
    <div class="mb-12 font-light text-3xl">{{ $formType }} {{ $type->name ?? '' }}</div>
    <form action="{{ $action }}" method="POST">
        @if($action === 'update')
            @method('PATCH')
        @endif
        @csrf
        <div>
            <div class="flex flex-col space-y-8">
                <input type="hidden" name="type" value="{{ $type->id }}">
                <div class="flex flex-col space-y-2">
                    <label class="font-bold text-lg" for="category">Category</label>
                    <select class="rounded-lg text-center shadow-md py-2" name="category" id="category" >
                        <option></option>
                        @foreach($categories as $category)
                            <option {{ ($category->id == old('category')) ? 'selected' : '' }} value="{{$category->id}}">{{ucfirst($category->name)}}</option>
                        @endforeach
                    </select>
                    @error('category')
                        <div class="text-red text-md">{{ $message }}</div>
                    @enderror
                </div>

                <div class="flex flex-col space-y-2">
                    <label class="font-bold text-lg" for="description">Description</label>
                    <textarea
                        class="rounded-lg text-center shadow-md py-2"
                        name="description"
                        id="description"
                        cols="30"
                        rows="1"
                    >{{ old('description') ?? ''}}</textarea>
                    @error('description')
                        <div class="text-red-500 text-md">{{ $message }}</div>
                    @enderror
                </div>

                <div class="flex flex-col space-y-2">
                    <label class="font-bold text-lg" for="priority">Priority</label>
                    <select class="rounded-lg text-center shadow-md py-2" name="priority" id="priority">
                        @foreach($priorities as $priority)
                            @empty(!old('$priority'))
                                <option {{ ($priority == old('priority')) ? 'selected' : '' }} value="{{ $priority }}">{{$priority}}</option>
                            @else
                                <option {{ $priority == $default_priority ? 'selected' : '' }} value="{{ $priority }}">{{$priority}}</option>
                            @endempty
                        @endforeach
                    </select>
                    @error('priority')
                        <div class="text-red-500 text-md">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="flex flex-col space-y-2 mt-16">
                <button class="rounded-lg py-2 bg-slate-800 text-white hover:bg-slate-600 shadow-md" type="submit">{{ $formType }}</button>
            </div>
        </div>
    </form>
</x-app-layout>

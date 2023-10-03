@if(Session::has('info'))
    <div
        x-data="{isOpen: false}"
        x-show="isOpen"
        x-init="
            setTimeout(() => { isOpen = true; }, 100);
            setTimeout(() => { isOpen = false; }, 3000);
           "
        x-cloak
        x-transition:enter.duration.300ms
        x-transition:leave.duration.300ms
        class="fixed m-10 inset-x-0 mx-auto rounded-lg flex flex-row bg-blue-400 text-white justify-center p-4 hover:opacity-100 w-2/3 z-50">
        <p>{{ Session::get('info') }}</p>
        <div @click="isOpen = false" class="absolute top-0 right-2 hover:cursor-pointer ">&cross;</div>
    </div>
@endif
@if(Session::has('success'))
    <div
        x-data="{isOpen: false}"
        x-show="isOpen"
        x-init="
            setTimeout(() => { isOpen = true; }, 100);
            setTimeout(() => { isOpen = false; }, 3000);
           "
        x-cloak
        x-transition:enter.duration.300ms
        x-transition:leave.duration.300ms
        class="fixed m-10 inset-x-0 mx-auto rounded-lg flex flex-row bg-green-500 text-white justify-center p-4 hover:opacity-100 w-2/3 z-50">
        <p>{{ Session::get('success') }}</p>
        <div @click="isOpen = false" class="absolute top-0 right-2 hover:cursor-pointer ">&cross;</div>
    </div>
@endif
@if(Session::has('warning'))
    <div
        x-data="{isOpen: false}"
        x-show="isOpen"
        x-init="
            setTimeout(() => { isOpen = true; }, 100);
            setTimeout(() => { isOpen = false; }, 3000);
           "
        x-cloak
        x-transition:enter.duration.300ms
        x-transition:leave.duration.300ms
        class="fixed m-10 inset-x-0 mx-auto rounded-lg flex flex-row bg-yellow-400 text-white justify-center p-4 hover:opacity-100 w-2/3 z-50">
        <p>{{ Session::get('warning') }}</p>
        <div @click="isOpen = false" class="absolute top-0 right-2 hover:cursor-pointer ">&cross;</div>
    </div>
@endif
@if(Session::has('error'))
    <div
        x-data="{isOpen: false}"
        x-show="isOpen"
        x-init="
            setTimeout(() => { isOpen = true; }, 100);
            setTimeout(() => { isOpen = false; }, 3000);
           "
        x-cloak
        x-transition:enter.duration.300ms
        x-transition:leave.duration.300ms
        class="fixed m-10 inset-x-0 mx-auto rounded-lg flex flex-row bg-red-500 text-white justify-center p-4 hover:opacity-100 w-2/3 z-50">
        <p>{{ Session::get('error') }}</p>
        <div @click="isOpen = false" class="absolute top-0 right-2 hover:cursor-pointer ">&cross;</div>
    </div>
@endif

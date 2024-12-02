<div  class=" w-full  mx-auto  bg-slate-200">
    <header class="bg-white dark:bg-gray-800 shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Request') }}
            </h2>
        </div>
    </header>
    <div class=" w-full md:max-w-5xl mx-auto " style="overflow-x: hidden">


        <form wire:submit="create" class="mt-5">
            {{$this->form}}

            <button type="submit"
               class="animate-bounce focus:animate-none hover:animate-none inline-flex text-md font-medium bg-indigo-800 mt-3 px-4 py-2 rounded-lg tracking-wide text-white">
                <span class="ml-2">Submit 🏀</span>
            </button>
        </form>
    </div>


    {{-- {{$this->table}} --}}
    {{-- {{$items}} --}}


    {{-- </x-filament-actions::modals> --}}
</div>

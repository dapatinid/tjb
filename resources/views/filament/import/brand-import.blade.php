<div>
    <div>
        <form wire:submit='save' class="w-full max-w-sm flex">
            <div class="mr-2">
                <label for=""></label>
                <input type="file" wire:model='file'
                class="shadow appearance-none border rounded w-full py-2 px-3
                text-gray-700 leading-tight focus:outline-none focus:shadow-none">
            </div>
            <div class="flex items-center justify-between mt-1">
                <button
                class="bg-gray-500 hover:bg-gray-500 text-gray-500 dark:text-white font-bold py-2 px-4 rounded-lg
                focus:outline-none focus:shadow-outline"
                type="submit">
                Import
                </button>
            </div>
        </form>
    </div>
</div>